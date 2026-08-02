<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>







img{max-width:100%;height:auto;display:block}
svg{flex-shrink:0}
.container{max-width:1160px;margin:0 auto;padding:0 20px}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.section-label{display:inline-flex;align-items:center;gap:7px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brand);background:var(--brand-light);padding:5px 13px;border-radius:20px;margin-bottom:14px}
.section-label svg{width:13px;height:13px}
.section-title{font-size:clamp(1.6rem,2.9vw,2.25rem);font-weight:800;line-height:1.15;margin-bottom:12px}
.section-title span{color:var(--brand)}
.section-sub{color:var(--muted);font-size:.95rem;max-width:560px}
.skip-link{position:absolute;top:-50px;left:16px;background:var(--brand);color:var(--white);padding:8px 16px;border-radius:0 0 6px 6px;font-weight:600;z-index:9999;transition:top .2s}
.skip-link:focus{top:0}












/* LOGO — exact homepage image structure */
.nav-logo{display:flex;align-items:center;text-decoration:none;flex-shrink:0}
.nav-logo img{height:60px;width:auto;display:block}

/* EXACT NAVBAR — homepage */







.nav-caret{width:13px;height:13px;transition:transform var(--transition)}





.mob-group{display:flex;flex-direction:column}
.mob-group-label{font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);padding:4px 0;margin-top:6px}
.mob-group 
.nav-actions{display:flex;align-items:center;gap:8px}
.nav-actions 
.nav-actions 




.hero-grid{position:absolute;inset:0;pointer-events:none;opacity:.45;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:46px 46px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 50% 30%,#000 30%,transparent 80%)}
.hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 400px;gap:52px;align-items:center;padding-bottom:0}
.hero-tag{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:5px 14px;color:rgba(255,255,255,.92);margin-bottom:18px}
.live-dot{width:7px;height:7px;border-radius:50%;background:var(--accent);animation:pulse 1.6s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.7)}}
.hero h1{font-size:clamp(2rem,4vw,3rem);font-weight:800;line-height:1.1;margin-bottom:16px}
.hero h1 em{font-style:normal;color:var(--accent)}
.hero-sub{font-size:.95rem;color:rgba(255,255,255,.68);max-width:460px;line-height:1.72;margin-bottom:30px}
.hero-chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:32px}
.hero-chip{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border-radius:8px;font-size:.78rem;font-weight:600;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.16);color:rgba(255,255,255,.88);transition:var(--transition);text-decoration:none}
.hero-chip:hover{background:rgba(255,255,255,.18);text-decoration:none;color:#fff}
.hero-chip svg{width:14px;height:14px;color:var(--accent)}
.hero-stats{display:flex;gap:32px;padding:24px 0 56px;border-top:1px solid rgba(255,255,255,.12)}
.stat-val{font-family:'Sora',sans-serif;font-size:1.8rem;font-weight:800;line-height:1;color:#fff}
.stat-val span{color:var(--accent)}
.stat-lbl{font-size:.7rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:rgba(255,255,255,.45);margin-top:3px}

/* FEATURED CARD */
.feat-card{background:var(--white);border-radius:16px;box-shadow:0 28px 70px rgba(0,0,0,.32);overflow:hidden;transform:translateY(32px)}
.fc-thumb{height:120px;display:flex;align-items:center;justify-content:center;position:relative;background:linear-gradient(135deg,#0A2F57,#0D609E)}
.fc-thumb-icon{width:48px;height:48px;color:rgba(255,255,255,.25)}
.fc-avatar{position:absolute;bottom:-20px;left:18px;width:44px;height:44px;border-radius:50%;border:3px solid #fff;background:var(--brand);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:.82rem;font-weight:800;color:#fff}
.fc-badges{position:absolute;top:10px;right:10px;display:flex;gap:6px}
.fc-badge{font-size:.62rem;font-weight:800;padding:3px 8px;border-radius:20px;letter-spacing:.04em}
.fc-badge-next{background:var(--accent);color:var(--brand-deep)}
.fc-badge-cat{background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3)}
.fc-
.fc-spk-name{font-weight:700;font-size:.82rem;color:var(--text)}
.fc-spk-role{font-size:.72rem;color:var(--muted);margin-bottom:10px}
.fc-title{font-family:'Sora',sans-serif;font-size:.98rem;font-weight:800;color:var(--text);line-height:1.32;margin-bottom:11px}
.fc-met
.fc-mi{display:flex;align-items:center;gap:4px;font-size:.74rem;color:var(--muted)}
.fc-mi svg{width:12px;height:12px}
.prov-z{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:12px;font-size:.7rem;font-weight:600;background:var(--brand-light);color:var(--brand)}
.countdown{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:14px}
.cd-box{background:var(--brand-deep);border-radius:8px;padding:8px 4px;text-align:center}
.cd-n{font-family:'Sora',sans-serif;font-size:1.3rem;font-weight:800;color:#fff;line-height:1}
.cd-l{font-size:.58rem;color:rgba(255,255,255,.4);font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-top:3px}
.fc-ct
.fc-ct
.fc-note{display:flex;align-items:center;justify-content:center;gap:5px;margin-top:8px;font-size:.72rem;color:var(--muted)}
.fc-note svg{width:13px;height:13px;color:var(--success)}

/* STATS BAND */
.stats-band{background:var(--brand-deep);padding:20px 0}
.stats-band-inner{display:flex;align-items:center;justify-content:center;flex-wrap:wrap}
.sb-item{display:flex;align-items:center;gap:10px;padding:8px 36px;border-right:1px solid rgba(255,255,255,.1)}
.sb-item:last-child{border-right:none}
.sb-icon{width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);flex-shrink:0}
.sb-icon svg{width:18px;height:18px}
.sb-val{font-family:'Sora',sans-serif;font-size:1.15rem;font-weight:800;color:#fff;line-height:1}
.sb-lbl{font-size:.72rem;color:rgba(255,255,255,.5);font-weight:500;margin-top:2px}

/* FILTER */
.filter-wrap{background:var(--white);border-bottom:1px solid var(--border);padding:28px 0 0}
.filter-top{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px}
.filter-top h2{font-size:1.25rem;font-weight:800;color:var(--text)}
.filter-top .count{font-size:.82rem;color:var(--muted)}
.pill-row{display:flex;gap:7px;flex-wrap:wrap}
.pill{padding:7px 16px;border-radius:22px;border:1.5px solid var(--border);background:var(--white);font-size:.78rem;font-weight:600;color:var(--muted);cursor:pointer;transition:var(--transition);font-family:'Inter',sans-serif;min-height:36px}
.pill:hover{border-color:var(--brand);color:var(--brand)}
.pill.on{background:var(--brand);color:var(--white);border-color:var(--brand)}

/* WEBINAR CARDS — course-card style */
.grid-section{padding:32px 0 64px}
.w-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.wb-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;display:flex;flex-direction:column;transition:var(--transition)}
.wb-card:hover{box-shadow:var(--shadow-lg);border-color:var(--brand);transform:translateY(-4px)}
.wb-thumb{height:110px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.wb-thumb-icon{width:40px;height:40px;color:rgba(255,255,255,.85)}
.wb-thumb-av{position:absolute;bottom:-16px;left:16px;width:38px;height:38px;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:.72rem;font-weight:800;color:#fff}
.wb-badge{position:absolute;top:8px;left:8px;font-size:.6rem;font-weight:800;padding:3px 9px;border-radius:20px;letter-spacing:.04em}
.wb-free{background:var(--success);color:#fff}
.wb-prem{background:var(--accent);color:var(--brand-deep)}
.wb-reg{position:absolute;top:8px;right:8px;background:rgba(0,0,0,.4);color:#fff;font-size:.6rem;font-weight:700;padding:3px 8px;border-radius:20px;display:flex;align-items:center;gap:3px}
.wb-reg svg{width:10px;height:10px}
.t-blue{background:linear-gradient(135deg,#0A2F57,#0D609E)}
.t-orange{background:linear-gradient(135deg,#0A2F57,#ED9020)}
.t-green{background:linear-gradient(135deg,#064A85,#16a34a)}
.t-purple{background:linear-gradient(135deg,#064A85,#7c3aed)}
.t-teal{background:linear-gradient(135deg,#0A2F57,#0891b2)}
.t-amber{background:linear-gradient(135deg,#064A85,#d97706)}
.wb-
.wb-cat{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--brand);margin-bottom:8px}
.wb-title{font-family:'Sora',sans-serif;font-size:.92rem;font-weight:800;color:var(--text);line-height:1.35;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.5em}
.wb-spk{font-size:.76rem;color:var(--muted);margin-bottom:12px}
.wb-spk strong{color:var(--text);font-weight:600}
.wb-met
.wb-mi{display:flex;align-items:center;gap:4px}
.wb-mi svg{width:12px;height:12px}
.wb-foot{padding:12px 16px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:8px}
.wb-prov{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:12px}
.wp-z{background:var(--brand-light);color:var(--brand)}
.wp-m{background:#fefce8;color:#92400e}
.wp-t{background:#f3efff;color:#5b21b6}
.wb-foot 

/* WHY ATTEND */
.why-section{background:var(--white);padding:72px 0}
.why-header{text-align:center;margin-bottom:44px}
.why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.why-card{border:1px solid var(--border);border-radius:var(--radius);padding:26px 20px;background:var(--bg);transition:var(--transition)}
.why-card:hover{border-color:var(--brand);box-shadow:var(--shadow);transform:translateY(-3px);background:var(--white)}
.why-icon{width:46px;height:46px;border-radius:12px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.why-icon svg{width:22px;height:22px}
.why-card:hover .why-icon{background:var(--brand);color:var(--white)}
.why-title{font-weight:700;font-size:.92rem;margin-bottom:7px}
.why-desc{font-size:.82rem;color:var(--muted);line-height:1.62}

/* SPEAKERS */
.spk-section{padding:72px 0;background:var(--bg)}
.spk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:36px}
.spk-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:28px 22px;text-align:center;transition:var(--transition)}
.spk-card:hover{box-shadow:var(--shadow-lg);border-color:var(--brand);transform:translateY(-3px)}
.spk-av{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;margin:0 auto 14px;box-shadow:0 0 0 3px var(--white),0 0 0 5px var(--brand)}
.spk-name{font-family:'Sora',sans-serif;font-weight:700;font-size:1rem;color:var(--text);margin-bottom:3px}
.spk-role{font-size:.78rem;color:var(--muted);margin-bottom:12px;line-height:1.5}
.spk-tag{display:inline-flex;align-items:center;gap:5px;background:var(--brand-light);color:var(--brand);font-size:.7rem;font-weight:700;padding:4px 11px;border-radius:20px}
.spk-tag svg{width:12px;height:12px}
.spk-count{font-size:.76rem;color:var(--muted);margin-top:10px}

/* TESTIMONIALS */
.testi-section{background:var(--white);padding:72px 0}
.testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:36px}
.testi-card{background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:26px}
.testi-stars{display:flex;gap:2px;margin-bottom:12px;color:var(--accent)}
.testi-stars svg{width:16px;height:16px}
.testi-q{font-size:.88rem;color:var(--text);line-height:1.75;margin-bottom:18px}
.testi-q::before{content:'\201C';font-size:1.5rem;color:var(--accent);font-family:'Sora',sans-serif;font-weight:800;line-height:.5;margin-right:2px;vertical-align:-.2em}
.testi-author{display:flex;align-items:center;gap:10px}
.testi-av{width:38px;height:38px;border-radius:50%;color:#fff;font-family:'Sora',sans-serif;font-size:.78rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.testi-name{font-weight:700;font-size:.84rem;color:var(--text)}
.testi-role{font-size:.72rem;color:var(--muted)}

/* NEWSLETTER — homepage band */
.nl-band{background:linear-gradient(120deg,var(--brand-light) 0%,#dce9f8 100%);padding:52px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.nl-inner{display:flex;align-items:center;justify-content:space-between;gap:36px;flex-wrap:wrap}
.nl-text{flex:1 1 380px}
.nl-title{font-family:'Sora',sans-serif;font-size:clamp(1.25rem,2.2vw,1.6rem);font-weight:800;line-height:1.2;letter-spacing:-.02em;margin-bottom:8px}
.nl-title span{color:var(--brand)}
.nl-sub{color:var(--muted);font-size:.88rem;max-width:420px;line-height:1.65}
.nl-perks{display:flex;flex-direction:column;gap:7px;margin-top:14px}
.nl-perk{display:flex;align-items:center;gap:8px;font-size:.83rem;color:var(--text)}
.nl-perk svg{width:14px;height:14px;color:var(--success);flex-shrink:0}
.nl-form-wrap{flex:0 1 400px}
.nl-form{display:flex;flex-direction:column;gap:9px}
.nl-field{position:relative;display:flex;align-items:center}
.nl-field svg{position:absolute;left:12px;width:16px;height:16px;color:var(--muted);pointer-events:none}
.nl-field input{width:100%;border:1px solid var(--border);border-radius:8px;padding:11px 14px 11px 36px;font-family:'Inter',sans-serif;font-size:.9rem;color:var(--text);background:var(--white);outline:none;min-height:44px}
.nl-field input:focus{border-color:var(--brand)}
.nl-form 
.nl-form 
.nl-note{font-size:.72rem;color:var(--muted)}

/* CTA — exact homepage */
.cta-section{padding:72px 0}
.dual-ct
.cta-panel{border-radius:12px;padding:44px 32px}
.cta-panel.blue{background:linear-gradient(150deg,#0A2F57,var(--brand));color:var(--white)}
.cta-panel.blue h2, .cta-panel.blue p, .cta-panel.blue li, .cta-panel.blue strong, .cta-panel.blue a { color: var(--white) !important; }
.cta-panel.light{background:var(--white);color:var(--text);border:1px solid var(--border)}
.cta-ic{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.cta-panel.blue .cta-ic{background:rgba(255,255,255,.14);color:var(--white)}
.cta-panel.light .cta-ic{background:var(--brand-light);color:var(--brand)}
.cta-ic svg{width:25px;height:25px}
.cta-panel h2{font-size:1.35rem;font-weight:700;margin-bottom:10px}
.cta-panel p{font-size:.87rem;margin-bottom:22px}
.cta-panel.blue p{opacity:.86}
.cta-panel.light p{color:var(--muted)}
.cta-list{list-style:none;margin-bottom:26px;display:flex;flex-direction:column;gap:9px}
.cta-list li{display:flex;align-items:center;gap:9px;font-size:.85rem}
.cta-list li svg{width:16px;height:16px;flex-shrink:0;color:var(--accent)}

/* EXACT FOOTER — homepage */
.footer{background:#0A2F57;color:rgba(255,255,255,.78);padding:56px 0 0}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:36px;margin-bottom:44px}
.footer-logo{display:flex;align-items:center;text-decoration:none;margin-bottom:14px}
.footer-logo-img{height:52px;width:auto}
.footer-brand p{font-size:.83rem;line-height:1.75;opacity:.78;margin-bottom:18px}
.footer-socials{display:flex;gap:8px;flex-wrap:wrap}
.footer-socials 
.footer-socials a svg{width:17px;height:17px}
.footer-socials 
.footer-col h3{font-family:'Sora',sans-serif;font-size:.78rem;font-weight:700;color:var(--white);text-transform:uppercase;letter-spacing:.07em;margin-bottom:15px}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer-col ul 
.footer-col ul 
.footer-bottom{border-top:1px solid rgba(255,255,255,.1);padding:18px 0;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;font-size:.78rem;color:rgba(255,255,255,.45)}
.footer-bottom 
.footer-bottom 
.footer-links{display:flex;gap:18px;flex-wrap:wrap}
#btt{position:fixed;bottom:max(24px,calc(24px + env(safe-area-inset-bottom,0px)));right:24px;width:46px;height:46px;border-radius:50%;background:var(--brand);color:var(--white);border:none;cursor:pointer;box-shadow:var(--shadow-lg);display:none;align-items:center;justify-content:center;z-index:900;transition:var(--transition)}
#btt svg{width:20px;height:20px}
#btt.show{display:flex}
#btt:hover{background:var(--brand-dark)}

/* RESPONSIVE */
@media(max-width:860px){.dual-cta,.why-grid,.spk-grid,.testi-grid{grid-template-columns:1fr 1fr}.footer-grid{grid-template-columns:1fr 1fr}.hero-inner{grid-template-columns:1fr}.feat-card{transform:none;max-width:420px}.hero-stats{padding-bottom:36px}.w-grid{grid-template-columns:repeat(2,1fr)}.sb-item{padding:8px 20px}}
@media(max-width:580px){.container{padding:0 16px}.footer-grid{grid-template-columns:1fr;gap:24px}.w-grid,.dual-cta,.why-grid,.spk-grid,.testi-grid{grid-template-columns:1fr}.cta-panel{padding:30px 22px}.footer-bottom{flex-direction:column;text-align:center}.footer-links{justify-content:center}.stats-band-inner{flex-direction:column}.sb-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.1);width:100%;justify-content:center}.sb-item:last-child{border-bottom:none}.nl-inner{flex-direction:column;gap:20px}.nl-form-wrap{flex:1 1 auto;width:100%}.nl-band{padding:40px 0}.faq-section{padding:48px 0}.cta-section{padding:48px 0}.why-section,.spk-section,.testi-section{padding:48px 0}.grid-section{padding:24px 0 40px}}
@media(max-width:380px){.container{padding:0 14px}.nav-logo img{height:50px}}
@media(prefers-reduced-motion:reduce){}

/* search + sort row */
.search-sort{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:14px}
.search-box{position:relative;display:flex;align-items:center;flex:1;min-width:220px}
.search-box svg{position:absolute;left:12px;width:16px;height:16px;color:var(--muted);pointer-events:none}
.search-box input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 14px 10px 36px;font-family:'Inter',sans-serif;font-size:.86rem;color:var(--text);background:var(--white);outline:none;min-height:42px}
.search-box input:focus{border-color:var(--brand)}
.sort-select{border:1.5px solid var(--border);border-radius:8px;padding:10px 36px 10px 14px;font-family:'Inter',sans-serif;font-size:.84rem;font-weight:500;color:var(--text);background:var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%235b6577' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center;cursor:pointer;outline:none;min-height:42px;-webkit-appearance:none;appearance:none}
.sort-select:focus{border-color:var(--brand)}
/* view tabs (upcoming / on-demand) */
.view-tabs{display:inline-flex;background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:4px;margin-bottom:18px;gap:2px}
.view-tab{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:7px;border:none;background:transparent;color:var(--muted);font-family:'Inter',sans-serif;font-size:.83rem;font-weight:600;cursor:pointer;transition:var(--transition);min-height:38px}
.view-tab svg{width:15px;height:15px}
.view-tab.active{background:var(--white);color:var(--brand);box-shadow:0 1px 4px rgba(10,47,87,.1)}
/* seat progress */
.wb-seats{padding:0 16px 12px}
.wb-seat-bar{height:5px;border-radius:4px;background:var(--border);overflow:hidden;margin-bottom:5px}
.wb-seat-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--accent),var(--accent-dark));transition:width .6s ease}
.wb-seat-label{font-size:.7rem;color:var(--muted);display:flex;justify-content:space-between}
.wb-seat-label strong{color:var(--accent-dark);font-weight:700}
.wb-seat-label.almost strong{color:#dc2626}
/* live social proof banner */
.live-proof{display:flex;align-items:center;gap:9px;background:var(--brand-light);border:1px solid #cfe0f1;border-radius:10px;padding:9px 14px;margin-bottom:18px;font-size:.8rem;color:var(--brand-dark)}
.live-proof .lp-dot{width:8px;height:8px;border-radius:50%;background:var(--success);flex-shrink:0;animation:pulse 1.6s ease-in-out infinite;box-shadow:0 0 0 3px rgba(22,163,74,.18)}
.live-proof strong{font-weight:700}
.lp-avatars{display:flex;margin-left:auto}
.lp-av{width:24px;height:24px;border-radius:50%;border:2px solid var(--brand-light);margin-left:-8px;font-size:.58rem;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center}
/* empty state */
.empty-state{display:none;text-align:center;padding:48px 20px;color:var(--muted)}
.empty-state svg{width:48px;height:48px;color:var(--border);margin:0 auto 14px}
.empty-state h3{font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:6px}
.empty-state p{font-size:.86rem;max-width:320px;margin:0 auto 16px}
/* calendar mini-link */
.wb-cal{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;color:var(--brand);background:none;border:none;cursor:pointer;padding:0;font-family:'Inter',sans-serif}
.wb-cal svg{width:13px;height:13px}
.wb-cal:hover{color:var(--brand-dark);text-decoration:underline}
.wb-foot-2{padding:0 16px 14px;display:flex;justify-content:flex-end}
/* FAQ */
.faq-section{background:var(--bg);padding:72px 0}
.faq-wrap{max-width:760px;margin:30px auto 0}
.faq-item{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:12px;overflow:hidden;transition:var(--transition)}
.faq-item:hover{border-color:#cfe0f1}
.faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 20px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700;color:var(--text);text-align:left;line-height:1.4}
.faq-q svg{width:18px;height:18px;color:var(--brand);flex-shrink:0;transition:transform .2s}
.faq-item.open .faq-q svg{transform:rotate(45deg)}
.faq-
.faq-a-inner{padding:0 20px 18px;font-size:.86rem;color:var(--muted);line-height:1.7}
.faq-item.open .faq-
/* toast */
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--brand-deep);color:#fff;padding:12px 20px;border-radius:10px;font-size:.85rem;font-weight:600;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:9px;z-index:1200;opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;max-width:90vw}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
.toast svg{width:17px;height:17px;color:#5ee9a0;flex-shrink:0}
@media(max-width:580px){.search-sort{flex-direction:column;align-items:stretch}.sort-select{width:100%}}

/* ===== CONFIRMATION PAGE ===== */
.conf-hero{background:radial-gradient(ellipse 70% 60% at 80% 10%,rgba(237,144,32,.16) 0%,transparent 55%),linear-gradient(160deg,#0A2F57 0%,#064A85 60%,#0D609E 100%);color:#fff;padding:40px 0 92px;position:relative;overflow:hidden}
.conf-hero .hero-grid{position:absolute;inset:0;pointer-events:none;opacity:.4;background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:46px 46px;-webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 20%,#000 30%,transparent 80%);mask-image:radial-gradient(ellipse 90% 80% at 50% 20%,#000 30%,transparent 80%)}
.breadcrumb{position:relative;z-index:1;display:flex;align-items:center;gap:7px;font-size:.78rem;color:rgba(255,255,255,.6);margin-bottom:26px;flex-wrap:wrap}
.breadcrumb 
.breadcrumb 
.breadcrumb svg{width:13px;height:13px;opacity:.5}
.breadcrumb span[aria-current]{color:#fff;font-weight:600}
.conf-head{position:relative;z-index:1;text-align:center;max-width:620px;margin:0 auto}
.conf-tick{width:74px;height:74px;border-radius:50%;background:rgba(22,163,74,.16);border:1px solid rgba(94,233,160,.4);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;animation:tickpop .45s cubic-bezier(.2,.9,.3,1.3) both}
.conf-tick svg{width:38px;height:38px;color:#5ee9a0}
@keyframes tickpop{0%{transform:scale(.5);opacity:0}100%{transform:scale(1);opacity:1}}
.conf-head h1{font-size:clamp(1.7rem,3.4vw,2.5rem);font-weight:800;line-height:1.12;margin-bottom:12px}
.conf-head h1 span{color:var(--accent)}
.conf-head p{font-size:.98rem;color:rgba(255,255,255,.72);line-height:1.65}
.conf-head p strong{color:#fff;font-weight:600}

/* main layout */
.conf-main{padding:0 0 72px;margin-top:-64px;position:relative;z-index:5}
.conf-layout{display:grid;grid-template-columns:1.6fr 1fr;gap:24px;align-items:start}

/* ticket card */
.ticket{background:var(--white);border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-lg);overflow:hidden}
.ticket-top{background:linear-gradient(135deg,#0A2F57,#0D609E);color:#fff;padding:22px 24px;position:relative}
.ticket-top::after{content:'';position:absolute;left:0;right:0;bottom:-11px;height:22px;background:radial-gradient(circle 11px at 11px 50%,transparent 11px,var(--white) 11px) repeat-x;background-size:30px 22px;background-position:-4px 0}
.ticket-status{display:inline-flex;align-items:center;gap:6px;background:rgba(94,233,160,.18);color:#5ee9a0;font-size:.7rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:4px 11px;border-radius:20px;margin-bottom:14px}
.ticket-status svg{width:12px;height:12px}
.ticket-title{font-family:'Sora',sans-serif;font-size:1.3rem;font-weight:800;line-height:1.25;margin-bottom:14px}
.ticket-spk{display:flex;align-items:center;gap:11px}
.ticket-av{width:42px;height:42px;border-radius:50%;border:2px solid rgba(255,255,255,.3);background:var(--accent);color:var(--brand-deep);font-family:'Sora',sans-serif;font-weight:800;font-size:.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ticket-spk-name{font-weight:700;font-size:.86rem}
.ticket-spk-role{font-size:.74rem;color:rgba(255,255,255,.7)}
.ticket-
.ticket-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px 20px;margin-bottom:22px}
.tg-item{display:flex;gap:11px}
.tg-ic{width:38px;height:38px;border-radius:10px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.tg-ic svg{width:18px;height:18px}
.tg-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:2px}
.tg-val{font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4}
.tg-val small{display:block;font-weight:400;color:var(--muted);font-size:.78rem;margin-top:1px}
.prov-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:13px;font-size:.74rem;font-weight:600;background:var(--brand-light);color:var(--brand);margin-top:3px}

/* join link box */
.join-box{background:var(--bg);border:1px dashed var(--border);border-radius:12px;padding:16px;margin-bottom:20px}
.join-box-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:8px;display:flex;align-items:center;gap:6px}
.join-box-label svg{width:14px;height:14px;color:var(--brand)}
.join-link-row{display:flex;gap:8px;align-items:center}
.join-link-input{flex:1;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-family:'Inter',sans-serif;font-size:.82rem;color:var(--text);background:var(--white);min-width:0;overflow:hidden;text-overflow:ellipsis}
.copy-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:8px;background:var(--brand);color:#fff;border:none;font-family:'Inter',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;transition:var(--transition);white-space:nowrap;flex-shrink:0}
.copy-btn:hover{background:var(--brand-dark)}
.copy-btn svg{width:14px;height:14px}
.join-hint{font-size:.75rem;color:var(--muted);margin-top:9px;display:flex;align-items:center;gap:6px}
.join-hint svg{width:13px;height:13px;color:var(--accent);flex-shrink:0}

/* action buttons */
.ticket-actions{display:flex;gap:10px;flex-wrap:wrap}
.ticket-actions 

/* countdown strip */
.cd-strip{background:var(--brand-deep);border-radius:12px;padding:18px;text-align:center;color:#fff;margin-bottom:0}
.cd-strip-label{font-size:.72rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:12px}
.cd-strip-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}
.cd-strip-grid>*,.conf-layout>*,.ticket-grid>*,.reco-grid>*{min-width:0}
.cd-cell{min-width:0;overflow:hidden}
.cd-cell{background:rgba(255,255,255,.07);border-radius:9px;padding:10px 4px}
.cd-cell-n{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;line-height:1}
.cd-cell-l{font-size:.6rem;color:rgba(255,255,255,.5);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-top:4px}

/* side cards */
.side-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:22px;margin-bottom:20px}
.side-card h3{font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.side-card h3 svg{width:18px;height:18px;color:var(--brand)}
.next-list{list-style:none;display:flex;flex-direction:column;gap:14px}
.next-list li{display:flex;gap:12px;font-size:.84rem;color:var(--muted);line-height:1.55}
.next-num{width:24px;height:24px;border-radius:50%;background:var(--brand-light);color:var(--brand);font-size:.74rem;font-weight:800;font-family:'Sora',sans-serif;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.next-list li strong{color:var(--text);font-weight:600}
/* reminder toggles */
.rem-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:11px 0;border-bottom:1px solid var(--border)}
.rem-row:last-child{border-bottom:none;padding-bottom:0}
.rem-info{display:flex;align-items:center;gap:10px}
.rem-ic{width:34px;height:34px;border-radius:9px;background:var(--brand-light);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.rem-ic svg{width:16px;height:16px}
.rem-txt strong{display:block;font-size:.84rem;font-weight:600;color:var(--text)}
.rem-txt span{font-size:.74rem;color:var(--muted)}
/* toggle switch */
.switch{position:relative;width:42px;height:24px;flex-shrink:0}
.switch input{opacity:0;width:0;height:0;position:absolute}
.switch-slider{position:absolute;inset:0;background:var(--border);border-radius:24px;cursor:pointer;transition:var(--transition)}
.switch-slider::before{content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:var(--transition);box-shadow:0 1px 3px rgba(0,0,0,.2)}
.switch input:checked+.switch-slider{background:var(--success)}
.switch input:checked+.switch-slider::before{transform:translateX(18px)}
.switch input:focus-visible+.switch-slider{outline:3px solid var(--accent);outline-offset:2px}

/* calendar buttons */
.cal-btns{display:flex;flex-direction:column;gap:9px}
.cal-btn{display:flex;align-items:center;gap:10px;padding:11px 14px;border:1px solid var(--border);border-radius:9px;background:var(--white);font-family:'Inter',sans-serif;font-size:.84rem;font-weight:600;color:var(--text);cursor:pointer;transition:var(--transition);text-decoration:none}
.cal-btn:hover{border-color:var(--brand);background:var(--brand-light);text-decoration:none}
.cal-btn svg{width:17px;height:17px;color:var(--brand)}
.cal-btn .chev{margin-left:auto;width:15px;height:15px;color:var(--muted)}

/* recommended */
.reco-section{background:var(--white);padding:60px 0;border-top:1px solid var(--border)}
.reco-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px}
.reco-card{display:flex;gap:13px;padding:16px;border:1px solid var(--border);border-radius:12px;transition:var(--transition);text-decoration:none}
.reco-card:hover{border-color:var(--brand);box-shadow:var(--shadow);text-decoration:none;transform:translateY(-2px)}
.reco-thumb{width:52px;height:52px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
.reco-thumb svg{width:22px;height:22px}
.reco-cat{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--brand);margin-bottom:3px}
.reco-title{font-family:'Sora',sans-serif;font-size:.86rem;font-weight:700;color:var(--text);line-height:1.3;margin-bottom:5px}
.reco-met

@media(max-width:860px){.conf-layout{grid-template-columns:1fr}.reco-grid{grid-template-columns:1fr 1fr}}
@media(max-width:580px){.ticket-grid{grid-template-columns:1fr}.reco-grid{grid-template-columns:1fr}.ticket-actions .conf-hero{padding:28px 0 84px}.reco-section{padding:44px 0}}

/* iOS zoom fix: form controls >=16px on mobile (prevents focus auto-zoom) */
@media(max-width:580px){input,select,textare}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="main">

<!-- CONFIRMATION HERO -->
<section class="conf-hero">
  <span class="hero-grid" aria-hidden="true"></span>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= base_url('training/webinars') ?>"/>Webinars</a>
      <svg aria-hidden="true"><use href="#ic-chevr"/></svg>
      <a href="<?= base_url('training/webinars/acing-interview') ?>"/>Acing the Nigerian Corporate Interview</a>
      <svg aria-hidden="true"><use href="#ic-chevr"/></svg>
      <span aria-current="page">Registered</span>
    </nav>
    <div class="conf-head">
      <div class="conf-tick" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>
      <h1>You&rsquo;re <span>registered</span>, {{user_first_name}}!</h1>
      <p>Your seat for <strong>Acing the Nigerian Corporate Interview in 2026</strong> is confirmed. We&rsquo;ve emailed your join link to <strong>{{user_email}}</strong> &mdash; everything you need is below.</p>
    </div>
  </div>
</section>

<!-- MAIN -->
<div class="conf-main"><div class="container">
  <div class="conf-layout">

    <!-- LEFT: ticket -->
    <div>
      <div class="ticket">
        <div class="ticket-top">
          <span class="ticket-status"><svg aria-hidden="true"><use href="#ic-cc"/></svg>Seat confirmed</span>
          <div class="ticket-title">Acing the Nigerian Corporate Interview in 2026</div>
          <div class="ticket-spk">
            <div class="ticket-av">AO</div>
            <div>
              <div class="ticket-spk-name">Dr. Amaka Obi</div>
              <div class="ticket-spk-role">Head of Talent &middot; Access Bank Plc</div>
            </div>
          </div>
        </div>
        <div class="ticket-body">
          <div class="ticket-grid">
            <div class="tg-item"><div class="tg-ic"><svg aria-hidden="true"><use href="#ic-cal"/></svg></div><div><div class="tg-label">Date</div><div class="tg-val">Thursday, 25 June 2026</div></div></div>
            <div class="tg-item"><div class="tg-ic"><svg aria-hidden="true"><use href="#ic-clk"/></svg></div><div><div class="tg-label">Time</div><div class="tg-val">10:00 AM WAT<small>Runs 90 minutes</small></div></div></div>
            <div class="tg-item"><div class="tg-ic"><svg aria-hidden="true"><use href="#ic-video"/></svg></div><div><div class="tg-label">Platform</div><div class="tg-val"><span class="prov-pill">&#x25CF; Zoom</span></div></div></div>
            <div class="tg-item"><div class="tg-ic"><svg aria-hidden="true"><use href="#ic-usr"/></svg></div><div><div class="tg-label">Your ticket</div><div class="tg-val">{{ticket_number}}<small>{{admission_type}}</small></div></div></div>
          </div>

          <div class="join-box">
            <div class="join-box-label"><svg aria-hidden="true"><use href="#ic-video"/></svg>Your join link</div>
            <div class="join-link-row">
              <input class="join-link-input" id="joinlink" value="{{join_url}}" readonly aria-label="Webinar join link">
              <button class="copy-btn" type="button" onclick="copyJoin()"><svg aria-hidden="true"><use href="#ic-copy"/></svg>Copy</button>
            </div>
            <div class="join-hint"><svg aria-hidden="true"><use href="#ic-bell"/></svg>The link goes live 15 minutes before the session. We&rsquo;ll also email it to you on the day.</div>
          </div>

          <div class="ticket-actions">
            <a href="{{join_url}}" class="btn btn-primary btn-lg"><svg aria-hidden="true"><use href="#ic-video"/></svg>Join webinar</a>
            <button class="btn btn-outline btn-lg" type="button" onclick="shareWebinar()"><svg aria-hidden="true"><use href="#ic-share"/></svg>Invite a friend</button>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: sidebar -->
    <div>
      <!-- countdown -->
      <div class="side-card" style="padding:0;border:none;background:transparent;margin-bottom:20px">
        <div class="cd-strip">
          <div class="cd-strip-label">Starts in</div>
          <div class="cd-strip-grid">
            <div class="cd-cell"><div class="cd-cell-n" id="cd-d">05</div><div class="cd-cell-l">Days</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-h">00</div><div class="cd-cell-l">Hrs</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-m">00</div><div class="cd-cell-l">Min</div></div>
            <div class="cd-cell"><div class="cd-cell-n" id="cd-s">00</div><div class="cd-cell-l">Sec</div></div>
          </div>
        </div>
      </div>

      <!-- add to calendar -->
      <div class="side-card">
        <h3><svg aria-hidden="true"><use href="#ic-cal"/></svg>Add to your calendar</h3>
        <div class="cal-btns">
          <a class="cal-btn" href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Acing+the+Nigerian+Corporate+Interview+in+2026&dates=20260625T090000Z/20260625T103000Z&details=Your+JobberRecruit+webinar+with+Dr.+Amaka+Obi.+Join+link+is+in+your+email.&location=Zoom" target="_blank" rel="noopener" onclick="toastCal()"><svg aria-hidden="true"><use href="#ic-cal"/></svg>Google Calendar<svg class="chev" aria-hidden="true"><use href="#ic-chevr"/></svg></a>
          <button class="cal-btn" type="button" onclick="downloadICS()"><svg aria-hidden="true"><use href="#ic-dl"/></svg>Apple / Outlook (.ics)<svg class="chev" aria-hidden="true"><use href="#ic-chevr"/></svg></button>
        </div>
      </div>

      <!-- reminders -->
      <div class="side-card">
        <h3><svg aria-hidden="true"><use href="#ic-bell"/></svg>Reminders</h3>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true"><use href="#ic-mail"/></svg></div><div class="rem-txt"><strong>Email</strong><span>24 hours &amp; 1 hour before</span></div></div>
          <label class="switch"><input type="checkbox" checked aria-label="Email reminders"><span class="switch-slider"></span></label>
        </div>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true"><use href="#ic-bell"/></svg></div><div class="rem-txt"><strong>WhatsApp</strong><span>1 hour before start</span></div></div>
          <label class="switch"><input type="checkbox" checked aria-label="WhatsApp reminders"><span class="switch-slider"></span></label>
        </div>
        <div class="rem-row">
          <div class="rem-info"><div class="rem-ic"><svg aria-hidden="true"><use href="#ic-clk"/></svg></div><div class="rem-txt"><strong>SMS</strong><span>15 minutes before start</span></div></div>
          <label class="switch"><input type="checkbox" aria-label="SMS reminders"><span class="switch-slider"></span></label>
        </div>
      </div>

      <!-- what's next -->
      <div class="side-card">
        <h3><svg aria-hidden="true"><use href="#ic-cc"/></svg>What happens next</h3>
        <ul class="next-list">
          <li><span class="next-num">1</span><div><strong>Check your inbox.</strong> A confirmation with your join link is on its way to {{user_email}}.</div></li>
          <li><span class="next-num">2</span><div><strong>We&rsquo;ll remind you.</strong> You&rsquo;ll get a nudge 24 hours and 1 hour before the session.</div></li>
          <li><span class="next-num">3</span><div><strong>Join live.</strong> Tap the join link 15 minutes early to settle in before the Q&amp;A.</div></li>
          <li><span class="next-num">4</span><div><strong>Get the recording.</strong> Can&rsquo;t make it live? We&rsquo;ll email you the replay afterward.</div></li>
        </ul>
      </div>
    </div>

  </div>
</div></div>

<!-- RECOMMENDED -->
<section class="reco-section" aria-labelledby="reco-h">
  <div class="container">
    <div class="section-label"><svg aria-hidden="true"><use href="#ic-cap"/></svg>You might also like</div>
    <h2 class="section-title" id="reco-h">Keep building <span>momentum</span></h2>
    <p class="section-sub">Based on the session you just registered for, here are more webinars to round out your job search.</p>
    <div class="reco-grid">
      <a class="reco-card" href="<?= base_url('training/webinars/ats-cv') ?>"/>
        <div class="reco-thumb t-orange"><svg aria-hidden="true"><use href="#ic-doc"/></svg></div>
        <div><div class="reco-cat">CV &amp; Resume</div><div class="reco-title">ATS-Proof CV Writing</div><div class="reco-meta">Sat 27 Jun &middot; Free</div></div>
      </a>
      <a class="reco-card" href="<?= base_url('training/webinars/salary-negotiation') ?>"/>
        <div class="reco-thumb t-green"><svg aria-hidden="true"><use href="#ic-coin"/></svg></div>
        <div><div class="reco-cat">Salary</div><div class="reco-title">Know Your Worth: Salary Negotiation</div><div class="reco-meta">Wed 2 Jul &middot; &#x20A6;2,500</div></div>
      </a>
      <a class="reco-card" href="<?= base_url('training/webinars/linkedin-strategy') ?>"/>
        <div class="reco-thumb t-teal"><svg aria-hidden="true"><use href="#ic-chip"/></svg></div>
        <div><div class="reco-cat">LinkedIn</div><div class="reco-title">LinkedIn Strategies for Africans</div><div class="reco-meta">Tue 8 Jul &middot; Free</div></div>
      </a>
    </div>
  </div>
</section>

</main>
<?= $this->endSection() ?>

