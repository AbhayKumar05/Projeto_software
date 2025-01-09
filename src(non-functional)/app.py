from flask import Flask, jsonify, request
import mysql.connector
import numpy as np
from sklearn.cluster import KMeans
import pandas as pd
from flask import session
from flask_cors import CORS

app = Flask(__name__)

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
    cursor.execute("SELECT id, name, price, category, genre_id FROM products")
    produtos = cursor.fetchall()
    cursor.close()
    conn.close()
    return produtos

# Carregar histórico de compras de um utilizador
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
    df = pd.DataFrame(produtos, columns=["id", "name", "price", "genres"])
    df['category'] = pd.Categorical(df['genres']).codes
    dados_preprocessados = df[['price', 'genre_id']].values
    return dados_preprocessados, df

def treinar_kmeans(dados_produtos, n_clusters=3):
    kmeans = KMeans(n_clusters=n_clusters, random_state=42)
    kmeans.fit(dados_produtos)
    return kmeans

def recomendar_com_base_compras(produtos_comprados_ids, kmeans, df):
    clusters_comprados = df[df['id'].isin(produtos_comprados_ids)]['cluster'].unique()
    recomendacoes = df[df['cluster'].isin(clusters_comprados) & ~df['id'].isin(produtos_comprados_ids)]
    return recomendacoes[['id', 'name', 'price']].to_dict(orient="records")

@app.route("/recomendar", methods=["POST"])
def recomendar():
    try:
        data = request.json
        user_id = data.get("user_id")

        if not user_id:
            return jsonify({"erro": "ID do utilizador é obrigatório."}), 400

        produtos = carregar_dados_produtos()
        dados_produtos, df = preprocessar_dados(produtos)

        kmeans = treinar_kmeans(dados_produtos)

        df['cluster'] = kmeans.labels_

        produtos_comprados_ids = carregar_historico_compras(user_id)
        if not produtos_comprados_ids:
            return jsonify({"recomendacoes": [], "mensagem": "Nenhum histórico de compras encontrado."}), 200

        recomendacoes = recomendar_com_base_compras(produtos_comprados_ids, kmeans, df)
        return jsonify({"recomendacoes": recomendacoes})

    except Exception as e:
        return jsonify({"erro": str(e)}), 500

@app.route('/recomendacao', methods=['POST'])
def obter_recomendacoes():
    # Recebe o histórico do usuário
    dados_usuario = request.json
    if not dados_usuario or 'buscas' not in dados_usuario or 'compras' not in dados_usuario:
        return jsonify({'erro': 'Dados inválidos'}), 400

    # Gera as recomendações
    try:
        recomendacoes = recomendar_livros(dados_usuario)
        return jsonify({'recomendacoes': recomendacoes})
    except Exception as e:
        app.logger.error(f"Erro ao processar recomendação: {e}")
        return jsonify({'erro': 'Erro interno ao gerar recomendações'}), 500

if __name__ == "__main__":
    app.run(debug=True, host="127.0.0.1", port=5001)
