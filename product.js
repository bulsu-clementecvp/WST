
let cart = [];


const cartToggle = document.getElementById('cartToggle');
const cartModal = document.getElementById('cartModal');
const cartItemsContainer = document.getElementById('cartItems');
const cartTotalElem = document.getElementById('cartTotal') || createCartTotal();
const addToCartBtn = document.getElementById('addToCartBtn');

let currentProduct = {}; 
function createCartTotal() {
  const p = document.createElement('p');
  p.id = 'cartTotal';
  p.textContent = 'Total: ₱0';
  cartModal.insertBefore(p, cartModal.querySelector('.checkout-btn'));
  return p;
}

function openModal(title, price, image) {
  currentProduct = {
    title,
    price: parseFloat(price.replace('₱', '')),
    image,
  };
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalPrice').textContent = price;
  document.getElementById('modalImage').src = image;

  document.getElementById('productModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('productModal').style.display = 'none';
}


cartToggle.addEventListener('click', () => {
  cartModal.classList.toggle('open');
  renderCart();
});


addToCartBtn.addEventListener('click', () => {

  const size = document.getElementById('size').value;
  const color = document.getElementById('color').value;

 
  const productKey = `${currentProduct.title}-${size}-${color}`;

  const existingItem = cart.find(item => item.key === productKey);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      key: productKey,
      title: currentProduct.title,
      price: currentProduct.price,
      image: currentProduct.image,
      size,
      color,
      quantity: 1
    });
  }

  renderCart();
  closeModal();
});


function renderCart() {
  cartItemsContainer.innerHTML = '';

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = '<li>No items in cart</li>';
    cartTotalElem.textContent = 'Total: ₱0';
    return;
  }

  let total = 0;

  cart.forEach((item, index) => {
    total += item.price * item.quantity;

    const li = document.createElement('li');
    li.style.marginBottom = '10px';
    li.innerHTML = `
      <img src="${item.image}" alt="${item.title}" style="width:50px; height:auto; vertical-align: middle; margin-right: 10px;">
      <strong>${item.title}</strong><br>
      Size: ${item.size}, Color: ${item.color}<br>
      ₱${item.price.toFixed(2)} x ${item.quantity}
      <button class="remove-btn" data-index="${index}" style="margin-left: 10px;">Remove</button>
    `;
    cartItemsContainer.appendChild(li);
  });

  cartTotalElem.textContent = `Total: ₱${total.toFixed(2)}`;


  document.querySelectorAll('.remove-btn').forEach(button => {
    button.addEventListener('click', () => {
      const idx = parseInt(button.getAttribute('data-index'));
      cart.splice(idx, 1);
      renderCart();
    });
  });
}
