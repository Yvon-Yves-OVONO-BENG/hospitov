
function imprimerTableau() {
  const contenu = document.getElementById('basicExample').outerHTML;
  const style = `
    <style>
      table {
        width: 100%;
        border-collapse: collapse;
      }
      table, th, td {
        border: 1px solid black;
      }
    </style>
  `;
  
  const win = window.open('', '', 'height=700,width=900');
  win.document.write('<html><head><title>Impression</title>');
  win.document.write(style);
  win.document.write('</head><body>');
  win.document.write(contenu);
  win.document.write('</body></html>');
  win.document.close();
  win.focus();
  win.print();
  win.close();
}
