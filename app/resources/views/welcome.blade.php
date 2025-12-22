<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Widget - Умный чат-бот для вашего сайта</title>
    <meta name="description"
          content="Встраиваемый AI чат-виджет на базе Google Gemini. Простая интеграция, современный дизайн, мгновенные ответы.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
        }

        .hero {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 24px;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: #1a1a1a;
            color: white;
        }

        .btn-primary:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            padding: 80px 20px;
        }

        .section-title {
            font-size: 42px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 16px;
            color: #1a1a1a;
        }

        .section-subtitle {
            font-size: 18px;
            text-align: center;
            color: #666;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            margin-top: 40px;
        }

        .feature-card {
            background: #fafafa;
            padding: 32px;
            border-radius: 16px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
            border-color: #10b981;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1a1a1a;
        }

        .feature-text {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
        }

        .demo-section {
            background: #fafafa;
        }

        .demo-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .demo-content h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .demo-content p {
            font-size: 16px;
            color: #666;
            margin-bottom: 24px;
            line-height: 1.8;
        }

        .demo-widget {
            height: 600px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .code-section {
            background: #1a1a1a;
            color: white;
            padding: 80px 20px;
        }

        .code-section .section-title {
            color: white;
        }

        .code-section .section-subtitle {
            color: rgba(255, 255, 255, 0.7);
        }

        .code-block {
            background: #000;
            border-radius: 16px;
            padding: 32px;
            position: relative;
            margin-top: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .code-lang {
            color: #10b981;
            font-size: 14px;
            font-weight: 600;
        }

        .copy-btn {
            background: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: #059669;
        }

        .copy-btn.copied {
            background: #059669;
        }

        pre {
            margin: 0;
            overflow-x: auto;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
            color: #e5e5e5;
        }

        .code-comment {
            color: #10b981;
        }

        .code-tag {
            color: #10b981;
        }

        .code-attr {
            color: #60a5fa;
        }

        .code-string {
            color: #fbbf24;
        }

        .stats-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 60px 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
        }

        .cta-section {
            padding: 100px 20px;
            text-align: center;
            background: #fafafa;
        }

        .cta-section h2 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .cta-section p {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }

        footer {
            background: #1a1a1a;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        footer p {
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }

            .hero-subtitle {
                font-size: 16px;
            }

            .section-title {
                font-size: 32px;
            }

            .demo-container {
                grid-template-columns: 1fr;
            }

            .demo-widget {
                height: 500px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>AI Чат-Бот для Вашего Сайта</h1>
        <p class="hero-subtitle">
            Встраиваемый виджет с искусственным интеллектом на базе Google Gemini.
            Мгновенные ответы, простая интеграция, современный дизайн.
        </p>
        <div class="hero-buttons">
            <a href="#demo" class="btn btn-primary">
                Попробовать демо
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="#integration" class="btn btn-secondary">
                Начать использовать
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Возможности</h2>
        <p class="section-subtitle">
            Современный AI-ассистент с продвинутыми функциями
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🤖</div>
                <h3 class="feature-title">Google Gemini AI</h3>
                <p class="feature-text">
                    Используем новейшую модель искусственного интеллекта от Google для умных и точных ответов
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💾</div>
                <h3 class="feature-title">История диалогов</h3>
                <p class="feature-text">
                    Все разговоры сохраняются в базе данных с возможностью продолжения беседы
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3 class="feature-title">Адаптивный дизайн</h3>
                <p class="feature-text">
                    Идеально работает на всех устройствах - от смартфонов до десктопов
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Быстрая интеграция</h3>
                <p class="feature-text">
                    Всего 3 строки кода - и чат работает на вашем сайте
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3 class="feature-title">Два режима</h3>
                <p class="feature-text">
                    Встраиваемый режим для страниц или всплывающее окно в углу
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Безопасность</h3>
                <p class="feature-text">
                    Защищенное соединение, сессии пользователей, CORS настроен
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Demo Section -->
<section class="section demo-section" id="demo">
    <div class="container">
        <div class="demo-container">
            <div class="demo-content">
                <h2>Попробуйте прямо сейчас</h2>
                <p>
                    Это живой пример работы чата. Задайте любой вопрос и получите
                    ответ от AI-ассистента. Все сообщения сохраняются, и вы можете
                    продолжить беседу в любое время.
                </p>
                <p>
                    Виджет работает в двух режимах: как полноценный встраиваемый чат
                    (как здесь справа) или как всплывающее окно в углу экрана
                    (кнопка в правом нижнем углу).
                </p>
                <button class="btn btn-primary" onclick="document.querySelector('.popup-chat-button').click()">
                    Открыть всплывающий чат
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </button>
            </div>
            <div class="demo-widget" id="inline-chat"></div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-number">&lt;50KB</div>
                <div class="stat-label">Размер виджета</div>
            </div>
            <div class="stat">
                <div class="stat-number">&lt;1s</div>
                <div class="stat-label">Время загрузки</div>
            </div>
            <div class="stat">
                <div class="stat-number">100%</div>
                <div class="stat-label">Адаптивность</div>
            </div>
            <div class="stat">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Доступность</div>
            </div>
        </div>
    </div>
</section>

<!-- Code Integration Section -->
<section class="code-section" id="integration">
    <div class="container">
        <h2 class="section-title">Простая интеграция</h2>
        <p class="section-subtitle">
            Добавьте эти 3 строки кода в любое место вашей HTML страницы
        </p>

        <div class="code-block">
            <div class="code-header">
                <span class="code-lang">HTML</span>
                <button class="copy-btn" onclick="copyCode('popup-code', this)">Копировать</button>
            </div>
            <pre id="popup-code"><span class="code-comment">&lt;!-- Всплывающий чат (в углу экрана) --&gt;</span>
<span class="code-tag">&lt;script</span> <span class="code-attr">src</span>=<span class="code-string">"{{ url('/widget.js') }}"</span><span
                    class="code-tag">&gt;&lt;/script&gt;</span>
<span class="code-tag">&lt;script&gt;</span>
    window.initPopupChat(<span class="code-string">'{{ url('/api/chat') }}'</span>);
<span class="code-tag">&lt;/script&gt;</span></pre>
        </div>

        <div class="code-block" style="margin-top: 24px;">
            <div class="code-header">
                <span class="code-lang">HTML</span>
                <button class="copy-btn" onclick="copyCode('inline-code', this)">Копировать</button>
            </div>
            <pre id="inline-code"><span class="code-comment">&lt;!-- Встроенный чат (на странице) --&gt;</span>
<span class="code-tag">&lt;div</span> <span class="code-attr">id</span>=<span class="code-string">"my-chat"</span> <span
                    class="code-attr">style</span>=<span class="code-string">"height: 600px"</span><span
                    class="code-tag">&gt;&lt;/div&gt;</span>
<span class="code-tag">&lt;script</span> <span class="code-attr">src</span>=<span class="code-string">"{{ url('/widget.js') }}"</span><span
                    class="code-tag">&gt;&lt;/script&gt;</span>
<span class="code-tag">&lt;script&gt;</span>
    window.initInlineChat(<span class="code-string">'#my-chat'</span>, <span
                    class="code-string">'{{ url('/api/chat') }}'</span>);
<span class="code-tag">&lt;/script&gt;</span></pre>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>Готовы начать?</h2>
        <p>Добавьте AI-ассистента на свой сайт за 5 минут</p>
        <div class="hero-buttons">
            <button class="btn btn-primary" onclick="copyCode('popup-code', this)">
                Скопировать код
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <p>AI Chat Widget © {{ date('Y') }} • Powered by Google Gemini & Laravel</p>
    </div>
</footer>

<script>
    function copyCode(elementId, button) {
        const code = document.getElementById(elementId).textContent;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(code).then(() => {
                showCopiedState(button);
            }).catch((err) => {
                console.error('Clipboard API failed:', err);
                fallbackCopy(code, button);
            });
        } else {
            fallbackCopy(code, button);
        }
    }

    function fallbackCopy(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showCopiedState(button);
        } catch (err) {
            console.error('Fallback copy failed:', err);
            alert('Не удалось скопировать. Пожалуйста, скопируйте вручную.');
        }

        document.body.removeChild(textArea);
    }

    function showCopiedState(button) {
        if (!button) return;

        const originalText = button.textContent;
        button.textContent = '✓ Скопировано!';
        button.classList.add('copied');

        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('copied');
        }, 2000);
    }
</script>
</body>
</html>
