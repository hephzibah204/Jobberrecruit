<!-- Chatbot Floating Toggle -->
<div id="chatbot-toggle" class="chatbot-toggle shadow-lg">
    <i class="ti ti-message-chatbot fs-24 text-white"></i>
    <span class="pulse"></span>
</div>

<!-- Chatbot Window -->
<div id="chatbot-window" class="chatbot-window shadow-xl d-none">
    <div class="chatbot-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="bot-avatar me-2">
                <i class="ti ti-robot fs-20 text-primary"></i>
            </div>
            <div>
                <h6 class="mb-0 text-white fw-bold">JobberAI Assistant</h6>
                <small class="text-white-50"><span class="status-indicator"></span> Online</small>
            </div>
        </div>
        <div class="header-actions">
            <button id="clear-chat" class="btn btn-sm text-white-50 p-0 me-2" title="Clear Chat" aria-label="Delete">
                <i class="ti ti-trash fs-16"></i>
            </button>
            <button id="close-chat" class="btn btn-sm text-white p-0" aria-label="Close">
                <i class="ti ti-x fs-18"></i>
            </button>
        </div>
    </div>
    
    <div id="chat-messages" class="chat-messages p-3">
        <div class="bot-message mb-3">
            <div class="message-content shadow-sm">
                Hello! I'm your AI assistant. How can I help you today?
            </div>
            <small class="text-muted ms-2 mt-1 d-block">Just now</small>
        </div>
    </div>

    <div id="typing-indicator" class="typing-indicator d-none px-3 mb-2">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <div class="chat-footer p-3 border-top">
        <form id="chat-form" class="chat-form">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." autocomplete="off">
                <button type="submit" class="btn btn-primary" aria-label="Action">
                    <i class="ti ti-send"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .chatbot-toggle {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand, #0A2F57), #4e73df);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1050;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .chatbot-toggle:hover {
        transform: scale(1.1);
    }
    
    .chatbot-toggle .pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--brand, #0A2F57);
        animation: pulse-animation 2s infinite;
        z-index: -1;
        opacity: 0.6;
    }
    
    @keyframes pulse-animation {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    
    .chatbot-window {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 380px;
        height: 500px;
        background: #fff;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        z-index: 1050;
        overflow: hidden;
        animation: slide-in-up 0.4s ease;
    }
    
    @keyframes slide-in-up {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .chatbot-header {
        background: linear-gradient(135deg, #1e1e2d, #2d2d44);
        padding: 15px 20px;
        color: #fff;
    }
    
    .bot-avatar {
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        margin-right: 4px;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        background: #f8f9fa;
        scroll-behavior: smooth;
    }
    
    .message-content {
        padding: 10px 15px;
        border-radius: 15px;
        max-width: 85%;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
    }
    
    .bot-message .message-content {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 2px;
    }
    
    .user-message {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-bottom: 15px;
    }
    
    .user-message .message-content {
        background: var(--brand, #0A2F57);
        color: #fff;
        border-bottom-right-radius: 2px;
    }
    
    .chat-footer {
        background: #fff;
    }
    
    .chat-form .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #eee;
    }
    
    .chat-form .btn {
        border-radius: 10px;
        margin-left: 5px;
    }
    
    .typing-indicator .dot {
        height: 8px;
        width: 8px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        animation: typing 1s infinite ease-in-out;
    }
    
    .typing-indicator .dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator .dot:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes typing {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Mobile adjustments (clears bottom navigation bar) */
    @media (max-width: 768px) {
        .chatbot-toggle {
            bottom: 80px;
            right: 18px;
            width: 50px;
            height: 50px;
        }
        .chatbot-window {
            bottom: 140px;
            right: 12px;
            left: 12px;
            width: auto;
            height: calc(100vh - 180px);
            max-height: 520px;
        }
    }
    
    /* Dark mode adjustments */
    [data-theme-mode="dark"] .chatbot-window {
        background: #1e1e2d;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    [data-theme-mode="dark"] .chat-messages {
        background: #1a1a27;
    }
    
    [data-theme-mode="dark"] .bot-message .message-content {
        background: #2d2d44;
        color: #e2e2e2;
    }
    
    [data-theme-mode="dark"] .chat-footer {
        background: #1e1e2d;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    [data-theme-mode="dark"] .chat-form .form-control {
        background: #2d2d44;
        border-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('chatbot-toggle');
    var win = document.getElementById('chatbot-window');
    var close = document.getElementById('close-chat');
    var form = document.getElementById('chat-form');
    var input = document.getElementById('chat-input');
    var messages = document.getElementById('chat-messages');
    var typing = document.getElementById('typing-indicator');
    var clear = document.getElementById('clear-chat');

    if (!toggle || !win) return;

    function scrollToBottom() {
        if (messages) messages.scrollTop = messages.scrollHeight;
    }

    toggle.addEventListener('click', function() {
        win.classList.toggle('d-none');
        scrollToBottom();
    });

    if (close) {
        close.addEventListener('click', function() {
            win.classList.add('d-none');
        });
    }

    if (clear) {
        clear.addEventListener('click', function() {
            if (confirm('Clear chat history?')) {
                fetch('<?= site_url('chatbot/clear') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-query-urlencoded'
                    },
                    body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
                }).then(function() {
                    if (messages) {
                        messages.innerHTML = '';
                        addMessage('bot', "Chat history cleared. How can I help you today?");
                    }
                });
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var msg = input.value.trim();
            if (!msg) return;

            input.value = '';
            addMessage('user', msg);
            
            if (typing) typing.classList.remove('d-none');
            scrollToBottom();

            var formData = new FormData();
            formData.append('message', msg);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= site_url('chatbot/send') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(res) {
                if (typing) typing.classList.add('d-none');
                if (res.success) {
                    addMessage('bot', res.response);
                } else {
                    addMessage('bot', "Sorry, I encountered an error. Please try again.");
                }
            })
            .catch(function() {
                if (typing) typing.classList.add('d-none');
                addMessage('bot', "Connection error. Please check your internet.");
            });
        });
    }

    function addMessage(sender, text) {
        var time = 'Just now';
        var html = '';
        
        if (sender === 'user') {
            html = '<div class="user-message">' +
                        '<div class="message-content shadow-sm">' + escapeHtml(text) + '</div>' +
                        '<small class="text-muted me-2 mt-1 d-block">' + time + '</small>' +
                    '</div>';
        } else {
            // Server already returns sanitized, allowlisted HTML (see AiService::sanitizeHtml) — render as-is.
            html = '<div class="bot-message mb-3">' +
                        '<div class="message-content shadow-sm">' + text + '</div>' +
                        '<small class="text-muted ms-2 mt-1 d-block">' + time + '</small>' +
                    '</div>';
        }
        
        if (messages) {
            messages.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
</script>
