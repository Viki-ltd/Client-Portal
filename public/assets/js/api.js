/**
 * Handles API calls for the AI Narrative Architect text assistant.
 * @param {string} prompt - The user's instruction (e.g., "Make it more professional").
 * @param {string} text - The current text from the textarea.
 * @returns {Promise<string>} - The refined text from the AI.
 */
async function getAIRefinement(prompt, text) {
    // This is the message structure your Python/Groq backend expects.
    const conversation = [
        { role: 'system', content: 'You are an expert copy editor. Rewrite the user\'s initial text based on their instruction.' },
        { role: 'user', content: `The initial text is: "${text}"` },
        { role: 'user', content: `My instruction is: "${prompt}"` }
    ];

    try {
        // --- THIS IS THE REAL API CALL ---
        // It sends the request to your local Python server.
        const response = await fetch('http://127.0.0.1:5000/api/refine', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ messages: conversation })
        });
        
        if (!response.ok) {
            // This will catch server errors (like 500)
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data.response; // Return the actual AI response from your backend

    } catch (error) {
        console.error("AI Refinement Error:", error);
        // This will show an error if the server is not running or there's a network issue.
        throw new Error("Failed to get a response from the AI assistant. Is the Python server running?");
    }
}


document.addEventListener('DOMContentLoaded', () => {
    // --- AI TEXT ASSISTANT ---
    // Note: The logic for this assistant is now handled in main.js,
    // which calls the getAIRefinement() function above.
    // The code below is for your OTHER AI assistants.

    document.querySelectorAll('.ai-assistant-container').forEach(container => {
        const chatHistory = container.querySelector('.assistant-chat-history');
        const input = container.querySelector('.assistant-input');
        const sendBtn = container.querySelector('.assistant-send-btn');
        const quickActionBtns = container.querySelectorAll('.quick-action-btn');
        const finalOutputTextarea = container.querySelector('.assistant-final-text');
        const copyBtn = container.querySelector('.btn-copy-text');

        let conversationHistory = [];

        const sendMessage = async (messageText) => {
            if (!messageText.trim() && conversationHistory.length === 0) {
                return;
            }
            if (!finalOutputTextarea.value.trim()){
                alert('Please provide some initial text in the main description box first.');
                return;
            }

            appendMessage('user', messageText);
            input.value = '';

            if (conversationHistory.length === 0) {
                const initialText = finalOutputTextarea.value;
                conversationHistory.push({ role: 'system', content: 'You are an expert copy editor. The user will provide initial text and then refinement instructions. Your goal is to rewrite the initial text based on the instructions.' });
                conversationHistory.push({ role: 'user', content: `The initial text is: "${initialText}"` });
            }
            conversationHistory.push({ role: 'user', content: messageText });

            try {
                const response = await fetch('http://127.0.0.1:5000/api/refine', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ messages: conversationHistory })
                });
                const data = await response.json();

                appendMessage('bot', data.response);
                finalOutputTextarea.value = data.response;
                conversationHistory.push({ role: 'assistant', content: data.response });

            } catch (error) {
                console.error("AI Assistant Error:", error);
                appendMessage('bot', 'Sorry, an error occurred.');
            }
        };

        const appendMessage = (sender, text) => {
            const message = document.createElement('div');
            message.className = `assistant-chat-message ${sender}`;
            message.innerHTML = `<div class="bubble">${text}</div>`;
            chatHistory.appendChild(message);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        };

        sendBtn.addEventListener('click', () => sendMessage(input.value));
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') sendMessage(input.value);
        });
        quickActionBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                sendMessage(btn.dataset.instruction);
            });
        });
        copyBtn.addEventListener('click', () => {
            finalOutputTextarea.select();
            document.execCommand('copy');
        });
    });

    // --- AI CHAT SUPPORT CONCIERGE ---
    const chatHistory = document.getElementById('chat-history');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const interactionArea = document.getElementById('chat-interaction-area');

    if (chatHistory && chatInput && sendButton && interactionArea) {
        
        const showSuggestions = (questions) => {
            interactionArea.innerHTML = ''; 
            const title = document.createElement('h6');
            title.className = 'suggestions-title';
            title.textContent = 'Here are some suggested starting points, or feel free to type your own question.';
            interactionArea.appendChild(title);
            const suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'chat-suggestions';
            questions.forEach(q => {
                const button = document.createElement('button');
                button.className = 'suggestion-button';
                button.textContent = q;
                button.onclick = () => {
                    chatInput.value = q;
                    handleSendMessage();
                };
                suggestionsContainer.appendChild(button);
            });
            interactionArea.appendChild(suggestionsContainer);
        };

        const showTypingIndicator = () => {
            interactionArea.innerHTML = `<div class="chat-message bot"><div class="message-bubble typing-indicator"><span></span><span></span><span></span></div></div>`;
        };
        
        const loadSuggestions = async () => {
            try {
                const response = await fetch('http://127.0.0.1:5000/api/suggestions');
                if (!response.ok) throw new Error('Failed to fetch suggestions');
                const data = await response.json();
                showSuggestions(data.suggestions);
            } catch (error) {
                console.error("Could not load suggestions:", error);
                interactionArea.style.display = 'none';
            }
        };

        const handleSendMessage = async () => {
            const userMessage = chatInput.value.trim();
            if (userMessage === '') return;
            appendMessage('user', userMessage);
            chatInput.value = '';
            showTypingIndicator();
            try {
                const response = await fetch('http://127.0.0.1:5000/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: userMessage })
                });
                if (!response.ok) throw new Error('Network error');
                const data = await response.json();
                interactionArea.innerHTML = '';
                appendMessage('bot', data.response);
                loadSuggestions();
            } catch (error) {
                console.error('Chat Error:', error);
                interactionArea.innerHTML = '';
                appendMessage('bot', 'Sorry, something went wrong. Please try again.');
            }
        };
        
        sendButton.addEventListener('click', handleSendMessage);
        chatInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                handleSendMessage();
            }
        });

        loadSuggestions();
    }
    
    function appendMessage(sender, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}`;
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.textContent = text;
        messageDiv.appendChild(bubbleDiv);
        chatHistory.appendChild(messageDiv);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }
});