from llama_index.core.query_engine import RetrieverQueryEngine
from faiss_index import load_products_to_faiss

# Load FAISS Index từ dữ liệu sản phẩm
index = load_products_to_faiss()

# Tạo Query Engine để truy vấn
query_engine = RetrieverQueryEngine.from_args(index.as_retriever())

def get_product_info(question):
    """Trả về kết quả tìm kiếm sản phẩm từ câu hỏi người dùng."""
    response = query_engine.query(question)
    return response
