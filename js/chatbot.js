document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chat-button');
    const chatContainer = document.getElementById('chat-container');
    const closeChatButton = document.getElementById('close-chat');
    const expandChatBtn = document.getElementById('expand-chat');
    const sendButton = document.getElementById('send-button');
    const userInput = document.getElementById('user-input');
    const messagesContainer = document.getElementById('messages-container');
    const chatOptionButtons = document.querySelectorAll('.chat-option-button');
    const chatNotification = document.querySelector('.chat-notification');

    // Base de conocimiento para respuestas rápidas
    const quickResponses = {
        'Beneficios': "💪 <strong>Beneficios de HealthBot:</strong><br>" +
            "✅ Planes de ejercicio personalizados<br>" +
            "✅ Dietas balanceadas según tus objetivos<br>" +
            "✅ Seguimiento de progreso constante<br>" +
            "✅ Soporte emocional y motivacional<br>" +
            "✅ Consejos de nutrición basados en investigación",

        'Mejora tu salud': "🏃‍♂️ <strong>Para mejorar tu salud:</strong><br>" +
            "Puedo generarte planes nutricionales y de ejercicio personalizados a tu persona, animate a inciar sesión para vivir esta experiencia, animate a iniciar sesión para poder emepzar tu rutina",

        'Consejos': "🔒 <strong>Consejos Personalizados Disponibles</strong><br><br>" +

            "Una vez que te registres en HealthBot, podré ofrecerte:<br><br>" +

            "✅ <strong>Planes de ejercicio</strong> adaptados a tu cuerpo<br>" +
            "✅ <strong>Dietas personalizadas</strong> según tus metas<br>" +
            "✅ <strong>Seguimiento de progreso</strong> semana a semana<br>" +
            "✅ <strong>Rutinas específicas</strong> para tu nivel<br>" +
            "✅ <strong>Consejos de nutrición</strong> detallados<br>" +
            "✅ <strong>Ajustes automáticos</strong> según tus resultados<br><br>" +

            "💪 <strong>¡Regístrate para comenzar tu journey personalizado!</strong>"
    };

    // Función para mostrar/ocultar el chat
    chatButton.addEventListener('click', () => {
        const isVisible = chatContainer.style.display === 'flex';
        chatContainer.style.display = isVisible ? 'none' : 'flex';
        
        // Ocultar notificación cuando se abre el chat
        if (!isVisible) {
            chatNotification.style.display = 'none';
        }
        
        // Enfocar el input cuando se abre el chat
        if (chatContainer.style.display === 'flex') {
            setTimeout(() => userInput.focus(), 100);
        }
    });

    closeChatButton.addEventListener('click', () => {
        chatContainer.style.display = 'none';
    });

    // Alternar modo pantalla completa
    expandChatBtn.addEventListener('click', () => {
        chatContainer.classList.toggle('expanded');
    });

    // Enviar mensaje con Enter
    userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Enviar mensaje con botón
    sendButton.addEventListener('click', sendMessage);

    // Función para manejar botones de opción rápida
    chatOptionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const question = this.textContent.replace(/[📝🚀💬]/g, '').trim();
            handleQuickOption(question);
        });
    });

    function handleQuickOption(question) {
        // Agregar mensaje del usuario (simulado)
        addMessage(question, "user");

        // Ocultar opciones rápidas después de usar una
        document.querySelector('.chat-options').style.display = 'none';

        // Simular tiempo de respuesta del bot
        setTimeout(() => {
            const botResponse = quickResponses[question] ||
                "No tengo una respuesta específica para eso. ¿Puedes reformular tu pregunta?";
            addMessage(botResponse, "bot");
        }, 1000);
    }

    function sendMessage() {
        const message = userInput.value.trim();
        if (!message) return;

        addMessage(message, "user");
        userInput.value = "";

        // Ocultar opciones rápidas si es la primera interacción del usuario
        if (document.querySelector('.chat-options').style.display !== 'none') {
            document.querySelector('.chat-options').style.display = 'none';
        }

        // Llamada al backend con cookies incluidas
        fetch("model/chatbot_api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include",
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

        if (sender === "bot" && text.includes("<br>")) {
            msg.innerHTML = text;
        } else {
            msg.textContent = text;
        }

        messagesContainer.appendChild(msg);

        // Scroll automático al final
        scrollToBottom();
    }

    function scrollToBottom() {
        // Usar requestAnimationFrame para un scroll más suave
        requestAnimationFrame(() => {
            messagesContainer.scrollTo({
                top: messagesContainer.scrollHeight,
                behavior: 'smooth'
            });
        });
    }

    // Asegurar que el chat esté oculto al cargar la página
    chatContainer.style.display = 'none';

    // Mostrar notificación después de unos segundos
    setTimeout(() => {
        chatNotification.style.display = 'flex';
    }, 3000);

    // Scroll inicial al fondo (por si hay mensajes de bienvenida)
    setTimeout(scrollToBottom, 100);
});