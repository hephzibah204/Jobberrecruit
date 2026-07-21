<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
.chat-messages-container {
    height: 400px;
    overflow-y: auto;
    padding: 24px;
    background: rgba(245, 249, 254, 0.5);
    border-radius: var(--radius);
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.chat-messages-container::-webkit-scrollbar {
    width: 4px;
}
.chat-messages-container::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}
.chat-bubble {
    max-width: 80%;
    padding: 14px 18px;
    border-radius: 18px;
    font-size: 0.86rem;
    line-height: 1.6;
    position: relative;
    animation: bubbleIn 0.3s ease;
}
@keyframes bubbleIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.chat-bubble--model {
    background: #fff;
    color: var(--text);
    border: 1px solid var(--border);
    border-bottom-left-radius: 4px;
    align-self: flex-start;
}
.chat-bubble--user {
    background: var(--brand);
    color: #fff;
    border-bottom-right-radius: 4px;
    align-self: flex-end;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    
    <!-- Hero -->
    <section class="nego-hero" aria-labelledby="studio-title" style="margin-bottom: 24px; border-radius: var(--radius-lg); color: #fff; padding: 34px; background: radial-gradient(ellipse 60% 90% at 88% 8%,rgba(237,144,32,.22) 0%,transparent 55%),linear-gradient(150deg,#0A2F57 0%,#064A85 55%,#0861A9 100%);">
        <div class="hero-grid" style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: center;">
            <div>
                <div class="hero-badges" style="display: flex; gap: 8px; margin-bottom: 12px;">
                    <span class="hb hb--premium" style="background: rgba(237,144,32,.18); border: 1px solid rgba(237,144,32,.45); color: #ffd9a8; font-size: 0.66rem; font-weight: 700; padding: 5px 12px; border-radius: 20px;">
                        <svg aria-hidden="true" style="width:12px;height:12px;display:inline-block;vertical-align:middle;margin-right:4px;fill:currentColor;"><use href="#i-crown"/></svg> Premium Simulation
                    </span>
                </div>
                <h1 id="studio-title" style="font-family:'Sora',sans-serif; font-size:clamp(1.5rem,3.4vw,2.15rem); font-weight:800; color:#fff; margin:0 0 8px;">Salary Negotiation Simulator</h1>
                <p class="hero-sub" style="font-size:0.92rem; color:rgba(255,255,255,0.85); line-height:1.6; max-width:560px;">Master the art of negotiation. Practice with our AI HR representative to secure the compensation you deserve.</p>
            </div>
            <div class="page-actions" style="margin-top: 10px;">
                <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-ghost-w btn-sm">
                    <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-arrow-l"/></svg> Back to Tools
                </a>
            </div>
        </div>
    </section>

    <!-- Simulator Glass Card -->
    <div style="max-width: 800px; margin: 0 auto;">
        <section class="card" aria-label="Salary negotiation simulator interaction" style="padding: 24px;">
            <div class="sim-head" style="display:flex; align-items:center; gap:12px; margin-bottom:20px; border-bottom:1px solid var(--border); padding-bottom:16px;">
                <div class="sim-head-ic" style="width:40px; height:40px; border-radius:10px; background:var(--brand-light); color:var(--brand); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg aria-hidden="true" style="width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-wallet"/></svg>
                </div>
                <div>
                    <h2 style="font-family:'Sora',sans-serif; font-size:1rem; font-weight:800; color:var(--brand-deep); margin:0;">AI HR Representative</h2>
                    <p style="font-size:0.76rem; color:var(--muted); margin:0;">Active Practice Session</p>
                </div>
            </div>

            <!-- Chat messages -->
            <div class="chat-messages-container" id="chat-window">
                <div class="chat-bubble chat-bubble--model">
                    Hello! I'm here to help you practice your salary negotiation. To start, please provide the <strong>Current Offer Details</strong> (Job Title, Base Salary, and any benefits offered).
                </div>
            </div>

            <!-- Chat Input form -->
            <form id="chat-form" style="display:flex; gap:12px; margin-top:20px;">
                <input type="text" id="chat-input" class="input" placeholder="Type your response here..." autocomplete="off" style="flex:1;">
                <button type="submit" class="btn btn-primary" id="btn-send" style="min-width:100px;">
                    Send
                </button>
            </form>
        </section>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatWindow = document.getElementById('chat-window');
    const btnSend = document.getElementById('btn-send');
    
    let history = [];
    let offerDetails = '';
    
    function appendMessage(role, message) {
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble chat-bubble--${role === 'user' ? 'user' : 'model'}`;
        bubble.innerHTML = message.replace(/\n/g, '<br>');
        
        chatWindow.appendChild(bubble);
        chatWindow.scrollTop = chatWindow.scrollHeight;
        
        if (message !== "Hello! I'm here to help you practice your salary negotiation. To start, please provide the **Current Offer Details** (Job Title, Base Salary, and any benefits offered).") {
            history.push({sender: role, message: message});
        }
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if (!msg) return;
        
        if (!offerDetails) {
            offerDetails = msg;
            chatInput.value = '';
            appendMessage('user', msg);
            appendMessage('model', `I see. Based on those details, how would you like to respond to this offer? Remember, I'll be playing the role of the HR representative.`);
            return;
        }
        
        chatInput.value = '';
        appendMessage('user', msg);
        
        btnSend.disabled = true;
        btnSend.innerHTML = '<span class="spinner"></span>';
        
        const formData = new FormData();
        formData.append('type', 'negotiation');
        formData.append('message', msg);
        formData.append('history', JSON.stringify(history));
        formData.append('extra', offerDetails);
        
        fetch('<?= base_url('candidate/career-tools/send-message') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            appendMessage('model', data.message);
        })
        .catch(error => {
            toastr.error('Connection error. Please try again.');
        })
        .finally(() => {
            btnSend.disabled = false;
            btnSend.innerHTML = 'Send';
        });
    });
});
</script>
<?= $this->endSection() ?>
