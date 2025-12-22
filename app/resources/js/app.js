import './bootstrap';
import {createApp} from 'vue';
import ChatWidget from './components/ChatWidget.vue';
import PopupChat from './components/PopupChat.vue';

// Initialize popup chat on demo page
if (document.querySelector('.popup-chat-wrapper') === null) {
    const popupContainer = document.createElement('div');
    document.body.appendChild(popupContainer);

    const popupApp = createApp(PopupChat, {
        apiUrl: window.location.origin + '/api/chat'
    });
    popupApp.mount(popupContainer);
}

// Initialize inline chat on demo page
const inlineChatElement = document.getElementById('inline-chat');
if (inlineChatElement) {
    const inlineApp = createApp(ChatWidget, {
        apiUrl: window.location.origin + '/api/chat',
        inline: true
    });
    inlineApp.mount(inlineChatElement);
}

// Export for widget.js
window.ChatWidget = ChatWidget;
window.PopupChat = PopupChat;
