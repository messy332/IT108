// Reports actions: export PDF/CSV
export function exportPDF(){
  // Simple fallback: open print dialog so the user can Save as PDF
  window.print();
}

export function exportCSV(){
  // Export first table on the page as CSV
  const table = document.querySelector('table');
  if(!table){ alert('No table to export'); return; }
  const csv = [];
  for (const row of table.rows){
    const cells = [];
    for (const cell of row.cells){
      const text = (cell.innerText||'').replaceAll('"','""');
      cells.push('"'+text+'"');
    }
    csv.push(cells.join(','));
  }
  const blob = new Blob([csv.join('\n')], {type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = 'report.csv'; a.click();
  URL.revokeObjectURL(url);
}

// Expose globally for onClick buttons if needed
window.exportPDF = exportPDF;
window.exportCSV = exportCSV;
