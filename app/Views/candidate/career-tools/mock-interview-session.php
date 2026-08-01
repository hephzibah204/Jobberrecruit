<?= $this->extend('layouts/minimal') ?>

<?= $this->section('content') ?>
<style>
/* ── Mock Interview Session Styles (Aligned to candidate-interview-session.html) ── */
:root {
  --bg: #F4F7FB;
  --panel-bg: #FFFFFF;
  --brand: #0A2F57;
  --brand-light: #E7EDF6;
  --accent: #ED9020;
  --accent-light: #FFF4E8;
  --txt: #1A2B49;
  --muted: #64748B;
  --border: #E2E8F0;
  --success: #16A34A;
  --success-light: #DCFCE7;
  --radius: 12px;
  --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
  --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.07), 0 4px 6px -4px rgb(0 0 0 / 0.07);
}

body {
  background: var(--bg) !important;
  color: var(--txt);
  font-family: 'Outfit', sans-serif;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

/* ── bar ── */
.sess-bar {
  position: sticky;
  top: 0;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 64px;
  padding: 0 24px;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
}
.sess-bar-left {
  display: flex;
  align-items: center;
  gap: 16px;
}
.sess-bar-left h1 {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: var(--brand);
}
.sess-bar-left p {
  font-size: 0.85rem;
  color: var(--muted);
  margin: 0;
}
.timer {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: var(--brand);
  background: var(--brand-light);
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 0.9rem;
  transition: all 0.3s;
}
.timer.warn {
  background: #FEE2E2;
  color: #DC2626;
  animation: pulse-red 1.5s infinite;
}
@keyframes pulse-red {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

/* ── progress rail ── */
.prog-rail {
  height: 4px;
  background: var(--border);
  width: 100%;
  position: relative;
}
.prog-fill {
  height: 100%;
  background: var(--accent);
  width: 0%;
  transition: width 0.4s ease;
}

/* ── stage grid ── */
.stage {
  max-width: 1200px;
  margin: 24px auto;
  padding: 0 24px 100px;
  display: grid;
  grid-template-columns: minmax(0, 1fr) 300px;
  gap: 24px;
  align-items: start;
}
@media (max-width: 960px) {
  .stage {
    grid-template-columns: 1fr;
    padding-bottom: 180px;
  }
}

/* ── convo / bubbles ── */
.convo-col {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
.convo {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.turn {
  display: flex;
  gap: 16px;
  max-width: 85%;
}
.turn--ai {
  align-self: flex-start;
}
.turn--me {
  align-self: flex-end;
  flex-direction: row-reverse;
  max-width: 75%;
}
.turn-ava {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 600;
  font-size: 0.85rem;
  flex-shrink: 0;
}
.turn--me .turn-ava {
  background: var(--accent);
}
.bubble {
  background: #FFFFFF;
  padding: 16px 20px;
  border-radius: 0 var(--radius) var(--radius) var(--radius);
  box-shadow: var(--shadow);
  line-height: 1.5;
  font-size: 0.95rem;
  position: relative;
}
.turn--me .bubble {
  background: var(--brand);
  color: #FFFFFF;
  border-radius: var(--radius) 0 var(--radius) var(--radius);
}
.who {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--muted);
  margin-bottom: 6px;
}
.turn--me .who {
  color: rgba(255,255,255,0.7);
  text-align: right;
}
.q-tag {
  display: inline-block;
  font-size: 0.75rem;
  background: var(--accent-light);
  color: var(--accent);
  padding: 2px 8px;
  border-radius: 4px;
  font-weight: 600;
  margin-bottom: 8px;
}

/* ── thinking & skeleton ── */
.think {
  display: flex;
  align-items: center;
  gap: 12px;
  color: var(--muted);
  font-size: 0.9rem;
}
.think-dots {
  display: inline-flex;
  gap: 4px;
}
.think-dots i {
  width: 6px;
  height: 6px;
  background: var(--muted);
  border-radius: 50%;
  animation: think-bounce 1.4s infinite both;
}
.think-dots i:nth-child(2) { animation-delay: .2s; }
.think-dots i:nth-child(3) { animation-delay: .4s; }
@keyframes think-bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

/* ── answer dock ── */
.dock {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(244, 247, 253, 0.9);
  backdrop-filter: blur(12px);
  padding: 16px 24px;
  border-top: 1px solid var(--border);
  z-index: 9;
}
.dock-card {
  max-width: 1200px;
  margin: 0 auto;
  background: #FFFFFF;
  border-radius: var(--radius);
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--border);
  overflow: hidden;
}
.mode-tabs {
  display: flex;
  background: var(--brand-light);
  border-bottom: 1px solid var(--border);
}
.mode-tab {
  flex: 1;
  padding: 12px;
  border: none;
  background: transparent;
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--brand);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.2s;
  outline: none;
}
.mode-tab[aria-selected="true"] {
  background: #FFFFFF;
  color: var(--brand);
  box-shadow: inset 0 -2px 0 var(--accent);
}
.mode-tab:hover:not([aria-selected="true"]) {
  background: rgba(255, 255, 255, 0.4);
}
.dock-body {
  padding: 16px;
}
.dock-text-area {
  width: 100%;
  border: none;
  outline: none;
  resize: none;
  font-size: 0.95rem;
  color: var(--txt);
  height: 72px;
}
.dock-media {
  display: none;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  padding: 12px 0;
}
.dock-card[data-mode="voice"] .dock-text-area,
.dock-card[data-mode="video"] .dock-text-area {
  display: none;
}
.dock-card[data-mode="voice"] .dock-media,
.dock-card[data-mode="video"] .dock-media {
  display: flex;
}

/* media recording elements */
.call-stage {
  width: 100%;
  max-width: 360px;
  height: 120px;
  background: var(--brand);
  border-radius: var(--radius);
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #fff;
  overflow: hidden;
}
.rwave {
  display: flex;
  align-items: center;
  gap: 4px;
  height: 24px;
  margin-top: 10px;
}
.rwave span {
  display: inline-block;
  width: 3px;
  height: 8px;
  background: rgba(255,255,255,0.4);
  border-radius: 2px;
  transition: transform 0.1s ease;
}
.rwave.talking span {
  animation: wave-active 1.2s infinite ease-in-out;
}
.rwave.talking span:nth-child(2) { animation-delay: 0.15s; }
.rwave.talking span:nth-child(3) { animation-delay: 0.3s; }
.rwave.talking span:nth-child(4) { animation-delay: 0.45s; }
@keyframes wave-active {
  0%, 100% { transform: scaleY(1); }
  50% { transform: scaleY(2.8); }
}

.cam-stage {
  width: 100%;
  max-width: 360px;
  aspect-ratio: 16/9;
  background: #000;
  border-radius: var(--radius);
  position: relative;
  overflow: hidden;
}
.cam-stage video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.cam-stage.rear video {
  transform: scaleX(1);
}
.cam-stage:not(.rear) video {
  transform: scaleX(-1);
}
.cam-overlay {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(0,0,0,0.6);
  color: #fff;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
}
.cam-denied {
  position: absolute;
  inset: 0;
  background: rgba(15, 23, 42, 0.95);
  color: #fff;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  gap: 12px;
}
.cam-denied p {
  font-size: 0.8rem;
  margin: 0;
  opacity: 0.8;
}

/* live captions */
.live-caption {
  width: 100%;
  background: var(--bg);
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 0.85rem;
  color: var(--muted);
  border-left: 3px solid var(--accent);
}
.live-caption.cap-warn {
  border-left-color: #EF4444;
  color: #B91C1C;
}

/* controls */
.rec-controls {
  display: flex;
  align-items: center;
  gap: 16px;
}
.rec-orb {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: var(--brand);
  color: #fff;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: var(--shadow);
  transition: all 0.2s;
  outline: none;
}
.rec-orb:hover {
  transform: scale(1.05);
}
.rec-orb.rec {
  background: #EF4444;
  animation: pulse-red-bg 2s infinite;
}
@keyframes pulse-red-bg {
  0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
  70% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
  100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

.dock-foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 16px;
  border-top: 1px solid var(--border);
  background: #FCFDFE;
}
.wcount {
  font-size: 0.8rem;
  color: var(--muted);
}
.wcount.low {
  color: var(--accent);
}
.wcount.high {
  color: #EF4444;
  font-weight: 600;
}
.dock-actions {
  display: flex;
  gap: 10px;
}

/* ── panel (sidebar) ── */
.panel {
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: sticky;
  top: 88px;
}
.card {
  background: var(--panel-bg);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  padding: 20px;
}
.card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
  border-bottom: 1px solid var(--border);
  padding-bottom: 8px;
}
.card-head h3 {
  font-size: 0.95rem;
  font-weight: 700;
  margin: 0;
  color: var(--brand);
}

/* recruiter card */
.rec-mini {
  display: flex;
  align-items: center;
  gap: 12px;
}
.rec-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1rem;
}
.rec-details h4 {
  margin: 0 0 2px;
  font-size: 0.9rem;
  font-weight: 600;
}
.rec-details p {
  margin: 0;
  font-size: 0.75rem;
  color: var(--muted);
}

/* question map */
.qmap {
  display: flex;
  flex-direction: column;
  gap: 8px;
  max-height: 220px;
  overflow-y: auto;
}
.qm {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 10px;
  border-radius: 6px;
  background: var(--bg);
  font-size: 0.8rem;
  border: 1px solid transparent;
  transition: all 0.2s;
}
.qm.now {
  background: var(--brand-light);
  border-color: var(--brand);
  font-weight: 600;
}
.qm.done {
  background: var(--success-light);
}
.qm .n {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: var(--muted);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: 700;
  flex-shrink: 0;
}
.qm.now .n {
  background: var(--brand);
}
.qm.done .n {
  background: var(--success);
}

/* recruiter's notes list */
.rnotes-list {
  display: none;
  flex-direction: column;
  gap: 10px;
  max-height: 200px;
  overflow-y: auto;
  padding-left: 0;
  list-style: none;
  margin: 0;
}
.rnotes-list.show {
  display: flex;
}
.rnotes-list li {
  font-size: 0.8rem;
  padding: 8px;
  background: var(--bg);
  border-radius: 6px;
  line-height: 1.4;
  border-left: 3px solid var(--brand);
}
.rnotes-list li b {
  display: block;
  margin-bottom: 2px;
  color: var(--brand);
}
.rnotes-empty {
  font-size: 0.8rem;
  color: var(--muted);
  text-align: center;
  padding: 16px 0;
  font-style: italic;
}

/* ── live coaching floating widget ── */
.coach {
  position: fixed;
  bottom: 200px;
  right: 24px;
  width: 320px;
  z-index: 8;
  background: #FFFFFF;
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-lg);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  overflow: hidden;
}
.coach.hide {
  transform: translateX(360px);
  pointer-events: none;
  opacity: 0;
}
.coach-head {
  background: var(--brand);
  color: #fff;
  padding: 12px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.coach-head h4 {
  margin: 0;
  font-size: 0.85rem;
  font-weight: 600;
}
.coach-close {
  background: transparent;
  border: none;
  color: #fff;
  cursor: pointer;
  opacity: 0.8;
}
.coach-body {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.crow {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.crow-info {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  font-weight: 600;
}
.crow-track {
  height: 6px;
  background: var(--border);
  border-radius: 3px;
  overflow: hidden;
}
.crow-track .fill {
  height: 100%;
  background: var(--accent);
  width: 4%;
  border-radius: 3px;
  transition: width 0.3s ease;
}
.crow.pulse .fill {
  animation: pulse-fill 0.5s ease-out;
}
@keyframes pulse-fill {
  0% { opacity: 0.6; }
  100% { opacity: 1; }
}

.coach-tab {
  position: fixed;
  bottom: 220px;
  right: 0;
  background: var(--brand);
  color: #fff;
  padding: 10px 14px 10px 10px;
  border-radius: 8px 0 0 8px;
  cursor: pointer;
  z-index: 7;
  font-size: 0.8rem;
  font-weight: 600;
  box-shadow: var(--shadow);
  display: flex;
  align-items: center;
  gap: 6px;
}

/* ── lobby ── */
.lobby {
  position: fixed;
  inset: 0;
  background: rgba(10, 47, 87, 0.95);
  z-index: 99;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.lobby-card {
  background: #FFFFFF;
  border-radius: var(--radius);
  max-width: 540px;
  width: 100%;
  box-shadow: var(--shadow-lg);
  overflow: hidden;
}
.lobby-head {
  background: var(--brand);
  color: #fff;
  padding: 24px;
  text-align: center;
}
.lobby-head h2 {
  margin: 0 0 8px;
  font-size: 1.4rem;
  font-weight: 700;
}
.lobby-body {
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.lobby-check {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 0.9rem;
  background: var(--bg);
  padding: 12px;
  border-radius: 8px;
}
.lobby-check svg {
  color: var(--success);
  flex-shrink: 0;
}

/* ── completion screen & executive report ── */
.done-wrap {
  display: none;
  max-width: 800px;
  margin: 40px auto;
  padding: 0 24px 100px;
  text-align: center;
}
body.finished .stage,
body.finished .dock,
body.finished .coach,
body.finished .coach-tab {
  display: none !important;
}
body.finished .done-wrap {
  display: block;
}
.done-orb {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: var(--success);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  box-shadow: var(--shadow-lg);
}
.dfacts {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin: 32px 0;
}
.dfact {
  background: #FFFFFF;
  border-radius: var(--radius);
  border: 1px solid var(--border);
  padding: 16px;
  box-shadow: var(--shadow);
}
.dfact b {
  display: block;
  font-size: 1.8rem;
  color: var(--brand);
  margin-bottom: 4px;
}
.dfact span {
  font-size: 0.8rem;
  color: var(--muted);
  font-weight: 600;
}

/* executive report card */
.report {
  background: #FFFFFF;
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-lg);
  padding: 32px;
  text-align: left;
  margin-top: 32px;
}
.rep-grid {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 32px;
  margin-top: 24px;
}
@media (max-width: 680px) {
  .rep-grid {
    grid-template-columns: 1fr;
  }
}
.rep-ring-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}
.rep-ring-box {
  width: 140px;
  height: 140px;
  position: relative;
}
.rep-ring-box svg {
  transform: rotate(-90deg);
}
.rep-overall {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--brand);
}
.rep-subs {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.rep-subs .sub {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.rep-subs i {
  font-style: normal;
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  font-weight: 600;
}
.rep-subs .track {
  height: 6px;
  background: var(--border);
  border-radius: 3px;
  overflow: hidden;
}
.rep-subs .fill {
  height: 100%;
  background: var(--brand);
  width: 0%;
  border-radius: 3px;
  transition: width 0.8s ease;
}
.rep-summary {
  margin: 24px 0;
  font-size: 0.95rem;
  line-height: 1.6;
  color: var(--txt);
}
.rep-lists {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}
@media (max-width: 680px) {
  .rep-lists {
    grid-template-columns: 1fr;
  }
}
.rep-lists ul {
  padding-left: 0;
  list-style: none;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.rep-lists li {
  display: flex;
  gap: 10px;
  font-size: 0.85rem;
  line-height: 1.4;
}
.rep-lists li svg {
  flex-shrink: 0;
  margin-top: 2px;
}

/* radar svg */
.radar-box {
  width: 260px;
  height: 236px;
  margin: 0 auto;
}
.radar-grid {
  fill: none;
  stroke: var(--border);
  stroke-width: 1;
}
.radar-axis {
  stroke: var(--border);
  stroke-width: 1;
}
.radar-shape {
  fill: rgba(10, 47, 87, 0.15);
  stroke: var(--brand);
  stroke-width: 2;
}
.radar-lbl {
  font-size: 9px;
  font-weight: 600;
  fill: var(--muted);
}

/* exit modal */
.modal-scrim {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.4);
  backdrop-filter: blur(4px);
  z-index: 100;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.modal-scrim.show {
  display: flex;
}
.exit-modal-card {
  background: #FFFFFF;
  border-radius: var(--radius);
  max-width: 400px;
  width: 100%;
  padding: 24px;
  box-shadow: var(--shadow-lg);
  text-align: center;
}
.exit-modal-card h3 {
  margin: 0 0 8px;
  color: var(--brand);
}

/* toast */
.toast-msg {
  position: fixed;
  bottom: 230px;
  left: 50%;
  transform: translateX(-50%) translateY(40px);
  background: #000;
  color: #fff;
  padding: 10px 20px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 99;
  box-shadow: var(--shadow-lg);
}
.toast-msg.show {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}

/* utilities */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.2s;
  outline: none;
  gap: 8px;
}
.btn--primary {
  background: var(--brand);
  color: #fff;
}
.btn--primary:hover:not(:disabled) {
  background: #113f70;
}
.btn--secondary {
  background: var(--brand-light);
  color: var(--brand);
}
.btn--secondary:hover:not(:disabled) {
  background: #d8e3f2;
}
.btn--danger {
  background: #EF4444;
  color: #fff;
}
.btn--outline {
  border-color: var(--border);
  background: #fff;
  color: var(--txt);
}
.btn--outline:hover {
  background: var(--bg);
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* offline banner */
.offline-bar {
  background: #EF4444;
  color: #fff;
  text-align: center;
  padding: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  display: none;
}
.offline-bar.show {
  display: block;
}

.confetti-piece {
  position: fixed;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  pointer-events: none;
  z-index: 999;
  top: 0; left: 0;
  opacity: 0;
  animation: confetti-drop 1.8s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
}
@keyframes confetti-drop {
  0% {
    transform: translate(var(--cx), var(--cy)) scale(1) rotate(0deg);
    opacity: 1;
  }
  100% {
    transform: translate(var(--tx), var(--ty)) scale(0.4) rotate(var(--rot));
    opacity: 0;
  }
}
</style>

<!-- SVG Icon Sprite -->
<svg style="display:none;">
  <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
    <path d="M19 10v1a7 7 0 0 1-14 0v-1"/>
    <line x1="12" x2="12" y1="19" y2="22"/>
  </symbol>
  <symbol id="i-video" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="m22 8-6 4 6 4V8Z"/>
    <rect width="14" height="12" x="2" y="6" rx="2" ry="2"/>
  </symbol>
  <symbol id="i-keyboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <rect width="20" height="12" x="2" y="6" rx="2"/>
    <path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M18 14h.01M10 14h4"/>
  </symbol>
  <symbol id="i-stop" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <rect width="14" height="14" x="5" y="5" rx="2"/>
  </symbol>
  <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
    <path d="M16 3h5v5"/>
    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
    <path d="M8 21H3v-5"/>
  </symbol>
  <symbol id="i-vol" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
  </symbol>
  <symbol id="i-vol-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
    <line x1="23" x2="17" y1="9" y2="15"/>
    <line x1="17" x2="23" y1="9" y2="15"/>
  </symbol>
  <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"/>
    <path d="M12 16v-4"/>
    <path d="M12 8h.01"/>
  </symbol>
  <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"/>
    <path d="m9 12 2 2 4-4"/>
  </symbol>
  <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
  </symbol>
</svg>

<div class="offline-bar" id="offline-bar">Offline mode active — answers are queued and will sync when you reconnect</div>

<!-- Top Bar -->
<header class="sess-bar">
  <div class="sess-bar-left">
    <button class="btn btn--outline" id="exit-btn" style="padding: 6px 12px; font-size: 0.8rem;">End Session</button>
    <div>
      <h1 id="meta-title">Mock Interview</h1>
      <p id="meta-sub">Loading...</p>
    </div>
  </div>
  <div class="sess-bar-right" style="display: flex; align-items: center; gap: 12px;">
    <button class="btn btn--outline" id="voice-btn" aria-pressed="true" style="padding: 8px;">
      <svg id="voice-btn-ic" width="18" height="18"><use href="#i-vol"/></svg>
    </button>
    <div class="timer" id="timer">
      <span id="timer-txt">0:00</span>
    </div>
  </div>
</header>

<div class="prog-rail">
  <div class="prog-fill" id="prog-fill"></div>
</div>

<!-- Main Interview Workspace -->
<main class="stage">
  <!-- Left Side: Conversation Column -->
  <div class="convo-col">
    <div class="convo" id="convo">
      <!-- Welcome turn -->
      <div class="turn turn--ai skeleton-turn">
        <span class="turn-ava">AI</span>
        <div class="bubble">
          <div class="who">AI Recruiter</div>
          <div class="think">
            <span class="think-dots"><i></i><i></i><i></i></span>
            <span>Setting up the interview room...</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side sticky panel -->
  <div class="panel">
    <!-- Recruiter Mini Card -->
    <div class="card">
      <div class="rec-mini">
        <div class="rec-avatar" id="rec-avatar">R</div>
        <div class="rec-details">
          <h4 id="p-rec-name">Interviewer</h4>
          <p id="p-rec-role">AI Recruiter</p>
        </div>
      </div>
    </div>

    <!-- Question Map -->
    <div class="card">
      <div class="card-head">
        <h3>Question Map</h3>
        <span class="q-count-pill" id="q-count-pill" style="font-size: 0.75rem; background: var(--bg); padding: 2px 8px; border-radius: 4px; font-weight: 600;"></span>
      </div>
      <div class="qmap" id="qmap"></div>
    </div>

    <!-- Recruiter's Notes -->
    <div class="card">
      <div class="card-head">
        <h3>Recruiter's Notes</h3>
      </div>
      <div class="rnotes-empty" id="rnotes-empty">No notes yet — submit answers to populate.</div>
      <ul class="rnotes-list" id="rnotes-list"></ul>
    </div>

    <!-- Active Tip Box -->
    <div class="card" style="background: var(--accent-light); border-color: var(--accent); color: var(--brand);">
      <div class="card-head" style="border-bottom-color: rgba(10, 47, 87, 0.1);">
        <h3 style="color: var(--brand);">Coaching Tip</h3>
      </div>
      <p id="live-tip-txt" style="font-size: 0.8rem; margin: 0; line-height: 1.4;">Answer a question to receive a tip.</p>
    </div>
  </div>
</main>

<!-- Floating Live Coaching Widget -->
<div class="coach hide" id="coach">
  <div class="coach-head">
    <h4>Live STAR Coach</h4>
    <button class="coach-close" id="coach-close">&times;</button>
  </div>
  <div class="coach-body">
    <!-- Length -->
    <div class="crow" data-k="len">
      <div class="crow-info">
        <span>Answer Length</span>
        <b>0%</b>
      </div>
      <div class="crow-track"><div class="fill"></div></div>
    </div>
    <!-- STAR Structure -->
    <div class="crow" data-k="star">
      <div class="crow-info">
        <span>STAR Structure</span>
        <b>0%</b>
      </div>
      <div class="crow-track"><div class="fill"></div></div>
    </div>
    <!-- Specificity -->
    <div class="crow" data-k="spec">
      <div class="crow-info">
        <span>Specificity (Numbers/Metrics)</span>
        <b>0%</b>
      </div>
      <div class="crow-track"><div class="fill"></div></div>
    </div>
    <!-- Confidence -->
    <div class="crow" data-k="conf">
      <div class="crow-info">
        <span>Confidence Index</span>
        <b>0%</b>
      </div>
      <div class="crow-track"><div class="fill"></div></div>
    </div>
    <!-- Professionalism -->
    <div class="crow" data-k="prof">
      <div class="crow-info">
        <span>Tone & Professionalism</span>
        <b>0%</b>
      </div>
      <div class="crow-track"><div class="fill"></div></div>
    </div>
  </div>
</div>
<div class="coach-tab" id="coach-tab">
  <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217c.004.13.11.23.243.23h.81c.137 0 .25-.113.25-.251v-.15c0-.625.294-.92.898-1.37.596-.443 1.228-1.025 1.228-2.199 0-1.612-1.308-2.268-2.585-2.268-1.47 0-2.617.853-2.68 2.469zM8 12a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
  <span>STAR Coach</span>
</div>

<!-- Answer Input Dock -->
<footer class="dock" id="dock">
  <div class="dock-card" id="dock-card" data-mode="text">
    <!-- Tabs for Modes -->
    <div class="mode-tabs">
      <button class="mode-tab" data-setmode="text" aria-selected="true">
        <svg width="16" height="16"><use href="#i-keyboard"/></svg> Text Mode
      </button>
      <button class="mode-tab" data-setmode="voice" aria-selected="false">
        <svg width="16" height="16"><use href="#i-mic"/></svg> Voice Mode
      </button>
      <button class="mode-tab" data-setmode="video" aria-selected="false">
        <svg width="16" height="16"><use href="#i-video"/></svg> Video Mode
      </button>
    </div>
    
    <div class="dock-body">
      <!-- Textarea for Text Answers -->
      <textarea class="dock-text-area" id="answer" placeholder="Type your response here... (Ctrl+Enter to submit)"></textarea>
      
      <!-- Video/Voice Stages -->
      <div class="dock-media">
        <!-- Voice recording state -->
        <div class="call-stage" id="call-stage">
          <div class="rwave" id="wave-live" hidden>
            <span></span><span></span><span></span><span></span><span></span>
          </div>
          <span id="rec-time" hidden>0:00</span>
          <span id="rec-indicator" hidden style="font-size:0.7rem; color: #EF4444; font-weight:600; display:flex; align-items:center; gap:4px; margin-top:6px;">
            <span style="display:inline-block; width:6px; height:6px; background:#EF4444; border-radius:50%;"></span> Recording
          </span>
        </div>

        <!-- Video preview state -->
        <div class="cam-stage" id="cam-stage" style="display:none;">
          <video id="cam-video" autoplay playsinline muted></video>
          <div class="cam-overlay" id="cam-overlay">
            <span id="cam-status-txt">Camera loading...</span>
            <span id="cam-timer" style="margin-left:8px; font-weight:700;">0:00</span>
          </div>
          <!-- Blocked info overlay -->
          <div class="cam-denied" id="cam-denied" hidden>
            <h4 style="margin:0 0 6px;">Camera Access Required</h4>
            <p id="cam-denied-txt">Permissions denied.</p>
            <button class="btn btn--primary" id="cam-retry" style="padding: 6px 12px; font-size:0.8rem;">Retry Camera</button>
          </div>
        </div>

        <!-- Dynamic Captions -->
        <div class="live-caption" id="live-caption" hidden>
          <div id="live-caption-txt">Listening...</div>
          <button class="btn btn--secondary" id="sr-retry" hidden style="margin-top:6px; padding:4px 8px; font-size:0.75rem;">Retry Voice Service</button>
        </div>

        <!-- Main Recording Orb -->
        <div class="rec-controls">
          <button class="rec-orb" id="rec-orb" aria-label="Start recording your answer">
            <svg id="rec-orb-ic" width="24" height="24"><use href="#i-mic"/></svg>
          </button>
          <span id="media-hint" style="font-size:0.8rem; color:var(--muted);">Tap the microphone to start speaking.</span>
        </div>
      </div>
    </div>

    <!-- Dock Footer -->
    <div class="dock-foot">
      <div class="wcount" id="wcount">0 words</div>
      <div class="dock-actions">
        <button class="btn btn--outline" id="skip-btn">Skip Question</button>
        <button class="btn btn--primary" id="submit-btn" disabled>Submit Answer</button>
      </div>
    </div>
  </div>
</footer>

<!-- Pre-Interview Lobby -->
<div class="lobby" id="lobby">
  <div class="lobby-card">
    <div class="lobby-head">
      <h2>Interview Lobby</h2>
      <p id="lobby-fmt">Loading details...</p>
    </div>
    <div class="lobby-body">
      <div class="lobby-check">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/></svg>
        <div>
          <strong>AI Recruiter Assigned</strong>
          <div style="font-size:0.8rem; color:var(--muted); margin-top:2px;" id="lobby-persona">Recruiter</div>
        </div>
      </div>
      
      <div class="lobby-check">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/></svg>
        <div>
          <strong>Target Role</strong>
          <div style="font-size:0.8rem; color:var(--muted); margin-top:2px;" id="lobby-role">Software Engineer</div>
        </div>
      </div>

      <div class="lobby-check">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/></svg>
        <div>
          <strong>Speech Engine Check</strong>
          <div style="font-size:0.8rem; color:var(--muted); margin-top:2px;" id="lobby-sound">Ready</div>
        </div>
      </div>
      
      <p class="lobby-hint" style="font-size:0.8rem; color:var(--muted); text-align:center; margin:0;">
        Tip: Make sure you are in a quiet environment. We recommend using headphones.
      </p>

      <button class="btn btn--primary" id="lobby-enter" style="width: 100%; height:48px; font-size:1rem;">
        <span id="lobby-enter-txt">Preparing Practice Room...</span>
      </button>
    </div>
  </div>
</div>

<!-- Completion Screen -->
<div class="done-wrap" id="done-wrap">
  <div class="done-orb" id="done-orb">
    <svg width="36" height="36" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/></svg>
  </div>
  <h2 id="done-title" style="margin: 0 0 8px; color:var(--brand); font-weight:700;" tabindex="-1">Interview Complete</h2>
  <p style="color:var(--muted); margin:0;">Your evaluation report is being generated by the AI recruiter</p>
  
  <div class="dfacts">
    <div class="dfact">
      <b id="d-answered">0</b>
      <span>Answered</span>
    </div>
    <div class="dfact">
      <b id="d-skipped">0</b>
      <span>Skipped</span>
    </div>
    <div class="dfact">
      <b id="d-time">0:00</b>
      <span>Practice Time</span>
    </div>
  </div>

  <!-- Executive Assessment Report (Dynamic) -->
  <div class="report" id="report">
    <h3 style="margin: 0; color:var(--brand); font-weight:700; font-size:1.2rem;">Executive Assessment Report</h3>
    
    <div class="rep-grid">
      <div class="rep-ring-col">
        <div class="rep-ring-box">
          <svg width="140" height="140">
            <circle cx="70" cy="70" r="56" fill="none" stroke="var(--border)" stroke-width="10"/>
            <circle id="rep-ring-p" cx="70" cy="70" r="56" fill="none" stroke="var(--brand)" stroke-width="10" stroke-dasharray="352" stroke-dashoffset="352" stroke-linecap="round" style="transition: stroke-dashoffset 1s ease-out;"/>
          </svg>
          <div class="rep-overall" id="rep-overall">0</div>
        </div>
        <div id="rep-band" style="font-weight:600; font-size:0.85rem;"></div>
        
        <!-- Interactive Radar Chart Area -->
        <div class="radar-box" id="radar-box">
          <svg width="260" height="236" id="radar-svg"></svg>
        </div>
      </div>

      <div class="rep-details-col">
        <div class="rep-subs" id="rep-subs"></div>
        <p class="rep-summary" id="rep-summary">Analyzing response narrative...</p>
        
        <div class="rep-lists">
          <div>
            <h4 style="margin:0 0 10px; color:var(--success); font-size:0.9rem; font-weight:700;">Key Strengths</h4>
            <ul id="rep-strengths"></ul>
          </div>
          <div>
            <h4 style="margin:0 0 10px; color:var(--accent); font-size:0.9rem; font-weight:700;">Improvement Points</h4>
            <ul id="rep-fixes"></ul>
          </div>
        </div>
        
        <div style="margin-top:24px; padding:12px; background:var(--brand-light); border-radius:8px; font-size:0.85rem; color:var(--brand); font-weight:600;" id="rep-advice"></div>
      </div>
    </div>
    
    <div style="margin-top:32px; padding-top:20px; border-top:1px solid var(--border); display:flex; gap:12px; justify-content:flex-end;">
      <button class="btn btn--outline" id="rep-pdf">Print Report / Save PDF</button>
      <button class="btn btn--primary" id="rep-share">Share Results</button>
      <button class="btn btn--primary" onclick="window.location.reload();">Practice Again</button>
    </div>
  </div>
</div>

<!-- Exit Confirmation Modal -->
<div class="modal-scrim" id="exit-modal">
  <div class="exit-modal-card">
    <h3>End Practice Session?</h3>
    <p style="color:var(--muted); font-size:0.9rem; margin-bottom:20px;">If you leave now, this session's progress will not be analyzed or saved to your history.</p>
    <div style="display:flex; gap:12px; justify-content:center;">
      <button class="btn btn--outline" id="exit-stay">Resume Practice</button>
      <button class="btn btn--danger" onclick="window.location.href='<?= base_url('candidate/career-tools/mock-interview') ?>'">End Session</button>
    </div>
  </div>
</div>

<div class="toast-msg" id="toast">
  <span id="toast-txt"></span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const $ = function(id) { return document.getElementById(id); };
    
    // Parse Context configuration passed from PHP
    const contextPreset = <?= json_encode($contextPreset) ?>;
    const applicationId = parseInt(contextPreset.application_id || 0);
    const jobTitle = contextPreset.job_title || 'This Role';

    // Persona Configurations
    const personas = {
        'corporate-hr': { name: 'Chioma Nwachukwu', role: 'HR Business Partner', open: 'Hello, I\'m Chioma from HR. Thank you for making time today — I\'ll walk you through a structured set of questions about your experience and fit for the role. Let\'s get started.' },
        'big4-partner': { name: 'Mr. Bankole Adisa', role: 'Partner, Professional Services', open: 'Good day. I\'m Bankole — I lead engagements at partner level, and I hold every candidate to the standard I hold my own team. Let\'s begin; I expect precision in your answers.' },
        'startup-founder': { name: 'Tomiwa', role: 'Founder & CEO', open: 'Hey, I\'m Tomiwa — I run the company, so this is just me, no HR script. I move fast and I like people who move fast too. Let\'s dive in.' },
        'technical-lead': { name: 'Emeka Okafor', role: 'Engineering / Technical Lead', open: 'Hi, I\'m Emeka — I\'ll be going deep on the technical side today. I care less about buzzwords and more about how you actually think through problems. Let\'s start.' },
        'gov-recruiter': { name: 'Alhaji Musa Ibrahim', role: 'Civil Service Interview Panel', open: 'Good day. I am Alhaji Musa Ibrahim, and I will conduct this interview in line with standard civil service procedure. Please answer each question fully, in the order presented.' },
        'banking-recruiter': { name: 'Ngozi Adebayo-Williams', role: 'Talent Recruiter, Banking & Financial Services', open: 'Hello, I\'m Ngozi, and I recruit for banking and financial services roles. We move efficiently here, and we care about precision — especially with numbers. Let\'s begin.' }
    };

    const P = personas[contextPreset.personality] || personas['corporate-hr'];
    
    // Set UI elements with active Persona details
    $('p-rec-name').textContent = P.name;
    $('p-rec-role').textContent = P.role;
    $('rec-avatar').textContent = P.name[0];

    let currentIdx = 0;
    let elapsedSeconds = 0;
    let countdownMinutes = parseInt(contextPreset.duration || 10);
    let remainSeconds = countdownMinutes * 60;
    let timerInterval = null;
    let isSpeaking = false;
    let interviewStarted = false;
    let history = [];
    
    // Total questions is derived from time limit divided by complexity pacing
    const totalQuestions = Math.max(3, Math.min(9, Math.round(countdownMinutes / 2.5)));

    // Setup speech support checks
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognitionSupported = !!SpeechRecognition;
    const synthesisSupported = 'speechSynthesis' in window;
    
    // Live UI updates
    $('meta-title').textContent = (jobTitle && jobTitle !== 'This Role') ? jobTitle + ' Mock Interview' : 'AI Mock Interview';
    $('meta-sub').textContent = `${contextPreset.interview_type || 'General'} · ${contextPreset.difficulty || 'Medium'} · ${countdownMinutes} min`;
    $('lobby-fmt').textContent = `${contextPreset.interview_type || 'General'} · ${contextPreset.difficulty || 'Medium'} · ${totalQuestions} Questions`;

    // The lobby "Preparing…" state is only a brief settling moment before the
    // room is ready — flip the button to its ready label once setup completes
    // so it doesn't look permanently disabled.
    const lobbyEnterBtn = $('lobby-enter');
    lobbyEnterBtn.disabled = true;
    setTimeout(function() {
        lobbyEnterBtn.disabled = false;
        $('lobby-enter-txt').textContent = 'Enter Interview Room';
    }, 900);
    
    // Setup question tracker circles on question map
    function buildQuestionMap() {
        const qmap = $('qmap');
        qmap.innerHTML = '';
        for (let i = 0; i < totalQuestions; i++) {
            const div = document.createElement('div');
            div.className = 'qm';
            div.id = `qm-${i}`;
            div.innerHTML = `<span class="n">${i+1}</span><span>Question ${i+1}</span>`;
            qmap.appendChild(div);
        }
    }

    function setPill() {
        $('q-count-pill').textContent = `${Math.min(currentIdx + 1, totalQuestions)} of ${totalQuestions}`;
    }

    // TTS Voice synthesis logic
    const VOICE = {
        on: true,
        voice: null,
        unlocked: false,
        queue: [],
        playing: false,
        onDone: null
    };

    function pickVoice() {
        if (!synthesisSupported) return;
        const vs = window.speechSynthesis.getVoices();
        if (!vs.length) return;
        const gender = (contextPreset.personality === 'corporate-hr' || contextPreset.personality === 'banking-recruiter') ? 'female' : 'male';
        const searchKeywords = gender === 'female' ? ['female', 'samantha', 'zira', 'karen', 'serena'] : ['male', 'daniel', 'david', 'google', 'fred'];
        const enVoices = vs.filter(v => /^en/i.test(v.lang));
        VOICE.voice = enVoices.find(v => {
            const name = v.name.toLowerCase();
            return searchKeywords.some(kw => name.includes(kw));
        }) || enVoices[0] || vs[0];
    }

    if (synthesisSupported) {
        pickVoice();
        window.speechSynthesis.onvoiceschanged = pickVoice;
    }

    function speakText(text, onDoneCallback) {
        if (!VOICE.on || !synthesisSupported) {
            if (onDoneCallback) onDoneCallback();
            return;
        }
        window.speechSynthesis.cancel();
        const sentences = text.replace(/<[^>]*>/g, '').split(/(?<=[.!?])\s+/);
        let sIdx = 0;
        isSpeaking = true;
        setTalking(true);

        function speakSentence() {
            if (sIdx >= sentences.length) {
                isSpeaking = false;
                setTalking(false);
                if (onDoneCallback) onDoneCallback();
                return;
            }
            const utterance = new SpeechSynthesisUtterance(sentences[sIdx]);
            if (VOICE.voice) utterance.voice = VOICE.voice;
            utterance.pitch = (contextPreset.personality === 'big4-partner' || contextPreset.personality === 'gov-recruiter') ? 0.9 : 1.0;
            utterance.rate = 1.0;
            utterance.onend = () => {
                sIdx++;
                speakSentence();
            };
            utterance.onerror = () => {
                sIdx++;
                speakSentence();
            };
            window.speechSynthesis.speak(utterance);
        }
        speakSentence();
    }

    function setTalking(on) {
        const wave = $('wave-live');
        if (wave) wave.classList.toggle('talking', on);
        const call = $('call-stage');
        if (call) call.classList.toggle('talking', on);
    }

    // Toggle speech button logic
    $('voice-btn').addEventListener('click', function() {
        VOICE.on = !VOICE.on;
        this.setAttribute('aria-pressed', VOICE.on ? 'true' : 'false');
        $('voice-btn-ic').innerHTML = `<use href="#${VOICE.on ? 'i-vol' : 'i-vol-off'}"/>`;
        if (!VOICE.on) {
            window.speechSynthesis.cancel();
            isSpeaking = false;
            setTalking(false);
        }
    });

    // Time ticks timer
    function startTimer() {
        timerInterval = setInterval(() => {
            remainSeconds--;
            elapsedSeconds++;
            
            const minutes = Math.floor(Math.max(0, remainSeconds) / 60);
            const seconds = Math.max(0, remainSeconds) % 60;
            $('timer-txt').textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (remainSeconds === 120) {
                $('timer').classList.add('warn');
                toast("2 minutes remaining. Wrap up your active thoughts.");
            }
            if (remainSeconds <= 0) {
                clearInterval(timerInterval);
                finishSession(true);
            }
        }, 1000);
    }

    function stopTimer() {
        if (timerInterval) clearInterval(timerInterval);
    }

    // Camera and Audio preview systems (Video Mode)
    let mediaStream = null;
    function startCamera() {
        const stage = $('cam-stage');
        const video = $('cam-video');
        $('cam-denied').hidden = true;
        stage.style.display = 'block';
        $('call-stage').style.display = 'none';

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            $('cam-denied-txt').textContent = "Camera capture features not supported in this browser environment.";
            $('cam-denied').hidden = false;
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                mediaStream = stream;
                video.srcObject = stream;
                $('cam-status-txt').textContent = "Webcam Streaming";
            })
            .catch(err => {
                $('cam-denied-txt').textContent = "Permissions blocked or webcam input unavailable.";
                $('cam-denied').hidden = false;
            });
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
        $('cam-stage').style.display = 'none';
        $('cam-video').srcObject = null;
    }

    // Input mode handler text/voice/video
    let activeMode = 'text';
    const dockCard = $('dock-card');

    function switchMode(mode) {
        activeMode = mode;
        dockCard.dataset.mode = mode;
        
        document.querySelectorAll('.mode-tab').forEach(tab => {
            tab.setAttribute('aria-selected', tab.dataset.setmode === mode ? 'true' : 'false');
        });

        if (mode === 'video') {
            startCamera();
        } else {
            stopCamera();
        }

        if (mode === 'voice') {
            $('call-stage').style.display = 'flex';
        } else if (mode === 'text') {
            $('call-stage').style.display = 'none';
        }

        resetRecordingUI();
    }

    document.querySelectorAll('.mode-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            switchMode(this.dataset.setmode);
        });
    });

    // Recording Controls and Audio Transcriptions
    let recording = false;
    let recordingTimer = null;
    let recordedSeconds = 0;
    let recognitionObj = null;

    function resetRecordingUI() {
        recording = false;
        recordedSeconds = 0;
        if (recordingTimer) clearInterval(recordingTimer);
        
        $('rec-orb').classList.remove('rec');
        $('rec-orb-ic').innerHTML = `<use href="#${activeMode === 'video' ? 'i-video' : 'i-mic'}"/>`;
        $('wave-live').hidden = true;
        $('rec-time').hidden = true;
        $('rec-indicator').hidden = true;
        
        if (activeMode === 'text') {
            $('submit-btn').disabled = $('answer').value.trim().split(/\s+/).filter(Boolean).length < 5;
        } else {
            $('submit-btn').disabled = true;
        }
        $('media-hint').textContent = activeMode === 'video' ? 'Tap camera icon to record response.' : 'Tap mic to start speaking.';
    }

    if (recognitionSupported) {
        recognitionObj = new SpeechRecognition();
        recognitionObj.continuous = true;
        recognitionObj.interimResults = true;
        recognitionObj.lang = 'en-US';

        recognitionObj.onresult = (e) => {
            let finalT = '';
            for (let i = e.resultIndex; i < e.results.length; i++) {
                if (e.results[i].isFinal) finalT += e.results[i][0].transcript;
            }
            if (finalT.trim()) {
                $('live-caption-txt').textContent = finalT;
                $('answer').value = finalT;
            }
        };

        recognitionObj.onerror = () => {
            $('live-caption-txt').textContent = "Transcription error or permissions lost. Type details if needed.";
        };
    }

    $('rec-orb').addEventListener('click', function() {
        if (!recording) {
            recording = true;
            this.classList.add('rec');
            $('rec-orb-ic').innerHTML = '<use href="#i-stop"/>';
            $('wave-live').hidden = false;
            $('rec-time').hidden = false;
            $('rec-indicator').hidden = false;
            $('live-caption').hidden = false;
            
            recordedSeconds = 0;
            recordingTimer = setInterval(() => {
                recordedSeconds++;
                const mins = Math.floor(recordedSeconds / 60);
                const secs = recordedSeconds % 60;
                $('rec-time').textContent = `${mins}:${secs < 10 ? '0' : ''}${secs}`;
                $('cam-timer').textContent = `${mins}:${secs < 10 ? '0' : ''}${secs}`;
            }, 1000);

            if (recognitionObj) {
                try { recognitionObj.start(); } catch(e) {}
            }
            $('media-hint').textContent = "Speaking... tap stop button when complete.";
        } else {
            recording = false;
            clearInterval(recordingTimer);
            this.classList.remove('rec');
            $('rec-orb-ic').innerHTML = '<use href="#i-refresh"/>';
            $('wave-live').hidden = true;
            $('rec-indicator').hidden = true;
            
            if (recognitionObj) {
                try { recognitionObj.stop(); } catch(e) {}
            }
            
            $('media-hint').textContent = "Review answer transcript. Submit or tap reload to record again.";
            $('submit-btn').disabled = false;
        }
    });

    $('cam-retry').addEventListener('click', startCamera);

    // Words validator for submission
    $('answer').addEventListener('input', function() {
        const words = this.value.trim().split(/\s+/).filter(Boolean).length;
        $('wcount').textContent = `${words} word${words === 1 ? '' : 's'}`;
        $('submit-btn').disabled = words < 5;
    });

    // Chat view rendering helper
    function appendBubble(sender, htmlText) {
        const turnDiv = document.createElement('div');
        turnDiv.className = `turn turn--${sender === 'user' ? 'me' : 'ai'}`;
        
        const avatar = sender === 'user' ? 'You' : P.name[0];
        const label = sender === 'user' ? 'You' : `${P.name} · AI Recruiter`;

        turnDiv.innerHTML = `
            <span class="turn-ava">${avatar}</span>
            <div class="bubble">
                <div class="who">${label}</div>
                <div>${htmlText}</div>
            </div>
        `;
        $('convo').appendChild(turnDiv);
        turnDiv.scrollIntoView({ behavior: 'smooth' });
    }

    // Submit user answer to controller API and get next dynamic response
    function submitAnswer() {
        const answer = $('answer').value.trim();
        if (!answer) return;

        appendBubble('user', answer);
        
        // Save answers into memory local history
        history.push({ sender: 'user', message: answer });
        
        $('answer').value = '';
        $('wcount').textContent = '0 words';
        $('submit-btn').disabled = true;

        // Render AI analysis/thinking animation
        const thinkDiv = document.createElement('div');
        thinkDiv.className = 'turn turn--ai';
        thinkDiv.innerHTML = `
            <span class="turn-ava">${P.name[0]}</span>
            <div class="bubble">
                <div class="who">${P.name} · AI Recruiter</div>
                <div class="think">
                    <span class="think-dots"><i></i><i></i><i></i></span>
                    <span>Analyzing responses...</span>
                </div>
            </div>
        `;
        $('convo').appendChild(thinkDiv);
        thinkDiv.scrollIntoView({ behavior: 'smooth' });

        // Build parameters form payload
        const formData = new FormData();
        formData.append('type', 'interview');
        formData.append('message', answer);
        formData.append('history', JSON.stringify(history));
        formData.append('extra', jobTitle);
        formData.append('difficulty', contextPreset.difficulty);
        formData.append('questionPack', contextPreset.question_pack);
        formData.append('interviewMode', activeMode);
        formData.append('webcamEnabled', activeMode === 'video' ? '1' : '0');
        formData.append('applicationId', String(applicationId));

        fetch('<?= base_url('candidate/career-tools/send-message') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            thinkDiv.remove();
            
            // Record model responses to transcript history
            history.push({ sender: 'model', message: data.message });

            // Display dynamic AI question response in chat
            const tag = `<span class="q-tag">Question ${currentIdx + 2} of ${totalQuestions}</span><br>`;
            appendBubble('model', tag + data.message);
            speakText(data.message);

            // Extract quality metrics dynamically
            const feedback = data.feedback || {};
            updateSTARIndicators(data);
            writeNotesPill(currentIdx, answer, data);

            // Move pointer forward on sidebar question map
            const currentPoint = $(`qm-${currentIdx}`);
            if (currentPoint) {
                currentPoint.classList.remove('now');
                currentPoint.classList.add('done');
            }

            currentIdx++;
            if (currentIdx >= totalQuestions) {
                finishSession(false);
                return;
            }

            const nextPoint = $(`qm-${currentIdx}`);
            if (nextPoint) {
                nextPoint.classList.add('now');
            }
            setPill();

            // Set coaching tip based on feedback
            $('live-tip-txt').textContent = data.star_tip || "Structure your answer highlighting Situation, Task, Action, and Result.";
            
            // Update progress rail
            const pct = Math.round((currentIdx / totalQuestions) * 100);
            $('prog-fill').style.width = `${pct}%`;

            resetRecordingUI();
        })
        .catch(err => {
            thinkDiv.remove();
            toast("Connection timeout. Please retry.");
            $('submit-btn').disabled = false;
        });
    }

    $('submit-btn').addEventListener('click', submitAnswer);

    // Support keyboard submissions
    $('answer').addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && !$('submit-btn').disabled) {
            submitAnswer();
        }
    });

    // Handle skipped actions
    $('skip-btn').addEventListener('click', function() {
        history.push({ sender: 'user', message: "[Candidate Skipped Question]" });
        
        const skippedPoint = $(`qm-${currentIdx}`);
        if (skippedPoint) {
            skippedPoint.classList.remove('now');
            skippedPoint.classList.add('done');
            skippedPoint.style.borderLeft = '3px solid #EF4444';
        }

        // Request next question turn from backend to keep AI in sync
        submitAnswerText("[Candidate Skipped Question. Please ask the next question.]");
    });

    function submitAnswerText(textStr) {
        // Render AI analysis/thinking animation
        const thinkDiv = document.createElement('div');
        thinkDiv.className = 'turn turn--ai';
        thinkDiv.innerHTML = `
            <span class="turn-ava">${P.name[0]}</span>
            <div class="bubble">
                <div class="who">${P.name} · AI Recruiter</div>
                <div class="think">
                    <span class="think-dots"><i></i><i></i><i></i></span>
                    <span>Fetching next question...</span>
                </div>
            </div>
        `;
        $('convo').appendChild(thinkDiv);
        thinkDiv.scrollIntoView({ behavior: 'smooth' });

        const formData = new FormData();
        formData.append('type', 'interview');
        formData.append('message', textStr);
        formData.append('history', JSON.stringify(history));
        formData.append('extra', jobTitle);
        formData.append('difficulty', contextPreset.difficulty);
        formData.append('questionPack', contextPreset.question_pack);
        formData.append('interviewMode', activeMode);
        formData.append('webcamEnabled', activeMode === 'video' ? '1' : '0');
        formData.append('applicationId', String(applicationId));

        fetch('<?= base_url('candidate/career-tools/send-message') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            thinkDiv.remove();
            history.push({ sender: 'model', message: data.message });

            const tag = `<span class="q-tag">Question ${currentIdx + 2} of ${totalQuestions}</span><br>`;
            appendBubble('model', tag + data.message);
            speakText(data.message);

            currentIdx++;
            if (currentIdx >= totalQuestions) {
                finishSession(false);
                return;
            }

            const nextPoint = $(`qm-${currentIdx}`);
            if (nextPoint) {
                nextPoint.classList.add('now');
            }
            setPill();

            const pct = Math.round((currentIdx / totalQuestions) * 100);
            $('prog-fill').style.width = `${pct}%`;

            resetRecordingUI();
        })
        .catch(err => {
            thinkDiv.remove();
            toast("Connection timeout. Please retry.");
        });
    }

    // Populate dynamic Recruiter note items
    function writeNotesPill(qNum, responseText, dataObj) {
        const list = $('rnotes-list');
        $('rnotes-empty').style.display = 'none';
        list.classList.add('show');

        const li = document.createElement('li');
        
        let commentText = dataObj.feedback || "Detailed response provided.";
        if (responseText.split(/\s+/).length < 25) {
            commentText = "Brief answer. Lacked supporting specific details.";
        }

        li.innerHTML = `<b>Question ${qNum + 1} Note</b>${commentText}`;
        list.appendChild(li);
        list.scrollTop = list.scrollHeight;
    }

    // STAR Coach metrics
    function updateSTARIndicators(dataObj) {
        const breakdown = dataObj.star_breakdown || {};
        const rating = {
            len: Math.round((dataObj.message || '').split(/\s+/).length / 2), // rough estimate
            star: dataObj.star_score ? dataObj.star_score * 10 : 70,
            spec: breakdown.result ? breakdown.result * 10 : 70,
            conf: breakdown.action ? breakdown.action * 10 : 80,
            prof: breakdown.situation ? breakdown.situation * 10 : 90
        };

        Object.keys(rating).forEach(key => {
            const row = document.querySelector(`.crow[data-k="${key}"]`);
            if (row) {
                row.querySelector('b').textContent = `${rating[key]}%`;
                row.querySelector('.fill').style.width = `${rating[key]}%`;
                row.classList.remove('pulse');
                void row.offsetWidth;
                row.classList.add('pulse');
            }
        });

        // Auto display STAR coaching panel
        $('coach').classList.remove('hide');
    }

    $('coach-close').addEventListener('click', () => $('coach').classList.add('hide'));
    $('coach-tab').addEventListener('click', () => $('coach').classList.remove('hide'));

    // Exit confirm popup triggers
    $('exit-btn').addEventListener('click', () => {
        $('exit-modal').classList.add('show');
    });
    $('exit-stay').addEventListener('click', () => {
        $('exit-modal').classList.remove('show');
    });

    // Finish session and generate assessment reports
    function finishSession(isTimeout = false) {
        stopTimer();
        stopCamera();
        
        // Remove tracking styles
        document.body.classList.add('finished');
        
        $('d-answered').textContent = currentIdx;
        $('d-skipped').textContent = totalQuestions - currentIdx;
        $('d-time').textContent = `${Math.floor(elapsedSeconds / 60)}m ${elapsedSeconds % 60}s`;

        // Send payload data to final assessment API controller
        const formData = new FormData();
        formData.append('history', JSON.stringify(history));
        formData.append('jobTitle', jobTitle);
        formData.append('difficulty', contextPreset.difficulty);
        formData.append('questionPack', contextPreset.question_pack);
        formData.append('interviewMode', activeMode);
        formData.append('webcamEnabled', activeMode === 'video' ? '1' : '0');
        formData.append('durationSeconds', String(elapsedSeconds));
        formData.append('applicationId', String(applicationId));

        fetch('<?= base_url('candidate/career-tools/evaluate-interview') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            // Render scorecard parameters
            $('rep-overall').textContent = data.overall_score || 0;
            
            const offsetVal = Math.round(352 * (1 - (data.overall_score || 0) / 100));
            $('rep-ring-p').style.strokeDashoffset = offsetVal;

            let badgeClass = 'pill pill--pending';
            let impression = 'Warming Up';
            if (data.overall_score >= 75) {
                badgeClass = 'pill pill--success';
                impression = 'Ready for real panels';
            } else if (data.overall_score >= 55) {
                badgeClass = 'pill pill--brand';
                impression = 'Progressing well';
            }
            $('rep-band').innerHTML = `<span class="${badgeClass}">${impression}</span>`;

            // Score breakdown bars
            const breakdowns = [
                { title: 'STAR Structure', val: data.star_average || 0 },
                { title: 'Communication Clarity', val: data.communication_score || 0 },
                { title: 'Confidence Factor', val: data.confidence_score || 0 },
                { title: 'Specificity Score', val: data.relevance_score || 0 }
            ];

            const scoreContainer = $('rep-subs');
            scoreContainer.innerHTML = '';
            breakdowns.forEach(sc => {
                const subDiv = document.createElement('div');
                subDiv.className = 'sub';
                subDiv.innerHTML = `
                    <i>${sc.title} <small>${sc.val}/10</small></i>
                    <div class="track"><div class="fill" style="width: ${sc.val * 10}%"></div></div>
                `;
                scoreContainer.appendChild(subDiv);
            });

            $('rep-summary').textContent = data.summary || "Comprehensive responses submitted. Candidate highlights details showing technical depth.";

            // Render strength items
            const strengthsList = $('rep-strengths');
            strengthsList.innerHTML = '';
            (data.strengths || ['Well organized responses', 'Strong active technical vocabulary']).forEach(st => {
                const li = document.createElement('li');
                li.innerHTML = `<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><use href="#i-check-c"/></svg><span>${st}</span>`;
                strengthsList.appendChild(li);
            });

            // Render improvements
            const improvementsList = $('rep-fixes');
            improvementsList.innerHTML = '';
            (data.improvements || ['Include key project statistics', 'Explain architectural alternatives']).forEach(imp => {
                const li = document.createElement('li');
                li.innerHTML = `<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><use href="#i-zap"/></svg><span>${imp}</span>`;
                improvementsList.appendChild(li);
            });

            $('rep-advice').textContent = `Recommended action: focus your next practice run on specificity metrics.`;

            // Draw radar canvas SVG
            drawRadarChart(data);
            
            // Trigger confetti celebration on completed workflows
            celebrateConfetti();
        })
        .catch(err => {
            toast("Unable to complete final evaluation metrics. Try again.");
        });
    }

    function drawRadarChart(data) {
        const svg = $('radar-svg');
        const cx = 130, cy = 118, R = 88;
        const metrics = [
            { name: 'STAR', score: (data.star_average || 7) * 10 },
            { name: 'Clarity', score: (data.communication_score || 7) * 10 },
            { name: 'Confidence', score: (data.confidence_score || 7) * 10 },
            { name: 'Specificity', score: (data.relevance_score || 7) * 10 },
            { name: 'Completeness', score: 90 }
        ];

        function getPt(i, radius) {
            const angle = -Math.PI / 2 + (i * 2 * Math.PI / 5);
            return [cx + radius * Math.cos(angle), cy + radius * Math.sin(angle)];
        }

        let innerMarkup = '';
        // Background guides
        [.33, .66, 1].forEach(factor => {
            const pts = metrics.map((_, idx) => getPt(idx, R * factor).join(',')).join(' ');
            innerMarkup += `<polygon class="radar-grid" points="${pts}"/>`;
        });

        // Draw axes lines
        metrics.forEach((m, idx) => {
            const axisPt = getPt(idx, R);
            const labelPt = getPt(idx, R + 18);
            innerMarkup += `<line class="radar-axis" x1="${cx}" y1="${cy}" x2="${axisPt[0]}" y2="${axisPt[1]}"/>`;
            innerMarkup += `<text class="radar-lbl" x="${labelPt[0]}" y="${labelPt[1]}" text-anchor="middle">${m.name}</text>`;
        });

        // Metric shapes
        const shapePts = metrics.map((m, idx) => getPt(idx, R * (m.score / 100)).join(',')).join(' ');
        innerMarkup += `<polygon class="radar-shape" points="${shapePts}"/>`;

        svg.innerHTML = innerMarkup;
    }

    function celebrateConfetti() {
        const doneOrb = $('done-orb');
        if (!doneOrb) return;
        const rect = doneOrb.getBoundingClientRect();
        const cx = rect.left + rect.width/2;
        const cy = rect.top + rect.height/2;
        const colors = ['#0861A9', '#ED9020', '#16A34A', '#0A2F57'];

        for (let i = 0; i < 20; i++) {
            const piece = document.createElement('span');
            piece.className = 'confetti-piece';
            
            const angle = (Math.PI * 2 * i / 20) + (Math.random() * 0.4 - 0.2);
            const dist = 80 + Math.random() * 100;
            
            piece.style.setProperty('--cx', `${cx}px`);
            piece.style.setProperty('--cy', `${cy}px`);
            piece.style.setProperty('--tx', `${cx + Math.cos(angle) * dist}px`);
            piece.style.setProperty('--ty', `${cy + Math.sin(angle) * dist + 50}px`);
            piece.style.setProperty('--rot', `${Math.random() * 360}deg`);
            piece.style.background = colors[i % colors.length];
            
            document.body.appendChild(piece);
            setTimeout(() => piece.remove(), 1800);
        }
    }

    // Helper alerts logger
    function toast(text) {
        const box = $('toast');
        $('toast-txt').textContent = text;
        box.classList.add('show');
        setTimeout(() => box.classList.remove('show'), 3500);
    }

    // Initialize lobby entry handler
    $('lobby-enter').addEventListener('click', function() {
        $('lobby').style.display = 'none';
        document.body.classList.remove('in-lobby');
        interviewStarted = true;
        
        // Start timers and initialize questions
        startTimer();
        buildQuestionMap();
        
        appendBubble('model', P.open);
        history.push({ sender: 'model', message: P.open });
        
        // Retrieve initial question dynamically from AI service to start the interview
        submitAnswerText(`Starting mock interview for ${jobTitle}. Mode: ${activeMode}. Difficulty: ${contextPreset.difficulty}.`);
    });

    document.body.classList.add('in-lobby');
});
</script>
<?= $this->endSection() ?>
