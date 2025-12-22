<template>
    <div class="popup-chat-wrapper">
        <!-- Chat Button -->
        <button @click="toggleChat" class="popup-chat-button" :class="{ active: isOpen }">
            <svg v-if="!isOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Chat Window -->
        <Transition name="popup">
            <div v-if="isOpen" class="popup-chat-window">
                <div class="popup-chat-header">
                    <div class="popup-chat-header-content">
                        <div class="popup-chat-avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="popup-chat-title">AI Ассистент</h3>
                            <p class="popup-chat-status">
                                <span class="status-dot"></span>
                                Онлайн
                            </p>
                        </div>
                    </div>
                    <button @click="toggleChat" class="popup-chat-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <ChatWidget :api-url="apiUrl" :inline="true"/>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import ChatWidget from './ChatWidget.vue';

const props = defineProps({
    apiUrl: {
        type: String,
        default: '/api/chat'
    }
});

const isOpen = ref(false);

const toggleChat = () => {
    isOpen.value = !isOpen.value;
};
</script>

<style scoped>
.popup-chat-wrapper {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.popup-chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #10b981;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.popup-chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
}

.popup-chat-button.active {
    background: #1a1a1a;
}

.popup-chat-button svg {
    width: 28px;
    height: 28px;
    color: #ffffff;
}

.popup-chat-window {
    position: absolute;
    right: 0;
    bottom: 80px;
    width: 400px;
    height: 600px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.popup-chat-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-chat-header-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.popup-chat-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.popup-chat-avatar svg {
    width: 24px;
    height: 24px;
}

.popup-chat-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.popup-chat-status {
    margin: 4px 0 0 0;
    font-size: 13px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #fff;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.popup-chat-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.popup-chat-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.popup-chat-close svg {
    width: 18px;
    height: 18px;
    color: #ffffff;
}

.popup-enter-active,
.popup-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.popup-enter-from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}

.popup-leave-to {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}

@media (max-width: 768px) {
    .popup-chat-wrapper {
        bottom: 16px;
        right: 16px;
        left: 16px;
    }

    .popup-chat-button {
        position: absolute;
        right: 0;
    }

    .popup-chat-window {
        width: 100%;
        height: calc(100vh - 100px);
        bottom: 76px;
        right: 0;
    }
}
</style>
