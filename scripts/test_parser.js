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
  return values;
}

const test = "1, 2, 'type', 'name', 'alias', '2021-04-15 18:28:16', '2021-06-14 23:42:49', 939, '2021-04-15 18:28:16', '0000-00-00 00:00:00', 0, 132, 1, 1, 939, '', 1, '{\"key\": \"val, val\"}', '{}'";
const results = parseSqlValues(test);
console.log('Length:', results.length);
console.log('Last element:', results[results.length-1]);
console.log('Elements element:', results[results.length-2]);
