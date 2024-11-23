import openai
import json
import os


openai.api_key = os.getenv("OPENAI_API_KEY")


data = [
    {"id": 1, "titulo": "Book 1", "autor": "Author 1", "price": 22.59, "image": "book__3.jpg", "tags": "fantasy adventure"},
    {"id": 2, "titulo": "Book 2", "autor": "Author 2", "price": 17.99, "image": "book__4.jpg", "tags": "mystery thriller"},
    {"id": 3, "titulo": "Book 3", "autor": "Author 3", "price": 9.50, "image": "home_book3.png", "tags": "romance drama"},
    {"id": 4, "titulo": "Book 4", "autor": "Author 4", "price": 12.99, "image": "book__2.jpg", "tags": "fantasy epic"},
    {"id": 5, "titulo": "Book 5", "autor": "Author 5", "price": 15.99, "image": "book__1.jpg", "tags": "science fiction"},
    {"id": 6, "titulo": "Book 6", "autor": "Author 6", "price": 25.50, "image": "book__5.jpg", "tags": "fantasy magic"},
    {"id": 7, "titulo": "Book 7", "autor": "Author 7", "price": 18.75, "image": "book__6.jpg", "tags": "mystery detective"},
    {"id": 8, "titulo": "Book 8", "autor": "Author 8", "price": 20.00, "image": "book__7.jpg", "tags": "romance fantasy"},
    {"id": 9, "titulo": "Book 9", "autor": "Author 9", "price": 14.30, "image": "book__8.jpg", "tags": "drama adventure"}
]

book_embeddings = []
for book in data:
    response = openai.Embedding.create(
        model="text-embedding-ada-002",  # Use the correct model here
        input=book['tags']
    )
    book_embeddings.append({"id": book["id"], "embedding": response['data'][0]['embedding'], **book})

# User's preference for recommendations (as an example)
user_preference = "fantasy magic"
user_embedding = openai.Embedding.create(
    model="text-embedding-ada-002",  # Same model as above
    input=user_preference
)["data"][0]["embedding"]

def cosine_similarity(a, b):
    """Calculate cosine similarity between two vectors."""
    return sum(x * y for x, y in zip(a, b)) / ((sum(x**2 for x in a)**0.5) * (sum(y**2 for y in b)**0.5))

# Sort books by similarity to the user's preference
recommendations = sorted(
    book_embeddings,
    key=lambda book: cosine_similarity(user_embedding, book["embedding"]),
    reverse=True
)[:8]

# Output the recommendations in JSON format
output = [
    {
        "id": book["id"],
        "titulo": book["titulo"],
        "price": book["price"],
        "image": book["image"]
    }
    for book in recommendations
]

print(json.dumps(output))
