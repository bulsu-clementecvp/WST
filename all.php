<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AUROVA</title>
  <link rel="stylesheet" href="test.css" />
</head>
<body>
  <div class="navbar">
    <ul class="nav-menu">
      <div class="nav-left">
        <li><a href="loginpage.php"><img src="img/logo.png" alt="Logo" class="logo" /></a></li>
        <li><a href="loginpage.php">AUROVA</a></li>
      </div>
      <div class="nav-right">
        <li><a href="loginpage.php">HOME</a></li>
        <li><a href="all.php">ALL</a></li>
        <li>
          <a href="account.php" class="icons">ACCOUNTS
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="..." />
            </svg>
          </a>
        </li>
      </div>
    </ul>
  </div>

  <button id="cartToggle" class="cart-button" aria-label="Toggle Cart">🛒</button>
  <div id="cartModal" class="cart-modal">
    <h3>Your Cart</h3>
    <table id="cartTable" class="cart-table" border="1">
      <thead>
        <tr>
          <th>Product</th><th>Price</th><th>Quantity</th><th>Size</th><th>Color</th><th>Subtotal</th><th>Action</th>
        </tr>
      </thead>
      <tbody id="cartTableBody">
        <tr><td colspan="7" style="text-align:center;">No items in cart</td></tr>
      </tbody>
    </table>
    <p id="cartTotal">Total: ₱0</p>
    <button class="checkout-btn">Checkout</button>
  </div>

  <div id="search-container" style="padding: 10px; text-align: center;">
    <input type="text" id="productSearch" placeholder="Search products..." style="width: 80%; padding: 8px; font-size: 16px;" />
  </div>

<div id="products-container">
  <h2>Men</h2>
  <div id="men-products" class="product-grid"></div>

  <h2>Women</h2>
  <div id="women-products" class="product-grid"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  fetch('get_categories.php')
    .then(response => response.json())
    .then(categories => {
      const container = document.getElementById('products-container');

      categories.forEach(category => {
     
        title.textContent = category;

        const section = document.createElement('div');
        section.id = `${category.toLowerCase()}-products`;
        section.className = 'product-grid';

        container.appendChild(title);
        container.appendChild(section);
      });
    })
    .catch(error => {
      console.error('Error loading dynamic categories:', error);
    });
});
</script>

  <div id="productModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div class="modal-content" style="background:#fff; padding:20px; max-width:400px; width:90%; position:relative;">
      <span class="close" style="position:absolute; top:10px; right:15px; font-size:28px; cursor:pointer;">&times;</span>
      <img id="modalImage" src="" alt="" style="width:100%; height:auto; margin-bottom:10px;" />
      <h2 id="modalTitle"></h2>
      <p id="modalPrice"></p>
      <p id="modalQuantity" style="color: #666;"></p>

      <label for="size">Size:</label>
      <select id="size" name="size">
        <option value="S">Small</option>
        <option value="M">Medium</option>
        <option value="L">Large</option>
        <option value="XL">Extra Large</option>
        <option value="2XL">2XL</option>
      </select>
      <br /><br />
      <label for="color">Color:</label>
      <select id="color" name="color">
        <option value="Red">Red</option>
        <option value="Black">Black</option>
        <option value="White">White</option>
      </select>
      <br /><br />
      <div>
        <button id="qty-minus">-</button>
        <input id="quantity" type="number" value="1" min="1" style="width:40px; text-align:center;" />
        <button id="qty-plus">+</button>
      </div>
      <br />
      <button id="addToCartBtn">Add to Cart</button>
    </div>
  </div>

  <script>
 function loadProducts() {
  fetch("products.xml")
    .then(res => res.text())
    .then(str => new window.DOMParser().parseFromString(str, "text/xml"))
    .then(data => {
      const container = document.getElementById("products-container");
      container.innerHTML = "";

      const products = data.getElementsByTagName("product");
      const categoryMap = {};

      for (let i = 0; i < products.length; i++) {
        const product = products[i];
        const name = product.getElementsByTagName("name")[0]?.textContent ?? "N/A";
        const category = product.getElementsByTagName("category")[0]?.textContent ?? "Uncategorized";
        const price = product.getElementsByTagName("price")[0]?.textContent ?? "0.00";
        const photo = product.getElementsByTagName("photo")[0]?.textContent ?? "";
        const quantity = product.getElementsByTagName("quantity")[0]?.textContent ?? "N/A";

        const card = document.createElement("div");
        card.className = "product-card";
        card.innerHTML = `
          <img src="${photo}" alt="${name}" />
          <h3>${name}</h3>
          <p>₱${price}</p>
          <button class="view-more-btn"
            data-name="${name}"
            data-price="₱${price}"
            data-photo="${photo}"
            data-quantity="${quantity}"
          >View More</button>
        `;

        if (!categoryMap[category]) {
          categoryMap[category] = [];
        }
        categoryMap[category].push(card);
      }

      for (const [catName, cards] of Object.entries(categoryMap)) {
        const section = document.createElement("div");
        section.className = "category-section";
        section.innerHTML = `<h2>${catName}</h2>`;

        const grid = document.createElement("div");
        grid.className = "product-grid";
        cards.forEach(card => grid.appendChild(card));

        section.appendChild(grid);
        container.appendChild(section);
      }

      document.querySelectorAll(".view-more-btn").forEach(button => {
        button.addEventListener("click", function () {
          openModal(
            this.getAttribute("data-name"),
            this.getAttribute("data-price"),
            this.getAttribute("data-photo"),
            this.getAttribute("data-quantity")
          );
        });
      });
    })
    .catch(error => {
      console.error("Error loading products:", error);
    });
}



    let cart = [];

    const cartToggleBtn = document.getElementById("cartToggle");
    const cartModal = document.getElementById("cartModal");
    const cartTableBody = document.getElementById("cartTableBody");
    const cartTotalElem = document.getElementById("cartTotal");
    const checkoutBtn = document.querySelector(".checkout-btn");

    const productModal = document.getElementById("productModal");
    const modalCloseBtn = productModal.querySelector(".close");
    const modalImage = document.getElementById("modalImage");
    const modalTitle = document.getElementById("modalTitle");
    const modalPrice = document.getElementById("modalPrice");
    const modalQuantity = document.getElementById("modalQuantity");
    const sizeSelect = document.getElementById("size");
    const colorSelect = document.getElementById("color");
    const qtyMinusBtn = document.getElementById("qty-minus");
    const qtyPlusBtn = document.getElementById("qty-plus");
    const quantityInput = document.getElementById("quantity");
    const addToCartBtn = document.getElementById("addToCartBtn");

    let currentProduct = null;

    cartToggleBtn.addEventListener("click", () => cartModal.classList.toggle("open"));
    modalCloseBtn.addEventListener("click", () => productModal.style.display = "none");

    qtyMinusBtn.addEventListener("click", () => {
      let qty = parseInt(quantityInput.value);
      if (qty > 1) quantityInput.value = qty - 1;
    });

    qtyPlusBtn.addEventListener("click", () => {
      let qty = parseInt(quantityInput.value);
      quantityInput.value = qty + 1;
    });

    function openModal(name, priceText, photo, quantity) {
      modalTitle.textContent = name;
      modalPrice.textContent = priceText;
      modalImage.src = photo;
      modalQuantity.textContent = `Available: ${quantity}`;
      quantityInput.value = 1;
      sizeSelect.selectedIndex = 0;
      colorSelect.selectedIndex = 0;

      currentProduct = {
        name,
        price: parseFloat(priceText.replace("₱", "")),
        photo,
      };

      productModal.style.display = "flex";
    }

    addToCartBtn.addEventListener("click", () => {
      if (!currentProduct) return;
      const qty = parseInt(quantityInput.value);
      const size = sizeSelect.value;
      const color = colorSelect.value;

      const index = cart.findIndex(item =>
        item.name === currentProduct.name &&
        item.size === size &&
        item.color === color
      );

      if (index > -1) {
        cart[index].quantity += qty;
      } else {
        cart.push({
          name: currentProduct.name,
          price: currentProduct.price,
          photo: currentProduct.photo,
          quantity: qty,
          size,
          color,
        });
      }

      renderCart();
      productModal.style.display = "none";
    });

    function renderCart() {
      if (cart.length === 0) {
        cartTableBody.innerHTML = `<tr><td colspan="7" style="text-align:center;">No items in cart</td></tr>`;
        cartTotalElem.textContent = "Total: ₱0";
        checkoutBtn.disabled = true;
        return;
      }

      cartTableBody.innerHTML = "";
      let total = 0;
      cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td><img src="${item.photo}" class="cart-item-image" alt="${item.name}" /> ${item.name}</td>
          <td>₱${item.price.toFixed(2)}</td>
          <td>${item.quantity}</td>
          <td>${item.size}</td>
          <td>${item.color}</td>
          <td>₱${subtotal.toFixed(2)}</td>
          <td><button class="remove-btn" data-index="${index}">Remove</button></td>
        `;
        cartTableBody.appendChild(tr);
      });

      cartTotalElem.textContent = `Total: ₱${total.toFixed(2)}`;
      checkoutBtn.disabled = false;

      document.querySelectorAll(".remove-btn").forEach(btn => {
        btn.addEventListener("click", e => {
          const idx = parseInt(e.target.getAttribute("data-index"));
          cart.splice(idx, 1);
          renderCart();
        });
      });
    }

    checkoutBtn.addEventListener("click", () => {
      if (cart.length > 0) {
        localStorage.setItem("cartData", JSON.stringify(cart));
        window.location.href = "checkout.php";
      }
    });

    document.getElementById("productSearch").addEventListener("input", () => {
      const filter = document.getElementById("productSearch").value.toLowerCase();
      document.querySelectorAll(".product-card").forEach(card => {
        const productName = card.querySelector("h3").textContent.toLowerCase();
        card.style.display = productName.includes(filter) ? "" : "none";
      });
    });

    window.onload = loadProducts;
    localStorage.removeItem("cartData");
  </script>
</body>
</html>