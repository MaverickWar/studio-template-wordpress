// Quick checks that need no WordPress: theme.json is valid, every template and part has balanced
// block comments with valid attributes, every template part it uses exists, and no colour is typed in.
import { readFileSync, readdirSync, existsSync } from 'node:fs';

let failed = 0;
const fail = (msg) => { console.error('✗', msg); failed++; };

JSON.parse(readFileSync('theme.json', 'utf8'));
if (/#[0-9a-f]{6}\b/i.test(readFileSync('theme.json', 'utf8'))) fail('theme.json has a typed-in colour; colours come from design/tokens.json');

const files = ['templates', 'parts', 'patterns'].flatMap((d) => readdirSync(d).map((f) => `${d}/${f}`));
for (const file of files) {
  const html = readFileSync(file, 'utf8');
  const stack = [];
  for (const m of html.matchAll(/<!--\s+(\/)?wp:([a-z0-9/-]+)(\s+(\{.*?\}))?\s+(\/)?-->/g)) {
    const [, closing, name, , attrs, selfClosing] = m;
    if (attrs) { try { JSON.parse(attrs); } catch { fail(`${file}: bad attributes on wp:${name}`); } }
    if (closing) { if (stack.pop() !== name) fail(`${file}: wp:${name} closed out of order`); }
    else if (!selfClosing) stack.push(name);
    if (name === 'template-part') {
      const slug = JSON.parse(attrs).slug;
      if (!existsSync(`parts/${slug}.html`)) fail(`${file}: template part "${slug}" is missing`);
    }
  }
  if (stack.length) fail(`${file}: wp:${stack.join(', wp:')} never closed`);
  if (/#[0-9a-f]{6}\b/i.test(html)) fail(`${file} has a typed-in colour`);
}
for (const needed of ['style.css', 'functions.php', 'templates/index.html']) if (!existsSync(needed)) fail(`${needed} is missing`);
if (failed) process.exit(1);
console.log(`✓ theme.json and ${files.length} templates, parts and patterns look right`);
