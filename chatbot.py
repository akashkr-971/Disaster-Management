from flask import Flask, request, Response, jsonify
from flask_cors import CORS
import subprocess
import json

app = Flask(__name__)
CORS(app)

def stream_ollama_response(prompt, model_name="mistral"):
    try:
        # Windows-specific command handling
        command = ["ollama.exe", "run", model_name, prompt]
        print(f"[INFO] Running: {' '.join(command)}")

        # Windows-specific process creation
        process = subprocess.Popen(
            command,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            bufsize=0,  # Unbuffered for Windows
            universal_newlines=True,
            shell=False,  # Don't use shell on Windows for security
            creationflags=subprocess.CREATE_NO_WINDOW if hasattr(subprocess, 'CREATE_NO_WINDOW') else 0
        )

        # Check if process started successfully
        if process.poll() is not None and process.returncode != 0:
            stderr_output = process.stderr.read() if process.stderr else "Unknown error"
            print(f"[ERROR] Process failed to start: {stderr_output}")
            yield f"⚠️ Error: Process failed to start - {stderr_output}\n"
            return

        # Read output line by line - Windows optimized
        if process.stdout is not None:
            try:
                output_buffer = ""
                while True:
                    # Read character by character for Windows
                    char = process.stdout.read(1)
                    if not char:
                        # Process has ended
                        if output_buffer.strip():
                            print(f"[Ollama Output] {output_buffer.strip()}")
                            yield output_buffer
                        break
                    
                    output_buffer += char
                    
                    # Yield when we have a complete line or significant buffer
                    if char == '\n' or len(output_buffer) > 50:
                        if output_buffer.strip():
                            print(f"[Ollama Output] {output_buffer.strip()}")
                            yield output_buffer
                        output_buffer = ""
                        
                # Wait for process to complete
                process.wait()
                
                # Check for errors after completion
                if process.returncode != 0:
                    stderr_output = process.stderr.read() if process.stderr else "Unknown error"
                    print(f"[ERROR] Ollama failed with return code {process.returncode}: {stderr_output}")
                    yield f"\n⚠️ Error: Ollama failed - {stderr_output}\n"
                else:
                    print("[INFO] Ollama completed successfully")
                    
            except Exception as read_error:
                print(f"[ERROR] Error reading output: {read_error}")
                yield f"\n⚠️ Error reading output: {str(read_error)}\n"
        else:
            print("[ERROR] process.stdout is None")
            yield "⚠️ Error: Could not access process output\n"

    except FileNotFoundError:
        error_msg = "Ollama command not found. Make sure Ollama is installed and in your PATH."
        print(f"[ERROR] {error_msg}")
        yield f"⚠️ {error_msg}\n"
    except Exception as e:
        print(f"[EXCEPTION] {e}")
        yield f"⚠️ Internal error: {str(e)}\n"
    finally:
        # Clean up process
        if 'process' in locals():
            try:
                if process.stdout:
                    process.stdout.close()
                if process.stderr:
                    process.stderr.close()
                if process.poll() is None:
                    process.terminate()
            except:
                pass

@app.route('/test-ollama', methods=['GET'])
def test_ollama():
    """Test endpoint to check if Ollama is working"""
    try:
        # Test if ollama command exists (Windows)
        result = subprocess.run(
            ["ollama.exe", "list"], 
            capture_output=True, 
            text=True, 
            timeout=10,
            shell=False,
            creationflags=subprocess.CREATE_NO_WINDOW if hasattr(subprocess, 'CREATE_NO_WINDOW') else 0
        )
        
        if result.returncode == 0:
            return jsonify({
                'status': 'success',
                'message': 'Ollama is working',
                'models': result.stdout
            })
        else:
            return jsonify({
                'status': 'error',
                'message': f'Ollama error: {result.stderr}'
            }), 500
            
    except FileNotFoundError:
        return jsonify({
            'status': 'error',
            'message': 'Ollama not found in PATH'
        }), 500
    except subprocess.TimeoutExpired:
        return jsonify({
            'status': 'error',
            'message': 'Ollama command timed out'
        }), 500
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': f'Error testing Ollama: {str(e)}'
        }), 500

@app.route('/chat', methods=['POST'])
def chat():
    try:
        # Handle both JSON and form data
        if request.is_json:
            data = request.get_json()
        else:
            # Handle form data or plain text
            data = {
                'message': request.form.get('message') or request.data.decode('utf-8')
            }
        
        if not data or 'message' not in data or not data['message']:
            return jsonify({'error': 'Missing message'}), 400

        user_message = data['message']
        model_name = data.get('model', 'mistral')  # Allow model selection
        
        print(f"[Request] User: {user_message}")
        print(f"[Request] Model: {model_name}")
        
        # Collect all response chunks and clean encoding
        full_response = ""
        for chunk in stream_ollama_response(user_message, model_name):
            if chunk.strip():
                full_response += chunk
        
        # Clean up encoding issues with emojis and special characters
        cleaned_response = full_response.strip()
        
        # Fix common emoji encoding issues
        emoji_fixes = {
            'ðŸ™': '💙',
            'ðŸª': '💪', 
            'ðŸ˜Š': '😊',
            'ðŸ˜¢': '😢',
            'âœ…': '✅',
            'â ï¸': '⚠️',
            'ðŸš¨': '🚨'
        }
        
        for broken, fixed in emoji_fixes.items():
            cleaned_response = cleaned_response.replace(broken, fixed)
        
        # Return JSON response that your HTML client expects
        return jsonify({
            'response': cleaned_response,
            'status': 'success'
        })

    except Exception as e:
        print(f"[EXCEPTION] Chat error: {e}")
        return jsonify({'error': f'Internal server error: {str(e)}'}), 500

@app.route('/chat-stream', methods=['POST'])
def chat_stream():
    """Streaming endpoint for real-time responses"""
    try:
        # Handle both JSON and form data
        if request.is_json:
            data = request.get_json()
        else:
            # Handle form data or plain text
            data = {
                'message': request.form.get('message') or request.data.decode('utf-8')
            }
        
        if not data or 'message' not in data or not data['message']:
            return jsonify({'error': 'Missing message'}), 400

        user_message = data['message']
        model_name = data.get('model', 'mistral')  # Allow model selection
        
        print(f"[Request] User: {user_message}")
        print(f"[Request] Model: {model_name}")
        
        def generate():
            for chunk in stream_ollama_response(user_message, model_name):
                if chunk.strip():
                    yield f"data: {chunk}\n\n"
            yield "data: [DONE]\n\n"
                
        return Response(
            generate(), 
            mimetype='text/plain',
            headers={
                'Cache-Control': 'no-cache',
                'Connection': 'keep-alive',
                'Access-Control-Allow-Origin': '*'
            }
        )

    except Exception as e:
        print(f"[EXCEPTION] Chat error: {e}")
        return jsonify({'error': f'Internal server error: {str(e)}'}), 500

@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({'status': 'healthy', 'service': 'Flask Ollama Chatbot'})

if __name__ == '__main__':
    print("[INFO] Starting Flask Ollama Chatbot...")
    print("[INFO] Test Ollama with: GET /test-ollama")
    print("[INFO] Health check with: GET /health")
    app.run(host='127.0.0.1', port=5000, debug=True)