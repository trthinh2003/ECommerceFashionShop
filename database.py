import mysql.connector

def connect_db():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="fashionshop_ec"
    )

def query_products(query):
    print("Running SQL:", query)
    db = connect_db()
    cursor = db.cursor(dictionary=True)

    cursor.execute(query)
    results = cursor.fetchall()

    cursor.close()
    db.close()
    return results
