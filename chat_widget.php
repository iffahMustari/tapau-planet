<!-- ✅ chat_widget.php (Updated with new design + color palette) -->
<style>
/* Chatbot Styles (Updated) */
#chatbot-bubble {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 360px;
  max-height: 500px;
  background: #FAF8F5;
  border: 1px solid #C7D0D5;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
  overflow: hidden;
  display: none;
  flex-direction: column;
  font-family: 'Segoe UI', sans-serif;
  z-index: 9999;
}

#chat-header {
  background: #5B5D6D;
  color: #FAF8F5;
  padding: 14px;
  text-align: center;
  font-weight: bold;
  font-size: 1rem;
}

#chat-body {
  flex: 1;
  padding: 12px;
  overflow-y: auto;
  background: #F3ECE5;
  scroll-behavior: smooth;
}

#chat-input {
  display: flex;
  border-top: 1px solid #EFE9C7;
  background: #EBDDD6;
}

#chat-input input {
  flex: 1;
  padding: 12px;
  border: none;
  background: #EBDDD6;
  color: #333;
  border-radius: 0 0 0 12px;
  font-size: 1rem;
}

#chat-input button {
  padding: 0 20px;
  background: #5B5D6D;
  color: #FAF8F5;
  border: none;
  border-radius: 0 0 12px 0;
  font-size: 1.2rem;
  cursor: pointer;
}

.chat-msg {
  margin: 10px 0;
  padding: 8px 12px;
  max-width: 80%;
  border-radius: 14px;
  font-size: 0.95rem;
  line-height: 1.4;
}

.user-msg {
  background: #EFE9C7;
  color: #333;
  margin-left: auto;
  text-align: right;
}

.bot-msg {
  background: #C7D0D5;
  color: #333;
  margin-right: auto;
  text-align: left;
}

#openChatBtn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #5B5D6D;
  color: white;
  border: none;
  border-radius: 50%;
  width: 56px;
  height: 56px;
  font-size: 24px;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  transition: background 0.3s;
}

#openChatBtn:hover {
  background: #B7C2AF;
  color: #333;
}
</style>

<!-- Chatbot HTML -->
<div id="chatbot-bubble">
  <div id="chat-header">AI Assistant</div>
  <div id="chat-body"></div>
  <div id="chat-input">
    <input type="text" id="userInput" placeholder="Type a message...">
    <button onclick="sendMessage()">➤</button>
  </div>
</div>

<!-- Open Chat Button -->
<button id="openChatBtn" onclick="toggleChat()">💬</button>

<!-- Chatbot Script -->
<script>
function toggleChat() {
  const chat = document.getElementById("chatbot-bubble");
  chat.style.display = chat.style.display === "none" ? "flex" : "none";
}

function sendMessage() {
  const input = document.getElementById("userInput");
  const msg = input.value.trim();
  if (!msg) return;

  appendMessage(msg, "user");

  // Bot response
  let response = "Sorry, I don't understand.";
  const lowerMsg = msg.toLowerCase();

  if (lowerMsg.includes("hi") || lowerMsg.includes("hello")) {
    response = "Hello! How can I help you today?";
  } else if (lowerMsg.includes("order")) {
    response = "To place an order, go to the <a href='menu.php'>menu page</a>.";
  } else if (lowerMsg.includes("menu")) {
    response = "Here's our menu: <a href='menu.php'>View Menu</a>.";
  } else if (lowerMsg.includes("track") || lowerMsg.includes("status")) {
    response = "You can track your order under 'My Orders'.";
  } else if (lowerMsg.includes("bye")) {
    response = "Goodbye! Have a nice day!";
  }

  setTimeout(() => {
    appendMessage(response, "bot");
    input.value = "";
  }, 400);
}

function appendMessage(message, type) {
  const chatBody = document.getElementById("chat-body");
  const div = document.createElement("div");
  div.className = `chat-msg ${type}-msg`;
  div.innerHTML = message;
  chatBody.appendChild(div);
  chatBody.scrollTop = chatBody.scrollHeight;
}
</script>
