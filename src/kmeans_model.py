import mysql.connector
import numpy as np
from sklearn.cluster import KMeans
import json

def conectar_bd():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="root",
        database="shop_db.sql"
    )


def carregar_dados_livros():
    conn = conectar_bd()
    cursor = conn.cursor()
    cursor.execute("SELECT id, popularity, rating FROM books")
    livros = cursor.fetchall()
    cursor.close()
    conn.close()
    return livros


def treinar_kmeans(dados_livros, n_clusters=3):
    kmeans = KMeans(n_clusters=n_clusters, random_state=42)
    kmeans.fit(dados_livros)
    return kmeans

def recomendar_livros(preferencias, kmeans, livros):
    cluster = kmeans.predict([preferencias])  
    recomendacoes = [livro[0] for i, livro in enumerate(livros) if kmeans.labels_[i] == cluster[0]]
    return recomendacoes

def salvar_recomendacoes(user_id, recommended_product_ids):
    conn = conectar_bd()
    cursor = conn.cursor()
    cursor.execute("""
        INSERT INTO recommendations (user_id, recommended_products)
        VALUES (%s, %s)
        ON DUPLICATE KEY UPDATE recommended_products = %s
    """, (user_id, json.dumps(recommended_product_ids), json.dumps(recommended_product_ids)))
    conn.commit()
    cursor.close()
    conn.close()

# Main
if __name__ == "__main__":
    
    livros = carregar_dados_livros()
    ids_livros = [livro[0] for livro in livros]
    dados_livros = np.array([[livro[1], livro[2]] for livro in livros]) 

    kmeans = treinar_kmeans(dados_livros)

    preferencias_utilizador = [60, 4.5]  
    recomendados = recomendar_livros(preferencias_utilizador, kmeans, livros)

    
    user_id = 1 
    salvar_recomendacoes(user_id, recomendados)

    print(f"Recomendações para o utilizador {user_id}: {recomendados}")
