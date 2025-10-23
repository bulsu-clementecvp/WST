function loadProducts() {
  fetch('products.xml')
    .then(response => response.text())
    .then(str => (new window.DOMParser()).parseFromString(str, "text/xml"))
    .then(data => {
      const menContainer = document.getElementById('men-products');
      const womenContainer = document.getElementById('women-products');
      menContainer.innerHTML = '';
      womenContainer.innerHTML = '';

      const products = data.getElementsByTagName('product');
      for (let i = 0; i < products.length; i++) {
        const name = products[i].getElementsByTagName('name')[0].textContent;
        const category = products[i].getElementsByTagName('category')[0].textContent;
        const price = products[i].getElementsByTagName('price')[0].textContent;
        const photo = products[i].getElementsByTagName('photo')[0].textContent;

        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
          <img src="${photo}" alt="${name}" />
          <h3>${name}</h3>
          <p>₱${price}</p>
          <button class="view-more-btn"
            data-name="${name}"
            data-price="₱${price}"
            data-photo="${photo}"
          >View More</button>
        `;

        if (category === 'Men') {
          menContainer.appendChild(card);
        } else if (category === 'Women') {
          womenContainer.appendChild(card);
        }
      }

      document.querySelectorAll('.view-more-btn').forEach(button => {
        button.addEventListener('click', function () {
          const name = this.getAttribute('data-name');
          const price = this.getAttribute('data-price');
          const photo = this.getAttribute('data-photo');
          openModal(name, price, photo);
        });
      });
    });
}
window.onload = loadProducts;


let cart = [];

const cartToggleBtn = document.getElementById('cartToggle');
const cartModal = document.getElementById('cartModal');
const cartItemsElem = document.getElementById('cartItems');
const cartTotalElem = document.getElementById('cartTotal');
const checkoutBtn = document.querySelector('.checkout-btn');

const productModal = document.getElementById('productModal');
const modalCloseBtn = productModal.querySelector('.close');
const modalImage = document.getElementById('modalImage');
const modalTitle = document.getElementById('modalTitle');
const modalPrice = document.getElementById('modalPrice');
const sizeSelect = document.getElementById('size');
const colorSelect = document.getElementById('color');
const qtyMinusBtn = document.getElementById('qty-minus');
const qtyPlusBtn = document.getElementById('qty-plus');
const quantityInput = document.getElementById('quantity');
const addToCartBtn = document.getElementById('addToCartBtn');

let currentProduct = null;

cartToggleBtn.addEventListener('click', () => {
  cartModal.style.display = (cartModal.style.display === 'block') ? 'none' : 'block';
});

modalCloseBtn.addEventListener('click', () => {
  productModal.style.display = 'none';
  currentProduct = null;
});

qtyMinusBtn.addEventListener('click', () => {
  let qty = parseInt(quantityInput.value);
  if (qty > 1) quantityInput.value = qty - 1;
});
qtyPlusBtn.addEventListener('click', () => {
  let qty = parseInt(quantityInput.value);
  quantityInput.value = qty + 1;
});

function openModal(name, priceText, photo) {
  modalTitle.textContent = name;
  modalPrice.textContent = priceText;
  modalImage.src = photo;
  quantityInput.value = 1;
  sizeSelect.selectedIndex = 0;
  colorSelect.selectedIndex = 0;

  currentProduct = {
    name,
    price: parseFloat(priceText.replace('₱', '')),
    photo
  };

  productModal.style.display = 'flex';
}

addToCartBtn.addEventListener('click', () => {
  if (!currentProduct) return alert('No product selected.');

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
      color
    });
  }

  renderCart();
  productModal.style.display = 'none';
  alert('Item added to cart!');
});

function renderCart() {
  if (cart.length === 0) {
    cartItemsElem.innerHTML = '<li>No items in cart</li>';
    cartTotalElem.textContent = 'Total: ₱0';
    checkoutBtn.disabled = true;
    return;
  }

  checkoutBtn.disabled = false;
  cartItemsElem.innerHTML = '';
  let total = 0;

  cart.forEach(item => {
    const li = document.createElement('li');
    li.textContent = `${item.name} - ₱${item.price.toFixed(2)} x${item.quantity} (Size: ${item.size}, Color: ${item.color})`;
    cartItemsElem.appendChild(li);
    total += item.price * item.quantity;
  });

  cartTotalElem.textContent = `Total: ₱${total.toFixed(2)}`;
}

checkoutBtn.addEventListener('click', () => {
  if (cart.length === 0) {
    alert('Your cart is empty!');
    return;
  }
  alert('Transaction complete! Thank you for your purchase.');
  cart = [];
  renderCart();
  cartModal.style.display = 'none';
});

window.onload = () => {
  renderCart();
};

window.onclick = (event) => {
  if (event.target === productModal) {
    productModal.style.display = 'none';
    currentProduct = null;
  }
};