const fs = require('fs');
const path = require('path');

const SEARCH_INDEX_FILE = 'scripts/search_index.sql';
const RAW_ITEMS_FILE = 'scripts/raw_items.sql';
const OUTPUT_FILE = 'data/content.json';
const PUBLIC_DIR = 'public';

const MAPPINGS = {
  blog: {
    title: '0d42b245-e62d-4698-bc70-982c70075cb1',
    summary: '1dd20d86-519c-493b-beed-6a8f0c1763ca',
    content: 'd92edfa0-7aa0-4df8-80dc-6312ca0de022'
  },
  secciones: {
    content: '42b41bf8-dbd2-4ede-bdc9-7c46cfb9d8fe'
  },
  servicios: {
    title: '5942fb97-e701-4488-ada8-6624f0f4fef9',
    summary: '7512ab32-ee4f-44ab-994c-509676fcde27',
    content: 'cd735a40-b6db-406f-8aef-f8d4dadce41e'
  },
  media: {
    title: '1ce388d3-6d52-4968-95e2-92b3e93f7bff'
  },
  slideshow: {
    title: '0e2813a9-dfed-4eb6-86f2-49a62e00997e'
  }
};

function parseSqlValues(row) {
  const values = [];
  let current = '';
  let inString = false;
  let escape = false;

  for (let i = 0; i < row.length; i++) {
    const char = row[i];
    if (escape) {
      current += char;
      escape = false;
    } else if (char === '\\') {
      current += char;
      escape = true;
    } else if (char === "'") {
      inString = !inString;
      current += char;
    } else if (char === ',' && !inString) {
      values.push(current.trim());
      current = '';
    } else {
      current += char;
    }
  }
  values.push(current.trim());
  return values.map(v => {
    v = v.trim();
    if (v.startsWith("'") && v.endsWith("'")) return v.slice(1, -1);
    return v;
  });
}

function parseIndexRows() {
  const sql = fs.readFileSync(SEARCH_INDEX_FILE, 'utf8');
  const rows = [];
  let currentRow = '';
  let depth = 0;
  let inString = false;
  let escape = false;

  const lines = sql.split('\n');
  for (let line of lines) {
    if (line.startsWith('INSERT')) continue;
    for (let char of line) {
        if (escape) { currentRow += char; escape = false; continue; }
        if (char === '\\') { currentRow += char; escape = true; continue; }
        if (char === "'") { inString = !inString; currentRow += char; continue; }
        if (char === '(' && !inString) { depth++; if (depth === 1) { currentRow = ''; continue; } }
        if (char === ')' && !inString) {
          depth--;
          if (depth === 0) {
            const vals = parseSqlValues(currentRow);
            if (vals.length >= 3) {
                rows.push({ 
                    item_id: vals[0], 
                    element_id: vals[1], 
                    value: vals[2] 
                });
            }
            continue;
          }
        }
        if (depth > 0) currentRow += char;
    }
    if (depth > 0) currentRow += '\n';
  }
  return rows;
}

function getItemsFromRaw() {
    const sql = fs.readFileSync(RAW_ITEMS_FILE, 'utf8');
    const items = [];
    let currentRow = '';
    let depth = 0;
    let inString = false;
    let escape = false;

    const lines = sql.split('\n');
    for (let line of lines) {
      if (line.startsWith('INSERT')) continue;
      for (let char of line) {
          if (escape) { currentRow += char; escape = false; continue; }
          if (char === '\\') { currentRow += char; escape = true; continue; }
          if (char === "'") { inString = !inString; currentRow += char; continue; }
          if (char === '(' && !inString) { depth++; if (depth === 1) { currentRow = ''; continue; } }
          if (char === ')' && !inString) {
            depth--;
            if (depth === 0) {
              const vals = parseSqlValues(currentRow);
              if (vals.length >= 4) {
                items.push({
                    id: vals[0],
                    type: vals[2],
                    name: vals[3],
                    raw: currentRow
                });
              }
              continue;
            }
          }
          if (depth > 0) currentRow += char;
      }
      if (depth > 0) currentRow += '\n';
    }
    return items;
}

const indexRows = parseIndexRows();
const rawItems = getItemsFromRaw();

const result = {
  secciones: [],
  servicios: [],
  media: [],
  slideshow: [],
  blog: []
};

rawItems.forEach(item => {
  const mapping = MAPPINGS[item.type];
  if (!mapping) return;

  const data = {
    id: item.id,
    type: item.type,
    title: item.name,
    alias: item.name.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-'),
    content: '',
    image: ''
  };

  // Get text from index
  const itemTexts = indexRows.filter(r => r.item_id === item.id);
  
  if (mapping.title) {
      const t = itemTexts.find(r => r.element_id === mapping.title);
      if (t) data.title = t.value.replace(/\\r/g, '').replace(/\\n/g, ' ').replace(/\\t/g, ' ').trim();
  }

  if (mapping.content) {
      const c = itemTexts.find(r => r.element_id === mapping.content);
      if (c) data.content = c.value.replace(/\\r\\n/g, '\n').replace(/\\n/g, '\n').replace(/\\r/g, '\n').replace(/\\t/g, '\t').replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\\\/g, '\\');
  } else if (mapping.summary) {
      const s = itemTexts.find(r => r.element_id === mapping.summary);
      if (s) data.content = s.value.replace(/\\r\\n/g, '\n').replace(/\\n/g, '\n').replace(/\\r/g, '\n').replace(/\\t/g, '\t').replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\\\/g, '\\');
  }

  // Get image from raw string
  const imgRegex = /images\\\\\/[^"]*/g;
  const matches = item.raw.match(imgRegex);
  if (matches) {
      let imgPath = matches[0]
        .replace(/\\\\/g, '/')
        .replace(/\\\//g, '/')
        .replace(/\/+/g, '/')
        .replace('images/', '')
        .replace(/[\\"]+$/, ''); // Clean trailing backslashes or quotes
      
      const fullPath = path.join(PUBLIC_DIR, 'images', 'legacy', imgPath);
      if (fs.existsSync(fullPath) && fs.lstatSync(fullPath).isDirectory()) {
          const files = fs.readdirSync(fullPath);
          const firstImg = files.find(f => f.toLowerCase().endsWith('.jpg') || f.toLowerCase().endsWith('.png') || f.toLowerCase().endsWith('.jpeg'));
          if (firstImg) imgPath += '/' + firstImg;
      }

      data.image = '/images/legacy/' + imgPath;
      
      if (item.type === 'media') {
          data.galleryPath = '/images/legacy/' + imgPath.split('/')[0];
      }
  }

  if (result[item.type]) {
    result[item.type].push(data);
  }
});

fs.writeFileSync(OUTPUT_FILE, JSON.stringify(result, null, 2));
console.log(`Successfully extracted ${Object.values(result).flat().length} items to ${OUTPUT_FILE}`);
