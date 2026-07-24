const fs = require('fs');
const sql = fs.readFileSync('public_html/conative_db.sql', 'utf8');

const regex = /INSERT INTO `tbl_content` \([^)]+\) VALUES\s+(.*);/g;
let match;
let count = 0;

while ((match = regex.exec(sql)) !== null) {
  const valuesString = match[1];
  console.log("Found an insert into tbl_content. Extracting items...");
  // Split values string by `),(` and iterate
  const rows = valuesString.split(/\),\(/);
  for (let row of rows) {
    const fields = row.split(/','/);
    if (fields.length > 3) {
      console.log(`- Title: ${fields[2]}`);
      count++;
    }
  }
}

console.log(`Total articles found: ${count}`);
