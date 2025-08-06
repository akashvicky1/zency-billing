function generateInvoicePDF() {
  const name = document.getElementById("customerName").value;
  const mobile = document.getElementById("customerMobile").value;
  const address = document.getElementById("customerAddress").value;

  const items = document.querySelectorAll(".item");
  let total = 0;
  let itemRows = "";

  items.forEach((row) => {
    const item = row.querySelector(".itemName").value;
    const qty = parseFloat(row.querySelector(".itemQty").value);
    const price = parseFloat(row.querySelector(".itemPrice").value);
    const amount = qty * price;
    total += amount;

    itemRows += `${item} - ${qty} x ₹${price} = ₹${amount}\n`;
  });

  const doc = new jsPDF();
  doc.setFontSize(16);
  doc.text("Zency Creation", 20, 20);
  doc.setFontSize(12);
  doc.text(`Customer: ${name}`, 20, 30);
  doc.text(`Mobile: ${mobile}`, 20, 38);
  doc.text(`Address: ${address}`, 20, 46);
  doc.text("Items:", 20, 56);
  doc.text(itemRows, 20, 66);
  doc.text(`Total: ₹${total}`, 20, 150);

  const filename = `${name.replace(/\s+/g, "_")}_Invoice.pdf`;
  doc.save(filename);

  // EmailJS
  emailjs.send("service_6qag525", "template_i33jgg6", {
    customer_name: name,
    customer_mobile: mobile,
    customer_address: address,
    total_amount: total,
    invoice_id: `INV${Date.now()}`,
  }).then(() => {
    alert("Invoice emailed successfully!");
  });
}
