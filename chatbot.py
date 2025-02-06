from flask import Flask, request, jsonify
from flask_cors import CORS
import subprocess

app = Flask(__name__)
CORS(app)

def get_ollama_response(prompt, model_name="ResQAI"):
    try:
        command = ["ollama", "run", model_name, prompt]
        result = subprocess.run(
            command,
            capture_output=True,
            text=True,
            encoding='utf-8',
            errors='replace'
        )
        
        if result.returncode == 0:
            # Clean and format the response
            response = result.stdout.strip()
            
            # Ensure proper line breaks for lists
            response = response.replace('\\n', '\n')  # Replace literal \n with actual newlines
            
            # Ensure numbered lists and bullet points are properly formatted
            lines = response.split('\n')
            formatted_lines = []
            for line in lines:
                # Add extra space before numbered points or bullet points
                if line.strip().startswith(('1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.', '0.', '-', '•')):
                    formatted_lines.append('\n' + line)
                else:
                    formatted_lines.append(line)
            
            return '\n'.join(formatted_lines)
        else:
            print(f"Error: {result.stderr}")
            return "Sorry, I encountered an error processing your request."
    except Exception as e:
        print(f"Error with Ollama: {str(e)}")
        return "Sorry, I encountered an error processing your request."

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.get_json()
        if not data or 'message' not in data:
            return jsonify({'error': 'No message received'}), 400
        
        user_message = data['message']
        print(f"Received message: {user_message}")
        
        response = get_ollama_response(user_message)
        print(f"Response: {response}")
        
        return jsonify({'response': response})
    
    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({'error': 'Internal server error'}), 500

if __name__ == '__main__':
    app.run(port=5000, debug=True)