const fs = require('fs');
const path = 'css/style.css';
let css = fs.readFileSync(path, 'utf8');

const blocks = [
  { start: '/* ==== Popular Category Section ==== */', end: '/* ==== Featured Job Section ==== */' },
  { start: '/* ==== Featured Job Section ==== */', end: '/* ===== Top Companies Section ===== */' },
  { start: '/* ===== Top Companies Section ===== */', end: '/* === Partners Section === */' },
  { start: '/* ===== Testimonials ===== */', end: '/* ==== Become Section ==== */' },
];

for (const b of blocks) {
  const s = css.indexOf(b.start);
  const e = css.indexOf(b.end, s + b.start.length);
  if (s !== -1 && e !== -1) {
    const before = css.substring(0, s);
    const after = css.substring(e);
    css = before + '\n' + after;
    console.log(`OK: ${b.start}`);
  } else {
    console.log(`MISS: ${b.start} (s=${s} e=${e})`);
  }
}

fs.writeFileSync(path, css);
console.log('Done');
