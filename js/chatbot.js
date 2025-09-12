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