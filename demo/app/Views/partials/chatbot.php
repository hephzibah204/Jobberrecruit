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
            <button id="clear-chat" class="btn btn-sm text-white-50 p-0 me-2" title="Clear Chat">
                <i class="ti ti-trash fs-16"></i>
            </button>
            <button id="close-chat" class="btn btn-sm text-white p-0">
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
                <button type="submit" class="btn btn-primary">
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
        background: linear-gradient(135deg, var(--primary-color), #4e73df);
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
        background: var(--primary-color);
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
        background: var(--primary-color);
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
$(document).ready(function() {
    const $toggle = $('#chatbot-toggle');
    const $window = $('#chatbot-window');
    const $close = $('#close-chat');
    const $form = $('#chat-form');
    const $input = $('#chat-input');
    const $messages = $('#chat-messages');
    const $typing = $('#typing-indicator');
    const $clear = $('#clear-chat');

    $toggle.on('click', function() {
        $window.toggleClass('d-none');
        scrollToBottom();
    });

    $close.on('click', function() {
        $window.addClass('d-none');
    });

    $clear.on('click', function() {
        if (confirm('Clear chat history?')) {
            $.post('<?= site_url('chatbot/clear') ?>', {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            }, function() {
                $messages.empty();
                addMessage('bot', "Chat history cleared. How can I help you today?");
            });
        }
    });

    $form.on('submit', function(e) {
        e.preventDefault();
        const msg = $input.val().trim();
        if (!msg) return;

        $input.val('');
        addMessage('user', msg);
        
        $typing.removeClass('d-none');
        scrollToBottom();

        $.post('<?= site_url('chatbot/send') ?>', {
            message: msg,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(res) {
            $typing.addClass('d-none');
            if (res.success) {
                addMessage('bot', res.response);
            } else {
                addMessage('bot', "Sorry, I encountered an error. Please try again.");
            }
        }).fail(function() {
            $typing.addClass('d-none');
            addMessage('bot', "Connection error. Please check your internet.");
        });
    });

    function addMessage(sender, text) {
        const time = 'Just now';
        let html = '';
        
        if (sender === 'user') {
            html = `
                <div class="user-message">
                    <div class="message-content shadow-sm">${text}</div>
                    <small class="text-muted me-2 mt-1 d-block">${time}</small>
                </div>
            `;
        } else {
            // Simple markdown-to-html for bot response
            const formattedText = text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            html = `
                <div class="bot-message mb-3">
                    <div class="message-content shadow-sm">${formattedText}</div>
                    <small class="text-muted ms-2 mt-1 d-block">${time}</small>
                </div>
            `;
        }
        
        $messages.append(html);
        scrollToBottom();
    }

    function scrollToBottom() {
        $messages.scrollTop($messages[0].scrollHeight);
    }
});
</script>
