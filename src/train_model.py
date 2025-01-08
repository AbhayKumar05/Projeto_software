import mysql.connector
import pandas as pd
from sklearn.cluster import KMeans
import joblib

def conectar_base_dados(host, user, password, database):
    conexao = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database
    )
    return conexao


def carregar_dados(conexao):
    query = """
    SELECT 
        livros.id AS product_id,
        livros.titulo,
        livros.genero,
        livros.autor,
        livros.preco,
        livros.popularidade AS popularity,
    FROM 
        livros;
    """
    return pd.read_sql(query, conexao)


GENEROS_LITERARIOS = [
    "Romance", "Novela", "Conto", "Crônica", "Poema", "Canção", "Drama histórico", "Teatro de vanguarda",
    "Fantasia", "Ficção científica", "Distopia", "Ação e aventura", "Ficção Policial", "Horror",
    "Thriller e Suspense", "Ficção histórica", "Ficção Feminina", "LGBTQ+", "Ficção Contemporânea",
    "Realismo mágico", "Graphic Novel", "Young adult – Jovem adulto", "New adult – Novo Adulto", "Infantil",
    "Memórias e autobiografia", "Biografia", "Gastronomia", "Arte e Fotografia", "Autoajuda",
    "História", "Viagem", "Crimes Reais", "Humor", "Ensaios", "Guias & Como fazer", 
    "Religião e Espiritualidade", "Humanidades e Ciências Sociais", "Paternidade e família",
    "Tecnologia e Ciência"
]


def preprocessar_dados(dados):
    
    dados['genero'] = dados['genero'].fillna('Outro')
    dados_encoded = pd.get_dummies(dados, columns=['genero', 'autor'])
    return dados_encoded[['preco', 'popularity', 'rating'] + [col for col in dados_encoded.columns if col.startswith('genero_') or col.startswith('autor_')]].values


def treinar_modelo(dados, n_clusters=3):
    modelo = KMeans(n_clusters=n_clusters, random_state=42)
    modelo.fit(dados)
    return modelo


def salvar_modelo(modelo, caminho='kmeans_model.pkl'):
    joblib.dump(modelo, caminho)
    print(f"Modelo salvo em {caminho}")


if __name__ == "__main__":
    
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': 'senha',
        'database': 'shop_db'
    }

    try:
        
        conexao = conectar_base_dados(**db_config)
        print("Conexão estabelecida com sucesso!")

        
        dados = carregar_dados(conexao)
        print("Dados carregados:\n", dados.head())

        
        dados_preprocessados = preprocessar_dados(dados)
        print("Dados pré-processados:\n", dados_preprocessados)

        
        modelo = treinar_modelo(dados_preprocessados, n_clusters=3)

        
        salvar_modelo(modelo)

    except Exception as e:
        print(f"Erro: {e}")
    finally:
        if 'conexao' in locals() and conexao.is_connected():
            conexao.close()
            print("Conexão encerrada.")
