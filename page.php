<!DOCTYPE html>
<html>
<head>
    <style>
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            z-index: 1000;
        }

        .chat-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            float: right;
        }

        .chat-box {
            display: none;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 10px 10px;
            height: 500px;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chat-messages {
            height: 380px;
            overflow-y: auto;
            padding: 15px;
        }

        .chat-input-container {
            padding: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 5px;
        }

        .chat-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .chat-send {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin: 8px 0;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
            margin-right: 5px;
        }

        .bot-message {
            background: #f0f0f0;
            margin-right: auto;
            margin-left: 5px;
            white-space: pre-wrap;
            font-family: monospace;
        }

        .bot-message ul, .bot-message ol {
            margin: 5px 0;
            padding-left: 20px;
        }

        .bot-message li {
            margin: 2px 0;
        }

        .chat-header {
            padding: 15px;
            background: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #0056b3;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">ResQAI Assistant</div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">Hello! How can I help you today?</div>
            </div>
            <div class="chat-input-container">
                <input type="text" class="chat-input" id="userInput" placeholder="Type your message...">
                <button class="chat-send" onclick="sendMessage()">Send</button>
            </div>
        </div>
        <button class="chat-button" id="toggleChat">Chat with ResQAI</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById("chatBox");
            const chatContainer = document.querySelector(".chat-container");
            const userInput = document.getElementById("userInput");
            
            document.getElementById("toggleChat").addEventListener("click", function(e) {
                e.stopPropagation();
                chatBox.style.display = chatBox.style.display === "none" ? "block" : "none";
                if (chatBox.style.display === "block") {
                    userInput.focus();
                }
            });
            
            document.addEventListener('click', function(e) {
                if (chatBox.style.display === "block" && 
                    !chatContainer.contains(e.target)) {
                    chatBox.style.display = "none";
                }
            });
            
            chatBox.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        function sendMessage() {
            let userInput = document.getElementById("userInput");
            let userMessage = userInput.value.trim();
            
            if (userMessage === "") return;
            
            const chatMessages = document.getElementById("chatMessages");
            
            chatMessages.innerHTML += `<div class="message user-message">${userMessage}</div>`;
            
            const typingIndicator = `<div class="message bot-message" id="typing">Typing...</div>`;
            chatMessages.innerHTML += typingIndicator;
            
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            userInput.value = "";
            userInput.focus();

            fetch("http://localhost:5000/chat", {
                method: "POST",
                body: JSON.stringify({ message: userMessage }),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Access-Control-Allow-Origin": "*"
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                document.getElementById("typing").remove();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const formattedResponse = data.response
                    .replace(/\n/g, '<br>')
                    .replace(/ {2,}/g, function(match) {
                        return '&nbsp;'.repeat(match.length);
                    });
                
                chatMessages.innerHTML += `<div class="message bot-message">${formattedResponse}</div>`;
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById("typing")?.remove();
                
                chatMessages.innerHTML += `<div class="message bot-message">Sorry, there was an error connecting to the AI service. Please try again later.</div>`;
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        }

        document.getElementById("userInput").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
    </script>
</body>
</html>
