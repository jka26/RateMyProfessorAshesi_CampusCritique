from flask import Flask, request, jsonify
from profanity_check import predict

app = Flask(__name__)

@app.route("/moderate", methods=["POST"])
def moderate():
    data = request.get_json()
    text = data.get("text", "")
    if not text:
        return jsonify({"error": "No text provided"}), 400

    is_inappropriate = predict([text])[0] == 1
    return jsonify({"flagged": is_inappropriate})

if __name__ == "__main__":
    app.run(port=5001)
