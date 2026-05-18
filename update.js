const fs = require('fs');
const urls = JSON.parse(fs.readFileSync('covers.json'));

let html = fs.readFileSync('index.html', 'utf8');
let sql = fs.readFileSync('database_updated.sql', 'utf8');

for (const [title, url] of Object.entries(urls)) {
  if (!url) continue;
  
  // HTML: { id: ..., title: 'Title', author: '...', price: ..., image: '' }
  // Escape regex special chars in title except for the literal quote which we handle
  const safeTitle = title.replace(/'/g, "\\\\'");
  const htmlRegex = new RegExp(`(title: '${safeTitle}'.*?image: )'.*?'`, 'g');
  html = html.replace(htmlRegex, `$1'${url}'`);

  // SQL: ('Title', 'Author', 'Genre', Price, Rating, Pages, 'Color', '', 'Description', Stock)
  const safeTitleSql = title.replace(/'/g, "''"); // SQL escaping
  const sqlRegex = new RegExp(`('\\s*${safeTitleSql}\\s*'.*?'#[a-fA-F0-9]{6}',\\s*)'.*?'`, 'g');
  sql = sql.replace(sqlRegex, `$1'${url}'`);
}

fs.writeFileSync('index.html', html);
fs.writeFileSync('database_updated.sql', sql);
console.log('Files updated successfully.');
