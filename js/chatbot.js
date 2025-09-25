document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chat-button');
    const chatContainer = document.getElementById('chat-container');
    const closeChatButton = document.getElementById('close-chat');
    
    // Función para mostrar/ocultar el chat
    chatButton.addEventListener('click', () => {
        chatContainer.style.display = (chatContainer.style.display === 'flex') ? 'none' : 'flex';
    });
    
    closeChatButton.addEventListener('click', () => {
        chatContainer.style.display = 'none';
    });
});
document.addEventListener("DOMContentLoaded", function () {
  const chatButton = document.getElementById("chat-button");
  const chatContainer = document.getElementById("chat-container");
  const closeChat = document.getElementById("close-chat");
  const sendButton = document.getElementById("send-button");
  const userInput = document.getElementById("user-input");
  const messagesContainer = document.getElementById("messages-container");

  
  chatButton.addEventListener("click", () => chatContainer.classList.add("active"));
  closeChat.addEventListener("click", () => chatContainer.classList.remove("active"));

  // Enviar mensaje
  sendButton.addEventListener("click", sendMessage);
  userInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
  });

  function sendMessage() {
    const message = userInput.value.trim();
    if (!message) return;

   
    addMessage(message, "user");
    userInput.value = "";

    // Llamar al backend
    fetch("model/chatbot_api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message }),
    })
      .then((res) => res.json())
      .then((data) => {
        addMessage(data.response, "bot");
      })
      .catch((err) => {
        addMessage("Error al conectar con el servidor.", "bot");
        console.error(err);
      });
  }

  function addMessage(text, sender) {
    const msg = document.createElement("div");
    msg.classList.add("message", sender);
    msg.innerText = text;
    messagesContainer.appendChild(msg);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
  }
});
