import numpy as np
import pandas as pd
from sklearn.cluster import KMeans
import joblib  


def carregar_dados():
  
    dados = pd.DataFrame({
        'product_id': [1, 2, 3, 4, 5],
        'popularity': [150, 300, 120, 500, 350],
    
    })
    return dados


def preprocessar_dados(dados):
    return dados[['popularity', 'rating']].values

def treinar_modelo(dados, n_clusters=3):
    modelo = KMeans(n_clusters=n_clusters, random_state=42)
    modelo.fit(dados)
    return modelo

def salvar_modelo(modelo, caminho='kmeans_model.pkl'):
    joblib.dump(modelo, caminho)
    print(f"Modelo salvo em {caminho}")

if __name__ == "__main__":
    
    dados = carregar_dados()
    print("Dados carregados:\n", dados)

 
    dados_preprocessados = preprocessar_dados(dados)
    print("Dados pré-processados:\n", dados_preprocessados)


    modelo = treinar_modelo(dados_preprocessados, n_clusters=3)


    salvar_modelo(modelo)
