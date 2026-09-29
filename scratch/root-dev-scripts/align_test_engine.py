import re

filepath = r"C:\Users\hephz\Documents\CODEBASE\Jobberrecruit\app\Views\candidate\aptitude\test_engine.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update the JS initialization block to load dynamic data
js_pattern = r'const QUESTIONS = \[.*?\n// Countdown timer \(DISPLAY ONLY — server enforces real timing\)'
js_replacement = """let QUESTIONS = [];
const answers = {};      // qIndex -> optIndex
const flags = {};         // qIndex -> bool
let cur = 0;
const keys = ['A','B','C','D','E'];
const attemptId = '<?= $attempt['id'] ?>';

function loadQuestions() {
  fetch(`<?= base_url('api/aptitude/attempts') ?>/${attemptId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
      if (data.status === 'success') {
          // Map to match the expected QUESTIONS array format
          QUESTIONS = data.questions.map(q => ({
              id: q.id,
              q: q.body,
              opts: q.options.map(o => o.body),
              option_ids: q.options.map(o => o.id)
          }));
          
          // Set remaining time
          const expiresAt = new Date(data.attempt.expires_at).getTime();
          const serverTime = data.server_time * 1000;
          remaining = Math.max(0, Math.floor((expiresAt - serverTime) / 1000));
          
          if (QUESTIONS.length > 0) {
              render();
              startTimer();
          } else {
              toastr.error('No questions loaded.');
          }
      } else {
          toastr.error('Failed to load questions.');
      }
  })
  .catch(err => {
      console.error(err);
      toastr.error('Network error loading test.');
  });
}

function render(){
  if (QUESTIONS.length === 0) return;
  const q = QUESTIONS[cur];
  document.getElementById('qnum').textContent = `Question ${cur+1} of ${QUESTIONS.length}`;
  document.getElementById('qbody').textContent = q.q;
  document.getElementById('prog').style.width = ((cur+1)/QUESTIONS.length*100)+'%';
  const opts = document.getElementById('opts');
  opts.innerHTML = '';
  q.opts.forEach((o,i)=>{
    const sel = answers[cur]===i ? ' selected':'';
    const el = document.createElement('div');
    el.className = 'tst-opt'+sel;
    el.onclick = ()=>select(i);
    el.innerHTML = `<span class="tst-opt-key">${keys[i]}</span><span class="tst-opt-text">${o}</span>`;
    opts.appendChild(el);
  });
  // nav state
  document.getElementById('prev').style.visibility = cur===0?'hidden':'visible';
  document.getElementById('next').textContent = cur===QUESTIONS.length-1?'Review & submit':'Next';
  const fl = document.getElementById('flag');
  fl.className = 'tst-flag'+(flags[cur]?' flagged':'');
  renderDots();
}

function renderDots(){
  const d = document.getElementById('dots');
  d.innerHTML = '';
  QUESTIONS.forEach((_,i)=>{
    const b = document.createElement('button');
    let c = 'tst-dot';
    if(answers[i]!==undefined) c+=' answered';
    if(flags[i]) c+=' flagged';
    if(i===cur) c+=' current';
    b.className = c; b.textContent = i+1;
    b.onclick = ()=>{cur=i;render();};
    d.appendChild(b);
  });
}

function select(i){
  answers[cur]=i;
  render();
  
  // autosave hook
  const q = QUESTIONS[cur];
  const selectedOptId = q.option_ids[i];
  
  const formData = new FormData();
  formData.append('question_id', q.id);
  formData.append('option_ids[]', selectedOptId);
  
  fetch(`<?= base_url('api/aptitude/attempts') ?>/${attemptId}/answer`, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
      if (data.status !== 'success') {
          console.error('Failed to autosave answer');
      }
  });
}

function go(dir){
  if(dir===1 && cur===QUESTIONS.length-1){ openModal(); return; }
  cur = Math.max(0, Math.min(QUESTIONS.length-1, cur+dir));
  render();
}

function toggleFlag(){ flags[cur]=!flags[cur]; render(); }

function openModal(){
  const answered = Object.keys(answers).length;
  const msg = answered<QUESTIONS.length
    ? `You've answered ${answered} of ${QUESTIONS.length} questions. Unanswered questions will be marked incorrect. Submit anyway?`
    : `You've answered all ${QUESTIONS.length} questions. Once submitted, your official score will be recorded and added to your profile.`;
  document.getElementById('modal-msg').textContent = msg;
  document.getElementById('modal').classList.add('show');
}

function closeModal(){ document.getElementById('modal').classList.remove('show'); }

function submitTest(){
  const formData = new FormData();
  formData.append('tab_switches', tabSwitches);

  const modal = document.getElementById('modal');
  if (modal) modal.classList.remove('show');

  fetch(`<?= base_url('api/aptitude/attempts') ?>/${attemptId}/submit`, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {
      if (data.status === 'success') {
          window.location.href = data.redirect;
      } else {
          toastr.error('Submission failed.');
      }
  })
  .catch(err => {
      console.error(err);
      toastr.error('Error submitting test.');
  });
}

// Countdown timer (DISPLAY ONLY — server enforces real timing)"""
content = re.sub(js_pattern, js_replacement, content, flags=re.DOTALL)

# Update startTest trigger to fetch questions
content = content.replace("if (MODE !== 'official') { startTest(); }", "loadQuestions();")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Aligned test_engine.php frontend with backend")
