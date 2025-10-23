<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout</title>
  <style>
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    img { width: 50px; }

    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background: #f9f9f9;
      color: #222;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 700;
      letter-spacing: 1px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 12px 15px;
      text-align: center;
    }

    th {
      background: #000;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    img {
      width: 50px;
      height: auto;
      vertical-align: middle;
      margin-right: 10px;
      border-radius: 4px;
    }

    #totalPrice {
      text-align: right;
      font-size: 1.3em;
      font-weight: 700;
      margin-bottom: 40px;
    }

    button {
      padding: 12px 25px;
      font-size: 1em;
      cursor: pointer;
      border-radius: 4px;
      border: 2px solid transparent;
      transition: background-color 0.3s, color 0.3s;
      margin-right: 10px;
      user-select: none;
    }

    #confirmBtn {
      background-color: #000;
      color: #fff;
      border-color: #000;
    }

    #confirmBtn:hover {
      background-color: #fff;
      color: #000;
      border-color: #000;
    }

    #backBtn {
      background-color: #fff;
      color: #000;
      border: 2px solid #000;
    }

    #backBtn:hover {
      background-color: #000;
      color: #fff;
      border-color: #000;
    }

    .address-section {
      background: #fff;
      color: #000;
      border: 2px solid #000;
      padding: 20px;
      margin: 30px 0;
      border-radius: 8px;
      box-shadow: 2px 2px 0 #000;
      max-width: 600px;
    }

    .address-section h2 {
      font-size: 1.4em;
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: bold;
    }

    .address-section label {
      display: block;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .address-section select {
      width: 100%;
      padding: 10px;
      border: 2px solid #000;
      background: #fff;
      color: #000;
      font-size: 1em;
      border-radius: 4px;
      margin-bottom: 20px;
      cursor: pointer;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-image: url('data:image/svg+xml;utf8,<svg fill="%23000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 16px;
      transition: border-color 0.3s;
    }

    .address-section select:focus {
      outline: none;
      border-color: #555;
      box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    .payment-method {
      margin-bottom: 30px;
      max-width: 600px;
      background: #fff;
      border: 2px solid #000;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 2px 2px 0 #000;
    }
    .payment-method h2 {
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .payment-method label {
      display: block;
      font-weight: normal;
      margin-bottom: 10px;
      cursor: pointer;
      font-size: 1.1em;
    }
    .payment-method input[type="radio"] {
      margin-right: 10px;
      cursor: pointer;
    }

    .modal {
      position: fixed;
      z-index: 9999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 30px 40px;
      border-radius: 8px;
      max-width: 400px;
      text-align: center;
      position: relative;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      font-size: 1.2em;
    }

    .close {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 28px;
      font-weight: bold;
      color: #333;
      cursor: pointer;
      user-select: none;
    }

    .close:hover {
      color: black;
    }
  </style>
</head>
<body>

  <h1>Order Details</h1>
  <table id="orderTable">
    <thead>
      <tr>
        <th>Product</th>
        <th>Size</th>
        <th>Color</th>
        <th>Quantity</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
  <h3 id="totalPrice">Total: ₱0.00</h3>

  <div class="payment-method">
    <h2>Payment Method</h2>
    <label><input type="radio" name="payment" value="gcash" /> GCash</label>
    <label><input type="radio" name="payment" value="paypal" /> PayPal</label>
    <label><input type="radio" name="payment" value="cod" /> Cash on Delivery (COD)</label>
  </div>

    <div class="address-section">
    <h2>Shipping Address</h2>
    <label for="addressSelect">Choose address:</label>
    <select id="addressSelect">
      <option value="">Loading...</option>
    </select>
    <div class="address-details" id="addressDetails"></div>
  </div>

  <button id="backBtn" onclick="window.history.back()">Back</button>    
  <button id="confirmBtn">Confirm Checkout</button>


  <div id="paymentModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <p>Payment complete. Please wait for confirmation.</p>
    </div>
  </div>

  <script>
const cartData = localStorage.getItem('cartData');
const cart = cartData ? JSON.parse(cartData) : [];
const tbody = document.querySelector('#orderTable tbody');
const totalPriceEl = document.getElementById('totalPrice');
const confirmBtn = document.getElementById('confirmBtn');

function formatPrice(num) {
  return '₱' + num.toFixed(2);
}

if (cart.length === 0) {
  tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Your cart is empty.</td></tr>`;
  totalPriceEl.textContent = 'Total: ₱0.00';
  confirmBtn.disabled = true;
} else {
  let total = 0;
  tbody.innerHTML = '';

  cart.forEach(item => {
    const price = parseFloat(item.price);
    const quantity = parseInt(item.quantity);
    const subtotal = price * quantity;
    total += subtotal;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <img src="${item.photo}" alt="${item.name}" />
        ${item.name}
      </td>
      <td>${item.size || '-'}</td>
      <td>${item.color || '-'}</td>
      <td>${quantity}</td>
      <td>${formatPrice(price)}</td>
    `;
    tbody.appendChild(tr);
  });

  totalPriceEl.textContent = `Total: ${formatPrice(total)}`;
  confirmBtn.disabled = false;
}

fetch('get_addresses.php')
  .then(res => res.json())
  .then(addresses => {
    const select = document.getElementById('addressSelect');
    select.innerHTML = '';

    if (addresses.length === 0) {
      select.innerHTML = '<option value="">No saved addresses</option>';
      return;
    }

    addresses.forEach((addr, index) => {
      const option = document.createElement('option');
      option.value = index;
      option.textContent = `${addr.house_no}, ${addr.street}, ${addr.brgy}, ${addr.city}, ${addr.province || ''}`;
      option.dataset.full = JSON.stringify(addr);
      select.appendChild(option);
    });
  });

document.getElementById('addressSelect').addEventListener('change', function () {
  const selected = this.selectedOptions[0];
  const details = selected.dataset.full ? JSON.parse(selected.dataset.full) : null;
  const container = document.getElementById('addressDetails');

  if (details) {
    container.innerHTML = `
      <p><strong>Street:</strong> ${details.street}</p>
      <p><strong>Barangay:</strong> ${details.brgy}</p>
      <p><strong>City:</strong> ${details.city}</p>
      <p><strong>Province:</strong> ${details.province || 'N/A'}</p>
      <p><strong>Country:</strong> ${details.country}</p>
      <p><strong>Postal Code:</strong> ${details.postal_code}</p>
    `;
  } else {
    container.innerHTML = '';
  }
});

const modal = document.getElementById('paymentModal');
const closeBtn = document.getElementById('closeModal');

function showModal() {
  modal.style.display = 'flex';
}

function hideModal() {
  modal.style.display = 'none';
  window.location.href = 'all.php';
}

closeBtn.onclick = hideModal;

window.onclick = function(event) {
  if (event.target === modal) {
    hideModal();
  }
};

let selectedPayment = null;
const paymentRadios = document.querySelectorAll('input[name="payment"]');
paymentRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    selectedPayment = radio.value;
  });
});

confirmBtn.addEventListener('click', () => {
  if (!selectedPayment) {
    alert('Please select a payment method.');
    return;
  }

  if (cart.length === 0) {
    alert('Your cart is empty.');
    return;
  }

  const addressSelect = document.getElementById('addressSelect');
  if (!addressSelect.value) {
    alert('Please select a shipping address.');
    return;
  }

  confirmBtn.disabled = true;
  confirmBtn.textContent = 'Processing...';

  const now = new Date();
  const checkoutDateTime = now.toISOString();

  const payload = {
    cart: cart,
    payment_method: selectedPayment,
    shipping_address: JSON.parse(addressSelect.selectedOptions[0].dataset.full),
    total: cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
    checkout_datetime: checkoutDateTime
  };

  fetch('save_transaction.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(response => {
    if (!response.ok) throw new Error(`Server error: ${response.status}`);
    return response.json();
  })
  .then(data => {
    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Confirm Checkout';

    if (data.success) {
      localStorage.removeItem('cartData');
      tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Your cart is empty.</td></tr>`;
      totalPriceEl.textContent = 'Total: ₱0.00';
      confirmBtn.disabled = true;

      if (selectedPayment === 'cod') {
        showModal();
      } else {
        alert('Transaction saved! Transaction ID: ' + data.transaction_id);
        window.location.href = 'all.php';
      }
    } else {
      alert(data.message || 'Failed to save transaction. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred while processing your transaction. Please try again.');
    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Confirm Checkout';
  });
});


  </script>
</body>
</html>