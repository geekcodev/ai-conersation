@verbatim
        (function () {
            'use strict';

            // Global initialization flag
            window._aiChatWidgetLoaded = false;
            window._aiChatWidgetQueue = [];

            // Check if Vue is already loaded
            if (!window.Vue) {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/vue@3.4/dist/vue.global.prod.js';
                script.onload = initWidget;
                script.onerror = function () {
                    console.error('Failed to load Vue.js for AI Chat Widget');
                };
                document.head.appendChild(script);
            } else {
                initWidget();
            }

            function initWidget() {
                const {createApp, ref, onMounted, nextTick} = window.Vue;

                // Inject styles
                if (!document.getElementById('ai-chat-widget-styles')) {
                    const style = document.createElement('style');
                    style.id = 'ai-chat-widget-styles';
                    style.textContent = `.chat-widget{display:flex;flex-direction:column;height:100%;background:#fff;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif}.chat-widget.inline-mode{border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.12);overflow:hidden}.chat-messages{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:16px;background:#fafafa}.chat-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;text-align:center;padding:40px 20px}.chat-empty-icon{font-size:64px;margin-bottom:16px;opacity:.5}.chat-empty-title{font-size:20px;font-weight:600;color:#1a1a1a;margin:0 0 8px}.chat-empty-text{font-size:14px;color:#666;margin:0}.chat-message{display:flex;gap:12px;align-items:flex-start;max-width:85%;animation:slideIn .3s ease-out}@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}.chat-message.user{margin-left:auto;flex-direction:row-reverse}.chat-message-avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#f0f0f0}.chat-message.user .chat-message-avatar{background:#10b981}.chat-message-avatar svg{width:20px;height:20px;color:#666}.chat-message.user .chat-message-avatar svg{color:#fff}.chat-message-content{display:flex;flex-direction:column;gap:4px}.chat-message.user .chat-message-content{align-items:flex-end}.chat-message-text{background:#fff;padding:12px 16px;border-radius:16px;font-size:15px;line-height:1.5;color:#1a1a1a;word-wrap:break-word;white-space:pre-wrap;box-shadow:0 1px 2px rgba(0,0,0,.05)}.chat-message.user .chat-message-text{background:#10b981;color:#fff}.chat-message-time{font-size:11px;color:#999;padding:0 4px}.chat-typing{display:flex;gap:6px;padding:16px;background:#fff;border-radius:16px;box-shadow:0 1px 2px rgba(0,0,0,.05)}.chat-typing span{width:8px;height:8px;border-radius:50%;background:#10b981;animation:typing 1.4s infinite}.chat-typing span:nth-child(2){animation-delay:.2s}.chat-typing span:nth-child(3){animation-delay:.4s}@keyframes typing{0%,60%,100%{opacity:.3;transform:scale(.8)}30%{opacity:1;transform:scale(1)}}.chat-input-wrapper{background:#fff;border-top:1px solid #e5e5e5;padding:16px}.chat-input-container{display:flex;gap:12px;align-items:flex-end;background:#fafafa;border:2px solid #e5e5e5;border-radius:24px;padding:8px 8px 8px 16px;transition:border-color .2s}.chat-input-container:focus-within{border-color:#10b981}.chat-input{flex:1;border:none;background:transparent;padding:8px 0;font-size:15px;line-height:1.5;outline:0;resize:none;min-height:24px;max-height:120px;font-family:inherit;color:#1a1a1a}.chat-input::placeholder{color:#999}.chat-input:disabled{opacity:.6;cursor:not-allowed}.chat-send-btn{width:40px;height:40px;border-radius:50%;border:none;background:#10b981;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .2s}.chat-send-btn:hover:not(:disabled){background:#059669;transform:scale(1.05)}.chat-send-btn:disabled{opacity:.5;cursor:not-allowed;transform:scale(1)}.chat-send-btn svg{width:20px;height:20px}.chat-spinner{animation:spin 1s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}.chat-messages::-webkit-scrollbar{width:6px}.chat-messages::-webkit-scrollbar-track{background:transparent}.chat-messages::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}.chat-messages::-webkit-scrollbar-thumb:hover{background:#9ca3af}.popup-chat-wrapper{position:fixed;bottom:24px;right:24px;z-index:9999;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}.popup-chat-button{width:60px;height:60px;border-radius:50%;background:#10b981;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(16,185,129,.3);transition:all .3s cubic-bezier(.4,0,.2,1)}.popup-chat-button:hover{transform:scale(1.1);box-shadow:0 12px 32px rgba(16,185,129,.4)}.popup-chat-button.active{background:#1a1a1a}.popup-chat-button svg{width:28px;height:28px;color:#fff}.popup-chat-window{position:absolute;right:0;bottom:80px;width:400px;height:600px;max-height:calc(100vh - 120px);background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);display:flex;flex-direction:column;overflow:hidden}.popup-chat-header{background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:#fff;padding:20px;display:flex;justify-content:space-between;align-items:center}.popup-chat-header-content{display:flex;align-items:center;gap:12px}.popup-chat-avatar{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;backdrop-filter:blur(10px)}.popup-chat-avatar svg{width:24px;height:24px}.popup-chat-title{margin:0;font-size:16px;font-weight:600}.popup-chat-status{margin:4px 0 0;font-size:13px;opacity:.9;display:flex;align-items:center;gap:6px}.status-dot{width:8px;height:8px;border-radius:50%;background:#fff;animation:pulse 2s infinite}@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}.popup-chat-close{background:rgba(255,255,255,.2);border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}.popup-chat-close:hover{background:rgba(255,255,255,.3)}.popup-chat-close svg{width:18px;height:18px;color:#fff}@media (max-width:768px){.chat-messages{padding:16px;gap:12px}.chat-message{max-width:90%}.chat-message-avatar{width:32px;height:32px}.chat-input-wrapper{padding:12px}.popup-chat-wrapper{bottom:16px;right:16px;left:16px}.popup-chat-button{position:absolute;right:0}.popup-chat-window{width:100%;height:calc(100vh - 100px);bottom:76px;right:0}}`;
                    document.head.appendChild(style);
                }

                // Chat Widget Component
                const ChatWidget = {
                    name: 'ChatWidget',
                    props: {
                        apiUrl: {type: String, required: true},
                        inline: {type: Boolean, default: false}
                    },
                    setup(props) {
                        const messages = ref([]);
                        const inputMessage = ref('');
                        const isSending = ref(false);
                        const isTyping = ref(false);
                        const sessionId = ref('');

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
                                const url = props.apiUrl + '/history?session_id=' + sessionId.value;
                                const response = await fetch(url);
                                const data = await response.json();
                                if (data.success) messages.value = data.data.messages;
                            } catch (error) {
                                console.error('Failed to load history:', error);
                            }
                        };

                        const sendMessage = async () => {
                            const message = inputMessage.value.trim();
                            if (!message || isSending.value) return;

                            inputMessage.value = '';
                            isSending.value = true;

                            messages.value.push({
                                id: Date.now(),
                                role: 'user',
                                content: message,
                                timestamp: new Date().toISOString()
                            });

                            isTyping.value = true;

                            try {
                                const url = props.apiUrl + '/send';
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        session_id: sessionId.value,
                                        message: message
                                    })
                                });

                                const data = await response.json();
                                isTyping.value = false;

                                if (data.success) {
                                    messages.value.push({
                                        id: data.data.message_id,
                                        role: 'assistant',
                                        content: data.data.message,
                                        timestamp: data.data.timestamp
                                    });
                                } else {
                                    messages.value.push({
                                        id: Date.now(),
                                        role: 'assistant',
                                        content: 'Извините, произошла ошибка.',
                                        timestamp: new Date().toISOString()
                                    });
                                }
                            } catch (error) {
                                isTyping.value = false;
                                messages.value.push({
                                    id: Date.now(),
                                    role: 'assistant',
                                    content: 'Извините, не удалось подключиться.',
                                    timestamp: new Date().toISOString()
                                });
                                console.error('Chat error:', error);
                            }

                            isSending.value = false;
                        };

                        const formatTime = (timestamp) => {
                            const date = new Date(timestamp);
                            return date.toLocaleTimeString('ru-RU', {hour: '2-digit', minute: '2-digit'});
                        };

                        onMounted(() => {
                            sessionId.value = getOrCreateSessionId();
                            loadHistory();
                        });

                        return {messages, inputMessage, isSending, isTyping, sendMessage, formatTime};
                    },
                    template: `
                        <div class="chat-widget" :class="{ 'inline-mode': inline }">
                            <div class="chat-messages">
                                <div v-if="messages.length === 0" class="chat-empty">
                                    <div class="chat-empty-icon">💬</div>
                                    <p class="chat-empty-title">Начните диалог</p>
                                    <p class="chat-empty-text">Задайте вопрос нашему AI-ассистенту</p>
                                </div>
                                <div v-for="m in messages" :key="m.id" :class="['chat-message', m.role]">
                                    <div class="chat-message-avatar">
                                        <svg v-if="m.role === 'user'" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                        </svg>
                                    </div>
                                    <div class="chat-message-content">
                                        <div class="chat-message-text">{{m.content}}</div>
                                        <div class="chat-message-time">{{formatTime(m.timestamp)}}</div>
                                    </div>
                                </div>
                                <div v-if="isTyping" class="chat-message assistant">
                                    <div class="chat-message-avatar">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                        </svg>
                                    </div>
                                    <div class="chat-message-content">
                                        <div class="chat-typing"><span></span><span></span><span></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-input-wrapper">
                                <div class="chat-input-container">
                                    <textarea v-model="inputMessage" @keydown.enter.exact.prevent="sendMessage"
                                              class="chat-input" placeholder="Введите сообщение..." rows="1"
                                              :disabled="isSending"></textarea>
                                    <button @click="sendMessage" class="chat-send-btn"
                                            :disabled="isSending || !inputMessage.trim()">
                                        <svg v-if="!isSending" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                            <line x1="22" y1="2" x2="11" y2="13"></line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                        <svg v-else class="chat-spinner" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `
                };

                // Popup Chat Component
                const PopupChat = {
                    name: 'PopupChat',
                    props: {
                        apiUrl: {type: String, required: true}
                    },
                    components: {ChatWidget},
                    setup() {
                        const isOpen = ref(false);
                        const toggleChat = () => {
                            isOpen.value = !isOpen.value;
                        };
                        return {isOpen, toggleChat};
                    },
                    template: `
                        <div class="popup-chat-wrapper">
                            <button @click="toggleChat" class="popup-chat-button" :class="{ active: isOpen }">
                                <svg v-if="!isOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
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
                                            <p class="popup-chat-status"><span class="status-dot"></span>Онлайн</p>
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
                        </div>
                    `
                };

                // Public API functions
                window.initInlineChat = function (selector, apiUrl) {
                    if (!apiUrl) {
                        console.error('AI Chat Widget: apiUrl is required');
                        return;
                    }

                    const element = document.querySelector(selector);
                    if (element) {
                        createApp(ChatWidget, {
                            apiUrl: apiUrl,
                            inline: true
                        }).mount(element);
                    } else {
                        console.error('AI Chat Widget: Element not found:', selector);
                    }
                };

                window.initPopupChat = function (apiUrl) {
                    if (!apiUrl) {
                        console.error('AI Chat Widget: apiUrl is required');
                        return;
                    }

                    // Check if popup already exists
                    if (document.querySelector('.popup-chat-wrapper')) {
                        console.warn('AI Chat Widget: Popup chat already initialized');
                        return;
                    }

                    const container = document.createElement('div');
                    document.body.appendChild(container);
                    createApp(PopupChat, {apiUrl: apiUrl}).mount(container);
                };

                // Mark as loaded
                window._aiChatWidgetLoaded = true;

                // Process queued calls
                if (window._aiChatWidgetQueue && window._aiChatWidgetQueue.length > 0) {
                    window._aiChatWidgetQueue.forEach(function (item) {
                        if (item.type === 'inline') {
                            window.initInlineChat(item.selector, item.apiUrl);
                        } else if (item.type === 'popup') {
                            window.initPopupChat(item.apiUrl);
                        }
                    });
                    window._aiChatWidgetQueue = [];
                }

                // Auto-initialize popup chat if no manual init
                setTimeout(function () {
                    if (!document.querySelector('.popup-chat-wrapper') &&
                        !document.querySelector('.chat-widget')) {
                        // Only auto-init if on the same domain
                        if (window.location.hostname === '{{ parse_url(url('/'), PHP_URL_HOST) }}') {
                            window.initPopupChat('/api/chat');
                        }
                    }
                }, 100);
            }

            // Wrapper functions that queue calls until widget is ready
            (function () {
                var originalInitInline = window.initInlineChat;
                var originalInitPopup = window.initPopupChat;

                window.initInlineChat = function (selector, apiUrl) {
                    if (window._aiChatWidgetLoaded) {
                        originalInitInline(selector, apiUrl);
                    } else {
                        window._aiChatWidgetQueue.push({type: 'inline', selector: selector, apiUrl: apiUrl});
                    }
                };

                window.initPopupChat = function (apiUrl) {
                    if (window._aiChatWidgetLoaded) {
                        originalInitPopup(apiUrl);
                    } else {
                        window._aiChatWidgetQueue.push({type: 'popup', apiUrl: apiUrl});
                    }
                };
            }) ();
        }) ();
@endverbatim
