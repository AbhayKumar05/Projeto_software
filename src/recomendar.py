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
    cursor.execute("""
        SELECT p.id, p.name, p.price, p.image, p.author, g.name AS genre 
        FROM products p 
        JOIN genres g ON p.genre_id = g.id
    """)
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
    df = pd.DataFrame(produtos, columns=["id", "name", "price", "image", "author", "genre"])
    dados_preprocessados = df[['price']].values 
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

        # Load product data
        produtos = carregar_dados_produtos()
        dados_produtos, df = preprocessar_dados(produtos)
        kmeans = treinar_kmeans(dados_produtos)
        df['cluster'] = kmeans.labels_

        # Load purchased product IDs
        produtos_comprados_ids = carregar_historico_compras(user_id)
        if not produtos_comprados_ids:
            return jsonify({"recomendacoes": [], "mensagem": "Nenhum histórico de compras encontrado."}), 200

        # Determine clusters of purchased products
        clusters_comprados = df[df['id'].isin(produtos_comprados_ids)]['cluster'].unique()

        # Recommend products not purchased but in the same cluster
        recomendacoes = df[df['cluster'].isin(clusters_comprados) & ~df['id'].isin(produtos_comprados_ids)]
        recomendacoes_json = recomendacoes[["id", "name", "price", "image", "author", "genre"]].to_dict(orient='records')

        # Return the recommendations as JSON
        return jsonify({"recomendacoes": recomendacoes_json}), 200
    except Exception as e:
        return jsonify({"erro": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True)

