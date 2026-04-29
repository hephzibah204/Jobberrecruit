<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Salary Negotiation Simulator</h4>
                <h6>Practice the art of closing the deal.</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Tools
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card custom-card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-success py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-white-transparent me-3">
                            <i class="ti ti-coin text-white fs-20"></i>
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">HR Representative AI</h6>
                            <p class="text-white-50 fs-12 mb-0">Salary Negotiation Session</p>
                        </div>
                    </div>
                </div>
                <div class="card-body chat-container p-0" style="height: 450px; overflow-y: auto;" id="chat-window">
                    <div class="p-4" id="chat-messages">
                        <div class="chat-message model-message mb-4">
                            <div class="d-flex">
                                <div class="avatar avatar-sm bg-success-transparent me-2 flex-shrink-0">
                                    <i class="ti ti-user"></i>
                                </div>
                                <div class="message-content p-3 bg-light rounded-3">
                                    <p class="mb-0">Hello! I'm here to help you practice your salary negotiation. To start, please provide the **Current Offer Details** (Job Title, Base Salary, and any benefits offered).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top p-3">
                    <form id="chat-form">
                        <div class="input-group">
                            <input type="text" id="chat-input" class="form-control border-0 bg-light" placeholder="Type your response here..." autocomplete="off">
                            <button class="btn btn-success text-white" type="submit" id="btn-send">
                                <i class="ti ti-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container::-webkit-scrollbar {
    width: 5px;
}
.chat-container::-webkit-scrollbar-thumb {
    background: #e0e0e0;
    border-radius: 10px;
}
.message-content {
    max-width: 85%;
    font-size: 14px;
    line-height: 1.6;
}
.user-message .d-flex {
    flex-direction: row-reverse;
}
.user-message .message-content {
    background-color: #198754 !important;
    color: white;
    margin-right: 0;
    margin-left: 10px;
}
.user-message .avatar {
    margin-right: 0 !important;
    margin-left: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatWindow = document.getElementById('chat-window');
    const chatMessages = document.getElementById('chat-messages');
    const btnSend = document.getElementById('btn-send');
    
    let history = [];
    let offerDetails = '';
    
    function appendMessage(role, message) {
        const div = document.createElement('div');
        div.className = `chat-message ${role === 'user' ? 'user-message' : 'model-message'} mb-4`;
        
        const isUser = role === 'user';
        const icon = isUser ? 'ti-user' : 'ti-user-check';
        const bg = isUser ? 'bg-success' : 'bg-success-transparent';
        
        div.innerHTML = `
            <div class="d-flex">
                <div class="avatar avatar-sm ${bg} me-2 flex-shrink-0">
                    <i class="ti ${icon}"></i>
                </div>
                <div class="message-content p-3 bg-light rounded-3 shadow-xs">
                    ${message.replace(/\n/g, '<br>')}
                </div>
            </div>
        `;
        
        chatMessages.appendChild(div);
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
        btnSend.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        
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
            btnSend.innerHTML = '<i class="ti ti-send"></i>';
        });
    });
});
</script>
<?= $this->endSection() ?>
