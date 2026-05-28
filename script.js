let cartCount = 0;
const cartDisplay = document.getElementById("cart-count");
const buttons = document.querySelectorAll(".add-to-cart");

buttons.forEach(btn => {
  btn.addEventListener("click", () => {
    cartCount++;
    cartDisplay.textContent = cartCount;
    alert("Item added to cart!");
  });
});
