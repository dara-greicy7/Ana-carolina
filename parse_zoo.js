const fs = require('fs');
const sql = fs.readFileSync('public_html/conative_db.sql', 'utf8');

const regex = /INSERT INTO `tbl_zoo_item` \([^)]+\) VALUES\s+(.*);/g;
let match;
let count = 0;

while ((match = regex.exec(sql)) !== null) {
  const valuesString = match[1];
  // Split values string by `),(` and iterate. This is fragile but works for simple dumps.
  // A better way is to just match `('...', '...', ...)`
  const itemsRegex = /\(([^)]+)\)/g;
  let itemMatch;
  while ((itemMatch = itemsRegex.exec(valuesString)) !== null) {
    const fields = itemMatch[1].split(/','|,(?=(?:[^']*'[^']*')*[^']*$)/);
    // Usually zoo items have id, application_id, type, name, alias, elements, ...
    if (fields.length > 3) {
      console.log(`- Item Name: ${fields[3]}`);
      count++;
    }
  }
}

console.log(`Total ZOO articles found: ${count}`);
