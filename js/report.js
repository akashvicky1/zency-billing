function filter(type) {
  // Dummy report data
  const totalInvoices = 12;
  const totalAmount = 15600;

  document.getElementById("reportResult").innerHTML = `
    <p>Total Invoices (${type}): ${totalInvoices}</p>
    <p>Total Sales: ₹${totalAmount}</p>
  `;
}
