import mysql.connector
from transformers import pipeline

# Koneksi ke database Laravel
db = mysql.connector.connect(
     host="127.0.0.1",
    user="root",
    password="",  # Sesuaikan dengan password database kamu
    database="dasaratha"
)

cursor = db.cursor(dictionary=True)

# Inisialisasi model zero-shot classification
classifier = pipeline("zero-shot-classification", model="facebook/bart-large-mnli")

# Label klasifikasi yang lebih lengkap
labels = [
    # Malware Variants
    "Malware", "Ransomware", "Spyware", "Trojan", "Worm", "Botnet", "Rootkit",
    "Adware", "Keylogger", "Remote Access Trojan (RAT)", "Stealer Malware",
    "Fileless Malware", "Polymorphic Malware", "Packers & Crypters", "Malvertising",

    # Specific Ransomware Families
    "WannaCry", "LockBit", "Conti", "REvil", "Black Basta", "Clop", "DarkSide",
    "Maze", "Ryuk", "Avaddon", "Babuk", "Sodinokibi", "Hive Ransomware",
    "MedusaLocker", "Netwalker", "BlackCat", "Royal Ransomware", "Cuba Ransomware",
    "Ragnar Locker", "Vice Society", "SunCrypt", "Egregor", "Pysa Ransomware",
    "MountLocker", "Nemty Ransomware", "Thanos Ransomware", "REactor Ransomware",

    # Advanced Persistent Threat (APT) Groups
    "APT1", "APT3", "APT10", "APT17", "APT19", "APT28", "APT29", "APT30", "APT32",
    "APT33", "APT34", "APT35", "APT38", "APT39", "APT41", "Lazarus Group", "Fancy Bear",
    "Cozy Bear", "Equation Group", "Turla Group", "MuddyWater", "Silent Librarian",
    "Charming Kitten", "Evil Corp", "Sandworm Team", "DarkHotel", "Winnti Group",
    "TA505", "FIN7", "Cobalt Group", "Silent Ransom", "Wizard Spider", "Scattered Spider",

    # OWASP Top 10 (2021)
    "OWASP A01: Broken Access Control", "OWASP A02: Cryptographic Failures",
    "OWASP A03: Injection", "OWASP A04: Insecure Design",
    "OWASP A05: Security Misconfiguration", "OWASP A06: Vulnerable and Outdated Components",
    "OWASP A07: Identification and Authentication Failures",
    "OWASP A08: Software and Data Integrity Failures",
    "OWASP A09: Security Logging and Monitoring Failures",
    "OWASP A10: Server-Side Request Forgery (SSRF)",

    # Exploit Categories
    "Privilege Escalation", "Buffer Overflow", "Heap Overflow", "DLL Hijacking",
    "DLL Side-Loading", "Remote Code Execution (RCE)", "Local File Inclusion (LFI)",
    "Server-Side Template Injection (SSTI)", "XML External Entity (XXE)", "Code Injection",
    "Race Condition Exploit", "Subdomain Takeover", "Session Fixation Attack",
    "Pass-the-Hash Attack", "DNS Cache Poisoning", "Man-in-the-Browser Attack",

    # DDoS & Network Attacks
    "DDoS", "DNS Amplification", "HTTP Flood", "Botnet Attack", "SYN Flood",
    "Ping of Death", "Teardrop Attack", "Slowloris Attack", "Memcached DDoS",
    "UDP Flood", "Smurf Attack", "NTP Amplification Attack", "IP Spoofing",

    # Cyber Warfare & Nation-State Attacks
    "Cyber Attack", "Nation-State Attack", "APT (Advanced Persistent Threat)",
    "Zero-Day Exploit", "State-Sponsored Hacking", "Cyber Terrorism",
    "Cyber Espionage", "Election Interference", "Military Cyber Operations",

    # Frameworks & Attack Methods
    "MITRE ATT&CK Framework", "Cyber Kill Chain", "NIST Cybersecurity Framework",
    "Diamond Model of Intrusion Analysis", "MITRE D3FEND", "Tactics, Techniques, and Procedures (TTPs)",
    "Indicators of Compromise (IoC)", "Indicators of Attack (IoA)",

    # Firewall & Security Solutions
    "Fortinet", "FortiGate", "Palo Alto Networks", "Cisco ASA",
    "Check Point Firewall", "Sophos UTM", "Juniper SRX", "Zscaler",
    "Cloudflare WAF", "Barracuda WAF", "Imperva WAF", "AWS WAF",
    "F5 Big-IP", "NGFW (Next-Gen Firewall)", "Intrusion Detection System (IDS)",
    "Intrusion Prevention System (IPS)", "SIEM (Security Information and Event Management)",
    "CrowdStrike Falcon", "Microsoft Defender ATP", "Trellix (McAfee & FireEye)",
    "Carbon Black", "SentinelOne", "Darktrace AI", "Rapid7 InsightIDR",
    "Splunk Security", "QRadar SIEM", "Elastic Security",

    # Social Engineering & Identity Attacks
    "Phishing", "Spear Phishing", "Whaling", "Smishing", "Vishing",
    "Business Email Compromise (BEC)", "Credential Stuffing", "Deepfake",
    "Synthetic Identity Fraud", "Fake News & Disinformation", "Angler Phishing",
    "Quishing (QR Code Phishing)", "Homograph Attack", "Pretexting",

    # Financial & Cryptojacking Attacks
    "Cryptojacking", "Financial Fraud", "Ponzi Scheme", "Pump and Dump Scam",
    "Romance Scam", "Fake Investment Scam", "Carding Fraud", "Gift Card Fraud",
    "Cryptocurrency Wallet Drain", "Bitcoin Dusting Attack", "Crypto Rug Pull Scams",

    # Supply Chain & Infrastructure Attacks
    "Supply Chain Attack", "IoT Attack", "Cloud Security Breach",
    "ICS/SCADA Attack", "Critical Infrastructure Attack", "Firmware Exploitation",
    "Software Supply Chain Attack", "Compromised CI/CD Pipeline",
    "Third-Party Vendor Risk", "Poisoned Software Update", "Dependency Confusion Attack",

    # Dark Web & Underground Activities
    "Dark Web Activities", "Hacking Group Activity", "Underground Market",
    "Illegal Data Trading", "Darknet Market", "Carding Fraud", "Identity Trading",
    "Initial Access Brokers (IABs)", "Bulletproof Hosting Services", "Ransomware-as-a-Service (RaaS)",
    "Cybercriminal Underground Forum", "Weaponized Malware Marketplace",

    # Mobile & Emerging Threats
    "Mobile Malware", "Banking Trojan", "SIM Swapping", "Mobile App Spoofing",
    "Side-Loading Malicious Apps", "5G Security Threats", "QR Code Attacks",
    "E-SIM Exploits", "Wearable Device Attacks", "Smart Home Device Hijacking",

    # Regulations & Compliance
    "Regulatory & Compliance", "GDPR", "CCPA", "NIST Compliance", "ISO 27001",
    "Cyber Insurance", "Cybersecurity Laws", "HIPAA Compliance", "SOC 2 Compliance",
    "CMMC (Cybersecurity Maturity Model Certification)", "FedRAMP Security",
    "PCI DSS Compliance", "Cyber Risk Assessment Standards", "SBOM (Software Bill of Materials)",

    # General Cybersecurity & Awareness
    "General Cybersecurity", "Cybersecurity Policy", "Security Awareness Training",
    "Red Teaming", "Penetration Testing", "Bug Bounty Programs", "Threat Intelligence Sharing",
    "Deception Technology", "Cyber Resilience", "Incident Response Plan",
    "Tabletop Exercises", "Digital Forensics", "XDR (Extended Detection and Response)",
    "SOC (Security Operations Center)", "Purple Team Exercises"
]


# Proses klasifikasi untuk `hacker_news`
cursor.execute("SELECT id, title, description FROM hacker_news WHERE category IS NULL")
hacker_news_items = cursor.fetchall()

for news in hacker_news_items:
    text = f"{news['title']} {news['description']}"
    result = classifier(text, labels)
    category = result["labels"][0]  # Ambil kategori dengan skor tertinggi
    cursor.execute("UPDATE hacker_news SET category = %s WHERE id = %s", (category, news["id"]))
    db.commit()

# Proses klasifikasi untuk `cyber_security_news`
cursor.execute("SELECT id, title, description FROM cyber_security_news WHERE category IS NULL")
cyber_news_items = cursor.fetchall()

for news in cyber_news_items:
    text = f"{news['title']} {news['description']}"
    result = classifier(text, labels)
    category = result["labels"][0]  # Ambil kategori dengan skor tertinggi
    cursor.execute("UPDATE cyber_security_news SET category = %s WHERE id = %s", (category, news["id"]))
    db.commit()

print("Klasifikasi selesai!")
cursor.close()
db.close()
