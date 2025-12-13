import csv
import json
import math
import os
from collections import Counter

# --- AYARLAR ---
csv_file = "annotations_metadata.csv"           # Senin değiştirdiğin kısa isim
text_folder = "sampled_train"   # Klasör adı
output_file = "score_map.json"  

documents = []
labels = []

# Klasör kontrolü
if not os.path.exists(csv_file):
    print(f"HATA: '{csv_file}' bulunamadı!")
    exit()

print("Veriler taranıyor, eksik dosyalar atlanacak...")

try:
    # 1. CSV'Yİ OKU
    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.reader(f)
        next(reader) # Başlık satırını atla
        
        found_count = 0
        missing_count = 0
        
        for row in reader:
            # CSV yapısına göre ID ve Label al
            if len(row) > 4:
                file_id = row[0]  
                label = row[4]    
                
                # Dosya yolunu oluştur
                file_path = os.path.join(text_folder, f"{file_id}.txt")
                
                # --- KRİTİK DÜZELTME BURADA ---
                # Dosya VARSA oku, YOKSA atla (Hata verme!)
                if os.path.exists(file_path):
                    try:
                        with open(file_path, 'r', encoding='utf-8') as txt_file:
                            text = txt_file.read()
                            documents.append(text)
                            labels.append(label)
                            found_count += 1
                    except:
                        pass # Okurken hata olursa da geç
                else:
                    missing_count += 1

    print(f"✅ İŞLEM TAMAM: {found_count} dosya bulundu ve okundu.")
    print(f"⚠️ {missing_count} dosya eksikti ve atlandı (Sorun yok).")

    # 2. IDF HESAPLA
    N = len(documents)
    if N == 0:
        print("HATA: Hiçbir metin dosyası bulunamadı! 'sampled_train' klasörü boş mu?")
        exit()

    print("Puanlar hesaplanıyor...")
    
    tokenized_docs = [doc.lower().split() for doc in documents]

    word_doc_count = Counter()
    for doc in tokenized_docs:
        unique_words = set(doc)
        for word in unique_words:
            word_doc_count[word] += 1

    idf_map = {}
    for word, count in word_doc_count.items():
        idf_map[word] = math.log(N / count)

    # 3. SCORE MAP HESAPLA
    score_map = {word: 0.0 for word in idf_map}

    for i, doc in enumerate(tokenized_docs):
        label = labels[i]
        tf_counter = Counter(doc)
        
        for word, count in tf_counter.items():
            tf = count / len(doc)
            tf_idf = tf * idf_map[word]
            
            if label == 'hate':
                score_map[word] -= tf_idf
            else:
                score_map[word] += tf_idf

    # 4. JSON KAYDET
    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(score_map, f, ensure_ascii=False, indent=4)

    print(f"🎉 BİTTİ! '{output_file}' oluşturuldu. PHP klasörüne atabilirsin.")

except Exception as e:
    print(f"Beklenmeyen bir hata: {e}")