import zipfile
import xml.etree.ElementTree as ET

def extract_text_from_docx(docx_path):
    with zipfile.ZipFile(docx_path) as docx:
        xml_content = docx.read('word/document.xml')
        tree = ET.fromstring(xml_content)
        
        namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
        
        paragraphs = []
        for p in tree.findall('.//w:p', namespaces):
            texts = [node.text for node in p.findall('.//w:t', namespaces) if node.text]
            if texts:
                paragraphs.append(''.join(texts))
                
        return '\n'.join(paragraphs)

if __name__ == '__main__':
    text = extract_text_from_docx('d:/TugasAkhir1/Project_Skripsi/Skripsi_TA1_Gibran.docx')
    with open('d:/TugasAkhir1/Project_Skripsi/extracted_text.txt', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Extraction complete. Text saved to extracted_text.txt")
