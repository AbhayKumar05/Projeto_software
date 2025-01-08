import mysql.connector
import numpy as np
from sklearn.cluster import KMeans
import json
import pandas as pd


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
        SELECT id, price, category, tags FROM products
    """)
    produtos = cursor.fetchall()
    cursor.close()
    conn.close()
    return produtos


def preprocessar_dados(produtos):
    df = pd.DataFrame(produtos, columns=["id", "price", "category", "tags"])
    df['category'] = pd.Categorical(df['category']).codes
    df['tags'] = pd.Categorical(df['tags']).codes
    dados_preprocessados = df[['price', 'category', 'tags']].values
    return dados_preprocessados, df


def treinar_kmeans(dados_produtos, n_clusters=3):
    kmeans = KMeans(n_clusters=n_clusters, random_state=42)
    kmeans.fit(dados_produtos)
    return kmeans

def recomendar_produtos(preferencias, kmeans, produtos, df):
    cluster = kmeans.predict([preferencias])
    recomendacoes = [produto[0] for i, produto in enumerate(produtos) if kmeans.labels_[i] == cluster[0]]
    return recomendacoes

def salvar_recomendacoes(user_id, recommended_product_ids):
    conn = conectar_bd()
    cursor = conn.cursor()

    
    recomendados_str = ','.join(map(str, recommended_product_ids))
    cursor.execute("""
        INSERT INTO orders (user_id, total_products, payment_status)
        VALUES (%s, %s, 'pending')
        ON DUPLICATE KEY UPDATE total_products = %s
    """, (user_id, recomendados_str, recomendados_str))

    conn.commit()
    cursor.close()
    conn.close()

# Main
if __name__ == "__main__":
    
    produtos = carregar_dados_produtos()

    
    dados_produtos, df = preprocessar_dados(produtos)

    
    kmeans = treinar_kmeans(dados_produtos)

    
    df['cluster'] = kmeans.labels_

    
    print("Produtos com seus respectivos clusters:")
    print(df[['id', 'cluster']])

    
    preferencias_utilizador = [20.00, 0, 5]  

    
    recomendados = recomendar_produtos(preferencias_utilizador, kmeans, produtos, df)

    
    user_id = 1  
    salvar_recomendacoes(user_id, recomendados)

    print(f"Recomendações para o utilizador {user_id}: {recomendados}")
