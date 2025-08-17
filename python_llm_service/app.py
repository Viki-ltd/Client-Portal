import os
import datetime
from flask import Flask, request, jsonify
from flask_cors import CORS
from groq import Groq
from dotenv import load_dotenv

load_dotenv()

# --- Helper Functions ---

def search_faq(question):
    try:
        with open('faq.txt', 'r', encoding='utf-8') as f:
            for line in f:
                if ':::' in line:
                    q, a = line.strip().split(':::', 1)
                    if question.strip().lower() == q.strip().lower():
                        print(f"Found answer in FAQ for: '{question}'")
                        return a.strip()
    except FileNotFoundError:
        print("faq.txt not found, skipping FAQ search.")
    return None

def log_unanswered_question(question):
    timestamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    with open('unanswered_questions.log', 'a', encoding='utf-8') as f:
        f.write(f"[{timestamp}] - {question}\n")

# --- Flask App Initialization ---

app = Flask(__name__)
CORS(app)

# --- Groq Client Initialization ---
try:
    groq_api_key = os.environ.get("GROQ_API_KEY")
    if not groq_api_key:
        raise ValueError("GROQ_API_KEY not found. Make sure it's set in your .env file.")
    client = Groq(api_key=groq_api_key)
except Exception as e:
    print(f"Error initializing Groq client: {e}")
    client = None

# --- API Routes ---

@app.route('/api/refine', methods=['POST'])
def refine_text():
    """
    Handles requests from the AI Narrative Architect to refine text using Groq.
    """
    if not client:
        return jsonify({"error": "Groq client not initialized."}), 500

    print("Received request for /api/refine")
    try:
        data = request.get_json()
        messages = data.get('messages')

        if not messages:
            return jsonify({"error": "Invalid request body. 'messages' key is missing."}), 400

        # --- THIS IS THE CORRECTED PART ---
        # Make the actual API call to Groq
        chat_completion = client.chat.completions.create(
            messages=messages,
            model="llama3-8b-8192",
        )
        ai_response = chat_completion.choices[0].message.content

        return jsonify({"response": ai_response})

    except Exception as e:
        print(f"Error in /api/refine: {e}")
        return jsonify({"error": "An internal server error occurred."}), 500


@app.route('/api/chat', methods=['POST'])
def handle_chat():
    """
    Handles chat requests with the smarter logic.
    """
    if not client: return jsonify({"error": "Groq client not initialized."}), 500
    
    data = request.get_json()
    user_message = data.get('message')
    if not user_message: return jsonify({"error": "No message provided."}), 400
    
    faq_answer = search_faq(user_message)
    if faq_answer:
        return jsonify({"response": faq_answer})
    
    log_unanswered_question(user_message)
    messages = [
        {"role": "system", "content": "You are a helpful support assistant."},
        {"role": "user", "content": user_message}
    ]
    chat_completion = client.chat.completions.create(messages=messages, model="llama3-8b-8192")
    return jsonify({"response": chat_completion.choices[0].message.content})


@app.route('/api/suggestions', methods=['GET'])
def get_suggestions():
    suggestions = [
        "How do I upgrade my service plan?",
        "What file types are supported for bulk upload?"
    ]
    return jsonify({"suggestions": suggestions})

# --- Main execution block ---
if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)