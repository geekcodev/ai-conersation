<template>
    <div class="chat-widget" :class="{ 'inline-mode': inline }">
        <!-- Messages Container -->
        <div ref="messagesContainer" class="chat-messages">
            <div v-if="messages.length === 0" class="chat-empty">
                <div class="chat-empty-icon">💬</div>
                <p class="chat-empty-title">Начните диалог</p>
                <p class="chat-empty-text">Задайте вопрос нашему AI-ассистенту</p>
            </div>

            <div
                v-for="message in messages"
                :key="message.id"
                :class="['chat-message', message.role]"
            >
                <div class="chat-message-avatar">
                    <svg v-if="message.role === 'user'" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <div class="chat-message-content">
                    <div class="chat-message-text">{{ message.content }}</div>
                    <div class="chat-message-time">{{ formatTime(message.timestamp) }}</div>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div v-if="isTyping" class="chat-message assistant">
                <div class="chat-message-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <div class="chat-message-content">
                    <div class="chat-typing">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Container -->
        <div class="chat-input-wrapper">
            <div class="chat-input-container">
        <textarea
            v-model="inputMessage"
            @keydown.enter.exact.prevent="sendMessage"
            @input="adjustTextareaHeight"
            ref="textarea"
            class="chat-input"
            placeholder="Введите сообщение..."
            rows="1"
            :disabled="isSending"
        />
                <button
                    @click="sendMessage"
                    class="chat-send-btn"
                    :disabled="isSending || !inputMessage.trim()"
                >
                    <svg v-if="!isSending" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    <svg v-else class="chat-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, nextTick, watch} from 'vue';
import axios from 'axios';

const props = defineProps({
    apiUrl: {
        type: String,
        default: '/api/chat'
    },
    inline: {
        type: Boolean,
        default: false
    }
});

const messages = ref([]);
const inputMessage = ref('');
const isSending = ref(false);
const isTyping = ref(false);
const sessionId = ref('');
const messagesContainer = ref(null);
const textarea = ref(null);

onMounted(async () => {
    sessionId.value = getOrCreateSessionId();
    await loadHistory();
});

const getOrCreateSessionId = () => {
    let id = localStorage.getItem('ai_chat_session');
    if (!id) {
        id = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('ai_chat_session', id);
    }
    return id;
};

const loadHistory = async () => {
    try {
        const response = await axios.get(`${props.apiUrl}/history`, {
            params: {session_id: sessionId.value}
        });

        if (response.data.success) {
            messages.value = response.data.data.messages;
            await nextTick();
            scrollToBottom();
        }
    } catch (error) {
        console.error('Failed to load history:', error);
    }
};

const sendMessage = async () => {
    const message = inputMessage.value.trim();
    if (!message || isSending.value) return;

    inputMessage.value = '';
    isSending.value = true;
    textarea.value.style.height = 'auto';

    messages.value.push({
        id: Date.now(),
        role: 'user',
        content: message,
        timestamp: new Date().toISOString()
    });

    await nextTick();
    scrollToBottom();
    isTyping.value = true;

    try {
        const response = await axios.post(`${props.apiUrl}/send`, {
            session_id: sessionId.value,
            message: message
        });

        isTyping.value = false;

        if (response.data.success) {
            messages.value.push({
                id: response.data.data.message_id,
                role: 'assistant',
                content: response.data.data.message,
                timestamp: response.data.data.timestamp
            });
        } else {
            messages.value.push({
                id: Date.now(),
                role: 'assistant',
                content: 'Извините, произошла ошибка. Попробуйте еще раз.',
                timestamp: new Date().toISOString()
            });
        }
    } catch (error) {
        isTyping.value = false;
        messages.value.push({
            id: Date.now(),
            role: 'assistant',
            content: 'Извините, не удалось подключиться. Попробуйте позже.',
            timestamp: new Date().toISOString()
        });
        console.error('Failed to send message:', error);
    }

    isSending.value = false;
    await nextTick();
    scrollToBottom();
};

const adjustTextareaHeight = () => {
    const el = textarea.value;
    if (el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }
};

const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('ru-RU', {hour: '2-digit', minute: '2-digit'});
};
</script>

<style scoped>
.chat-widget {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #ffffff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.chat-widget.inline-mode {
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: #fafafa;
}

.chat-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    padding: 40px 20px;
}

.chat-empty-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.chat-empty-title {
    font-size: 20px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
}

.chat-empty-text {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.chat-message {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    max-width: 85%;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-message.user {
    margin-left: auto;
    flex-direction: row-reverse;
}

.chat-message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #f0f0f0;
}

.chat-message.user .chat-message-avatar {
    background: #10b981;
}

.chat-message-avatar svg {
    width: 20px;
    height: 20px;
    color: #666;
}

.chat-message.user .chat-message-avatar svg {
    color: #ffffff;
}

.chat-message-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.chat-message.user .chat-message-content {
    align-items: flex-end;
}

.chat-message-text {
    background: #ffffff;
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 15px;
    line-height: 1.5;
    color: #1a1a1a;
    word-wrap: break-word;
    white-space: pre-wrap;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.chat-message.user .chat-message-text {
    background: #10b981;
    color: #ffffff;
}

.chat-message-time {
    font-size: 11px;
    color: #999;
    padding: 0 4px;
}

.chat-typing {
    display: flex;
    gap: 6px;
    padding: 16px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.chat-typing span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    animation: typing 1.4s infinite;
}

.chat-typing span:nth-child(2) {
    animation-delay: 0.2s;
}

.chat-typing span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        opacity: 0.3;
        transform: scale(0.8);
    }
    30% {
        opacity: 1;
        transform: scale(1);
    }
}

.chat-input-wrapper {
    background: #ffffff;
    border-top: 1px solid #e5e5e5;
    padding: 16px;
}

.chat-input-container {
    display: flex;
    gap: 12px;
    align-items: flex-end;
    background: #fafafa;
    border: 2px solid #e5e5e5;
    border-radius: 24px;
    padding: 8px 8px 8px 16px;
    transition: border-color 0.2s;
}

.chat-input-container:focus-within {
    border-color: #10b981;
}

.chat-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 8px 0;
    font-size: 15px;
    line-height: 1.5;
    outline: none;
    resize: none;
    min-height: 24px;
    max-height: 120px;
    font-family: inherit;
    color: #1a1a1a;
}

.chat-input::placeholder {
    color: #999;
}

.chat-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.chat-send-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #10b981;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s;
}

.chat-send-btn:hover:not(:disabled) {
    background: #059669;
    transform: scale(1.05);
}

.chat-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: scale(1);
}

.chat-send-btn svg {
    width: 20px;
    height: 20px;
}

.chat-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Scrollbar */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

@media (max-width: 768px) {
    .chat-messages {
        padding: 16px;
        gap: 12px;
    }

    .chat-message {
        max-width: 90%;
    }

    .chat-message-avatar {
        width: 32px;
        height: 32px;
    }

    .chat-input-wrapper {
        padding: 12px;
    }
}
</style>
