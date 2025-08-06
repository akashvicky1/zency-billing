function addItem() {
  const container = document.getElementById("itemsContainer");
  const div = document.createElement("div");
  div.classList.add("item");
  div.innerHTML = `
    <input type="text" placeholder="Item Name" class="itemName" required />
    <input type="number" placeholder="Qty" class="itemQty" required />
    <input type="number" placeholder="Price" class="itemPrice" required />
  `;
  container.appendChild(div);
}

document.getElementById("invoiceForm").addEventListener("submit", function (e) {
  e.preventDefault();
  generateInvoicePDF();
});
