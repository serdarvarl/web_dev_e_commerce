import csv
import json
import math
import os
from collections import Counter


csv_file = "annotations_metadata.csv"   
text_folder = "sampled_train"   
output_file = "score_map.json"  

documents = []
labels = []

# check dossier si exist
if not os.path.exists(csv_file):
    print(f"Erreur: '{csv_file}' n'est pas trouvable!")
    exit()

print("Loooddinngggg")

try:
    # csv lire
    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.reader(f)
        next(reader) # sauter les title
        
        found_count = 0
        missing_count = 0
        
        for row in reader:
            # ca depent srrucuter de csv 
            if len(row) > 4:
                file_id = row[0]  
                label = row[4]    
                
                #path
                file_path = os.path.join(text_folder, f"{file_id}.txt")
                
                # si y a des errure pass
                if os.path.exists(file_path):
                    try:
                        with open(file_path, 'r', encoding='utf-8') as txt_file:
                            text = txt_file.read()
                            documents.append(text)
                            labels.append(label)
                            found_count += 1
                    except:
                        pass 
                else:
                    missing_count += 1

    print(f"Ok: {found_count} fichier est trate")
    print(f"⚠️ {missing_count} manque fichier .")

    # calculer IDF
    N = len(documents)
    if N == 0:
        print("Errrurr verfier le fichier ")
        exit()

    print("Calculer point waittttt")
    
    tokenized_docs = [doc.lower().split() for doc in documents]

    word_doc_count = Counter()
    for doc in tokenized_docs:
        unique_words = set(doc)
        for word in unique_words:
            word_doc_count[word] += 1

    idf_map = {}
    for word, count in word_doc_count.items():
        idf_map[word] = math.log(N / count)

    # 3. Score map calculer
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

    print(f"🎉 Finir! '{output_file}' passe php ")

except Exception as e:
    print(f"Y a un erreur mais qui sais : {e}")