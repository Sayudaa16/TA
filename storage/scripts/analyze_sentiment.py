import mysql.connector
from transformers import pipeline
import re

# Koneksi ke database Laravel
db = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",  # Sesuaikan dengan password database kamu
    database="dasaratha"
)

cursor = db.cursor()

# Load Model Sentimen
sentiment_model = pipeline("sentiment-analysis", model="w11wo/indonesian-roberta-base-sentiment-classifier")

def is_quiz_tweet(text):
    quiz_keywords = ["kuis", "giveaway", "quiz", "lomba"]
    number_pattern = r"[A-Za-z]-\d"
    return any(keyword in text.lower() for keyword in quiz_keywords) or re.search(number_pattern, text)

# Ambil tweet yang belum dianalisis
cursor.execute("SELECT id, text FROM tweets WHERE sentiment IS NULL")
tweets = cursor.fetchall()

for tweet in tweets:
    tweet_id, text = tweet

    # Skip tweet kuis
    if is_quiz_tweet(text):
        sentiment = "skip"
    else:
        sentiment = sentiment_model(text)[0]['label']

    # Update database dengan hasil analisis sentimen
    cursor.execute("UPDATE tweets SET sentiment = %s WHERE id = %s", (sentiment, tweet_id))

db.commit()
cursor.close()
db.close()

print("Sentiment analysis completed.")
