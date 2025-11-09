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
    const expandChatBtn = document.getElementById("expand-chat");
    const sendButton = document.getElementById("send-button");
    const userInput = document.getElementById("user-input");
    const messagesContainer = document.getElementById("messages-container");

    // Mostrar/ocultar chat
    chatButton.addEventListener("click", () => chatContainer.classList.toggle("active"));
    closeChat.addEventListener("click", () => chatContainer.classList.remove("active"));

    // Alternar modo pantalla completa
    expandChatBtn.addEventListener("click", () => chatContainer.classList.toggle("expanded"));

    // Enviar mensaje con Enter
    userInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") sendMessage();
    });
    // Enviar mensaje con botón
    sendButton.addEventListener("click", sendMessage);

    function sendMessage() {
        const message = userInput.value.trim();
        if (!message) return;

        addMessage(message, "user");
        userInput.value = "";

        // Llamada al backend con cookies incluidas
        fetch("model/chatbot_api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include", // <- envío de cookies para sesión
            body: JSON.stringify({ message })
        })
        .then(res => res.json())
        .then(data => {
            addMessage(data.response, "bot");
        })
        .catch(err => {
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
