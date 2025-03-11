from fastapi import FastAPI
from chatbot import get_product_info

app = FastAPI()

@app.get("/ask/")
def ask_get(question: str):
    """API nhận câu hỏi qua GET"""
    answer = get_product_info(question)
    return {"question": question, "answer": str(answer)}

@app.post("/ask/")
def ask_post(data: dict):
    """API nhận câu hỏi qua POST (JSON)"""
    question = data.get("question", "")
    answer = get_product_info(question)
    return {"question": question, "answer": str(answer)}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000, reload=True)
