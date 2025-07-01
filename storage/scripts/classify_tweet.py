import sys
from transformers import pipeline

classifier = pipeline("zero-shot-classification", model="facebook/bart-large-mnli")

labels = [
    "Ransomware",
    "Phishing & Scam",
    "DDoS Attack",
    "Data Breach & Leaks",
    "Vulnerability Exploit",
    "Cyber Espionage",
    "Zero-Day Exploit",
    "Malware",
    "Botnet Attack",
    "Credential Stuffing",
    "Insider Threat",
    "Nation-State Attack",
    "Supply Chain Attack",
    "Social Engineering",
    "Dark Web Activity",
    "APT (Advanced Persistent Threat)",
    "Cryptojacking",
    "IoT Attack",
    "Identity Theft",
    "Fake Software & Rogueware",
    "Data Exfiltration",
    "Man-in-the-Middle (MitM) Attack"
]

if len(sys.argv) < 2:
    print("Unknown")
    sys.exit(1)

text = sys.argv[1]  # Ambil teks dari command line argument

result = classifier(text, labels)
print(result["labels"][0])  # Print hanya label terbaik
