import faiss
import numpy as np
from llama_index.core import VectorStoreIndex
from llama_index.embeddings.huggingface import HuggingFaceEmbedding
from llama_index.core.storage.storage_context import StorageContext
from llama_index.vector_stores.faiss import FaissVectorStore
from database import query_products
from llama_index.core.schema import Document


# 1. Khởi tạo FAISS Index
dim = 384  # Số chiều của vector (phụ thuộc vào model embedding)
index = faiss.IndexFlatL2(dim)  # FAISS Index kiểu L2 (khoảng cách Euclidean)

# 2. Dùng Llama Index FAISS wrapper
vector_store = FaissVectorStore(index)
storage_context = StorageContext.from_defaults(vector_store=vector_store)

# 3. Chọn mô hình embedding
embed_model = HuggingFaceEmbedding(model_name="sentence-transformers/all-MiniLM-L6-v2")

# 4. Thiết lập mô hình embedding trong LlamaIndex
from llama_index.core import Settings
Settings.embed_model = embed_model

# Vô hiệu hóa OpenAI LLM (Không có KEY của OpenAI nên phải vô hiệu hóa :)) )
Settings.llm = None

# 5. Nạp dữ liệu sản phẩm từ MySQL
def load_products_to_faiss():
    query = "SELECT id, product_name, description FROM products LIMIT 100;"
    products = query_products(query)

    # Dữ liệu trả về là 1 object nên phải chuyển nó sang documents
    # Tạo danh sách documents từ dữ liệu sản phẩm
    documents = [
        Document(text=f'{p["product_name"]} - {p["description"]}', metadata={"id": str(p["id"])})
        for p in products
    ]

    # Tạo index từ documents
    index = VectorStoreIndex.from_documents(
        documents,
        storage_context=storage_context,
        embed_model=Settings.embed_model
    )
    return index

# 6. Lưu FAISS Index ra file
def save_faiss_index():
    faiss.write_index(index, "faiss_index.bin")

# 7. Load lại FAISS Index từ file
def load_faiss_index():
    return faiss.read_index("faiss_index.bin")
