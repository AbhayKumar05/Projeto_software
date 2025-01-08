from flask import Flask, request, jsonify, session
import mysql.connector
import pandas as pd
from sklearn.cluster import KMeans
import json

app = Flask(__name__)
app.secret_key = '7a2b7aab5c34d9c4b178a1d94aabf29f'

def conectar_bd():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="root",
        database="shop_db"
    )

def carregar_dados_produtos():
    conn = conectar_bd()
    cursor = conn.cursor()
    cursor.execute("SELECT id, name, price, category, tags FROM products")
    produtos = cursor.fetchall()
    cursor.close()
    conn.close()
    return produtos

def carregar_historico_compras(user_id):
    conn = conectar_bd()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT total_products FROM orders WHERE user_id = %s
    """, (user_id,))
    compras = cursor.fetchall()
    cursor.close()
    conn.close()

    produtos_comprados = []
    for compra in compras:
        produtos_comprados.extend(map(int, compra[0].split(',')))
    return produtos_comprados

def preprocessar_dados(produtos):
    df = pd.DataFrame(produtos, columns=["id", "name", "price", "category", "tags"])
    df['category'] = pd.Categorical(df['category']).codes
    df['tags'] = pd.Categorical(df['tags']).codes
    dados_preprocessados = df[['price', 'category', 'tags']].values
    return dados_preprocessados, df

def treinar_kmeans(dados_produtos, n_clusters=3):
    kmeans = KMeans(n_clusters=n_clusters, random_state=42)
    kmeans.fit(dados_produtos)
    return kmeans

@app.route("/recomendar", methods=["POST"])
def recomendar():
    try:
        user_id = session.get("user_id")
        if not user_id:
            return jsonify({"erro": "Utilizador não autenticado."}), 401

        produtos = carregar_dados_produtos()
        dados_produtos, df = preprocessar_dados(produtos)
        kmeans = treinar_kmeans(dados_produtos)
        df['cluster'] = kmeans.labels_
        produtos_comprados_ids = carregar_historico_compras(user_id)
        if not produtos_comprados_ids:
            recomendacoes_vazias = jsonify({"recomendacoes": [], "mensagem": "Nenhum histórico de compras encontrado."}), 200
            with open("recomendacoes.json", "w") as f:
                json.dump(recomendacoes_vazias, f)
            return jsonify(recomendacoes_vazias), 200

        clusters_comprados = df[df['id'].isin(produtos_comprados_ids)]['cluster'].unique()
        recomendacoes = df[df['cluster'].isin(clusters_comprados) & ~df['id'].isin(produtos_comprados_ids)]
        recomendacoes_json = recomendacoes.to_dict(orient='records')

        with open("recomendacoes.json", "w") as f:
                json.dump(recomendacoes_json, f)
        return jsonify({"recomendacoes": recomendacoes_json}), 200
    except Exception as e:
        return jsonify({"erro": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)
