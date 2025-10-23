<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="dash.css">
  <title>Dashboard</title>
  
  <style>
    #transactions-table button {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
      font-size: 14px;
    }

    #transactions-table button + button {
      background-color: #f44336;
    }

    input[type="file"] {
      display: none;
    }

    .custom-file-upload {
      display: inline-block;
      padding: 8px 20px;
      cursor: pointer;
      background-color: #1976d2;
      color: white;
      border-radius: 6px;
      font-weight: 600;
      transition: background-color 0.3s ease;
      user-select: none;
    }

    .custom-file-upload:hover {
      background-color: #0d47a1;
    }

    .upload-container {
      margin: 10px 0 20px 0;
      display: flex;
      align-items: center;
      gap: 12px;
      font-family: Arial, sans-serif;
      font-size: 0.9rem;
      color: #555;
    }

    .upload-preview {
      width: 50px;
      height: 50px;
      border-radius: 6px;
      object-fit: cover;
      border: 1px solid #ccc;
      display: none;
    }

    button {
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.1s ease;
      }

      button:hover {
        transform: translateY(-1px);
      }

      .approve-btn {
        background-color: #28a745;
        color: white;
      }

      .approve-btn:hover {
        background-color: #218838;
      }

      .decline-btn {
        background-color: #dc3545;
        color: white;
      }

      .decline-btn:hover {
        background-color: #c82333;
      }

      .add-stock-btn {
        background-color: #007bff;
        color: white;
      }

      .add-stock-btn:hover {
        background-color: #0069d9;
      }

      #save-stock-btn {
        background-color: #17a2b8;
        color: white;
      }

      #save-stock-btn:hover {
        background-color: #138496;
      }

      #cancel-stock-btn {
        background-color: #6c757d;
        color: white;
      }

      #cancel-stock-btn:hover {
        background-color: #5a6268;
      }

      .edit-btn {
        background-color: #007bff;
        color: white;
      }

      .edit-btn:hover {
        background-color: #0056b3;
      }

      .delete-btn {
        background-color: #dc3545;
        color: white;
      }

      .delete-btn:hover {
        background-color: #c82333;
      }

      button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
      }
  </style>
</head>

<body>
  <div class="sidebar">
    <h2>AUROVA Admin</h2>
    <nav>
      <a href="#" class="active" data-section="dashboard">Dashboard</a>
      <a href="#" data-section="categories">Categories</a>
      <a href="#" data-section="products">Products</a>
      <a href="#" data-section="users">Users</a>
      <a href="#" data-section="stocks">Stocks</a>
      <a href="#" data-section="transactions">Transactions</a>
      <a href="#" data-section="inventory">Inventory</a>
      <a href="#" data-section="logout">Logout</a>
    </nav>
  </div>

  <div class="main-content">
    <h1 id="section-title">Dashboard</h1>

    <div id="content-area">
      <div class="section" id="dashboard-section">
        <p>Welcome to the AUROVA Admin Dashboard. Use the menu to manage categories, products, users, stocks, and transactions.</p>
      </div>

      <div class="section" id="categories-section" style="display:none;">
        <h2>Add New Category</h2>
        <form id="category-form">
          <label for="category-name">Category Name</label>
          <input type="text" id="category-name" name="category_name" required />
          <button type="submit">Add Category</button>
        </form>

        <h3>Category List</h3>
        <table id="category-table" border="1" style="margin-top: 20px; width: 100%;">
          <thead>
            <tr>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="category-tbody">
            <tr><td colspan="2" style="text-align: center;">Loading categories...</td></tr>
          </tbody>
        </table>
      </div>

      <div class="section" id="products-section" style="display:none;">
        <h2>Add New Product</h2>
        <form id="product-form" enctype="multipart/form-data">
          <label for="product-name">Product Name</label>
          <input type="text" id="product-name" name="name" required />

          <label for="product-category">Category</label>
          <select id="product-category" name="category" required>
            <option value="" disabled selected>Select Category</option>
            </select>
          <label for="product-price">Price (₱)</label>
          <input type="number" id="product-price" name="price" min="0" step="0.01" required />

          <label for="product-desc">Description</label>
          <textarea id="product-desc" name="description" rows="3" required></textarea>

          <label for="product-quantity">Quantity</label>
          <input type="number" id="product-quantity" name="quantity" min="0" required />

          <label for="product-tags">Tags (comma separated)</label>
          <input type="text" id="product-tags" name="tags" />

          <label for="product-photo">Photo</label>
          <div class="upload-container">
            <label for="product-photo" class="custom-file-upload">Choose Photo</label>
            <span id="product-photo-filename">No file chosen</span>
            <img id="product-photo-preview" class="upload-preview" alt="Preview" />
          </div>
          <input type="file" id="product-photo" name="photo" accept="image/*" required />

          <button type="submit">Add Product</button>
        </form>
      </div>

      <div class="section" id="users-section" style="display:none;">
        <h2>Registered Users</h2>
        <input type="text" id="users-search" placeholder="Search users..." style="margin-bottom: 10px; width: 100%; padding: 8px;">
        <table id="users-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="users-tbody">
            </tbody>
        </table>
      </div>

      <div class="section" id="stocks-section" style="display:none;">
        <h2>Manage Stocks</h2>
        <input type="text" id="stocks-search" placeholder="Search stocks..." style="margin-bottom: 10px; width: 100%; padding: 8px;">
        <table id="stocks-table" border="1" style="width:100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="stocks-tbody">
            </tbody>
        </table>

        <div id="add-stocks-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh;
            background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
          <div style="background:#fff; padding:20px; border-radius:8px; max-width:300px; margin:auto; position:relative;">
            <h3>Add Stocks</h3>
            <p id="modal-product-name"></p>
            <input type="number" id="add-stock-quantity" min="1" placeholder="Quantity to add" style="width:100%; padding:8px; margin-bottom:12px;" />
            <button id="save-stock-btn" style="padding:8px 16px;">Save</button>
            <button id="cancel-stock-btn" style="padding:8px 16px; margin-left:10px;">Cancel</button>
          </div>
        </div>
      </div>

      <div class="section" id="transactions-section" style="display:none;">
        <h2>Transactions</h2>
        <input type="text" id="transactions-search" placeholder="Search transactions..." style="margin-bottom: 10px; width: 100%; padding: 8px;">
        <table id="transactions-table">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Items</th>
              <th>Total</th>
              <th>Payment Method</th>
              <th>Shipping Address</th>
              <th>Checkout Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="transactions-tbody">
            </tbody>
        </table>
      </div>

      <div class="section" id="inventory-section" style="display:none;">
        <h2>Product Inventory</h2>
        <input type="text" id="inventory-search" placeholder="Search inventory..." style="margin-bottom: 10px; width: 100%; padding: 8px;">
        <table id="inventory-table">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Category</th>
              <th>Description</th>
              <th>Tags</th>
              <th>Price (₱)</th>
            <th>Stocks</th>
            <th>Photo</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="inventory-tbody">
          </tbody>
      </table>
    </div>

    <div id="photo-modal" class="modal" style="display:none;">
      <div class="modal-content">
        <span class="close-btn">&times;</span>
        <img id="modal-image" src="" alt="Product Photo" style="max-width:100%;">
      </div>
    </div>

    <div id="delete-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh;
        background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
      <div style="background:#fff; padding:20px; border-radius:8px; max-width:300px; text-align:center;">
        <p id="delete-message">Are you sure you want to delete this product?</p>
        <button id="delete-yes">Yes</button>
        <button id="delete-no">No</button>
      </div>
    </div>

    <div id="edit-modal" class="modal" style="display: none;">
      <div class="modal-content">
        <span class="close-edit">&times;</span>
        <h2>Edit Product</h2>
        <form id="edit-form" enctype="multipart/form-data">
          <input type="hidden" id="edit-index" />

          <label for="edit-name">Name:</label>
          <input type="text" id="edit-name" required />

          <label for="edit-category">Category:</label>
          <select id="edit-category" required>
            <option value="" disabled selected>Select Category</option>
            </select>

          <label for="edit-description">Description:</label>
          <textarea id="edit-description" rows="2"></textarea>

          <label for="edit-tags">Tags:</label>
          <input type="text" id="edit-tags" />

          <label for="edit-price">Price:</label>
          <input type="number" id="edit-price" step="0.01" required />

          <label for="edit-quantity">Quantity:</label>
          <input type="number" id="edit-quantity" disabled />

          <label for="edit-photo">Photo:</label>
          <div class="upload-container">
            <label for="edit-photo" class="custom-file-upload">Choose Photo</label>
            <span id="edit-photo-filename">No file chosen</span>
            <img id="edit-photo-preview" class="upload-preview" alt="Preview" />
          </div>
          <input type="file" id="edit-photo" accept="image/*" />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

    <div id="edit-user-modal" class="modal" style="display: none;">
      <div class="modal-content">
        <span class="close-user-edit">&times;</span>
        <h2>Edit User</h2>
        <form id="edit-user-form">
          <input type="hidden" id="edit-user-id" name="userId" />

          <label for="edit-user-fname">First Name:</label>
          <input type="text" id="edit-user-fname" name="firstName" required />

          <label for="edit-user-mname">Middle Name:</label>
          <input type="text" id="edit-user-mname" name="middleName" />

          <label for="edit-user-lname">Last Name:</label>
          <input type="text" id="edit-user-lname" name="lastName" required />

          <label for="edit-user-username">Username:</label>
          <input type="text" id="edit-user-username" name="username" required />

          <label for="edit-user-email">Email:</label>
          <input type="email" id="edit-user-email" name="email" required />

          <label for="edit-user-password">New Password (leave blank to keep current):</label>
          <input type="password" id="edit-user-password" name="password" />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

    <div class="section" id="logout-section" style="display:none;">
      <button id="logout-btn">Logout</button>
    </div>
  </div>
</div>

<script>
  window.productsData = [];
  window.transactionsData = [];

  const links = document.querySelectorAll('.sidebar nav a');
  const sections = {
    dashboard: document.getElementById('dashboard-section'),
    categories: document.getElementById('categories-section'),
    products: document.getElementById('products-section'),
    users: document.getElementById('users-section'),
    stocks: document.getElementById('stocks-section'),
    transactions: document.getElementById('transactions-section'),
    inventory: document.getElementById('inventory-section'),
    logout: document.getElementById('logout-section'),
  };
  const contentTitle = document.getElementById('section-title');
  const productForm = document.getElementById('product-form');
  const editForm = document.getElementById('edit-form');
  const logoutBtn = document.getElementById('logout-btn');

  const inventorySearchInput = document.getElementById('inventory-search');
  const usersSearchInput = document.getElementById('users-search');
  const stocksSearchInput = document.getElementById('stocks-search');
  const transactionsSearchInput = document.getElementById('transactions-search');


  const productPhotoInput = document.getElementById('product-photo');
  const productPhotoFilename = document.getElementById('product-photo-filename');
  const productPhotoPreview = document.getElementById('product-photo-preview');
  const editPhotoInput = document.getElementById('edit-photo');
  const editPhotoFilename = document.getElementById('edit-photo-filename');
  const editPhotoPreview = document.getElementById('edit-photo-preview');

  const stocksTableBody = document.getElementById('stocks-tbody');
  const addStocksModal = document.getElementById('add-stocks-modal');
  const modalProductName = document.getElementById('modal-product-name');
  const addStockQuantityInput = document.getElementById('add-stock-quantity');
  const saveStockBtn = document.getElementById('save-stock-btn');
  const cancelStockBtn = document.getElementById('cancel-stock-btn');

  const transactionsTableBody = document.getElementById('transactions-tbody');
  const deleteProductModal = document.getElementById('delete-modal');
  const deleteMessage = document.getElementById('delete-message');
  const deleteYesBtn = document.getElementById('delete-yes');
  const deleteNoBtn = document.getElementById('delete-no');
  const photoModal = document.getElementById('photo-modal');
  const modalImage = document.getElementById('modal-image');
  const photoModalCloseBtn = document.querySelector('#photo-modal .close-btn');
  const categoryTbody = document.getElementById('category-tbody');

  const usersTbody = document.getElementById('users-tbody');
  const editUserModal = document.getElementById('edit-user-modal');
  const editUserForm = document.getElementById('edit-user-form');
  const closeUserEditBtn = document.querySelector('.close-user-edit');


  let addingProduct = false;
  let currentEditRow = null;
  let currentProductNameForStock = null;
  let nameToDelete = null;

  function getText(parent, tag) {
    const el = parent.getElementsByTagName(tag)[0];
    return el ? el.textContent : '';
  }

  function populateCategoryDropdowns() {
    fetch('get_categories.php')
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(categories => {
        const productCategorySelect = document.getElementById('product-category');
        const editCategorySelect = document.getElementById('edit-category');

        [productCategorySelect, editCategorySelect].forEach(select => {
          if (!select) {
            console.warn(`Dropdown element not found: ${select === productCategorySelect ? 'product-category' : 'edit-category'}`);
            return;
          }
          select.innerHTML = '<option value="" disabled selected>Select Category</option>';

          categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat;
            option.textContent = cat;
            select.appendChild(option);
          });
        });
      })
      .catch(error => {
        console.error('Error loading categories for dropdowns:', error);
        const productCategorySelect = document.getElementById('product-category');
        const editCategorySelect = document.getElementById('edit-category');
        [productCategorySelect, editCategorySelect].forEach(select => {
          if (select) {
            select.innerHTML = `
              <option value="" disabled selected>Error loading categories</option>
              <option value="Men">Men</option>
              <option value="Women">Women</option>
            `;
          }
        });
      });
  }

  function loadCategories() {
    if (!categoryTbody) {
      console.error("Error: #category-tbody element not found in the DOM for loadCategories.");
      return;
    }
    categoryTbody.innerHTML = '<tr><td colspan="2" style="text-align: center;">Loading categories...</td></tr>';

    fetch('load_categories.php')
      .then(res => res.text())
      .then(html => {
        if (html.trim()) {
          categoryTbody.innerHTML = html;
        } else {
          categoryTbody.innerHTML = '<tr><td colspan="2" style="text-align: center;">No categories found.</td></tr>';
        }
      })
      .catch(error => {
        console.error('Error loading categories list:', error);
        if (categoryTbody) {
          categoryTbody.innerHTML = '<tr><td colspan="2" style="text-align: center; color: red;">Failed to load categories.</td></tr>';
        }
      });
  }

  window.editCategory = function(index) {
    const newName = prompt("Enter new category name:");
    if (newName) {
      fetch('edit_category.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `index=${index}&new_name=${encodeURIComponent(newName)}`
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        loadCategories();
        populateCategoryDropdowns();
      })
      .catch(error => console.error('Error editing category:', error));
    }
  };

  window.deleteCategory = function(index) {
    if (confirm("Are you sure you want to delete this category? This will also remove products under this category.")) {
      fetch('delete_category.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `index=${index}`
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        loadCategories();
        populateCategoryDropdowns();
        loadAllProductsData().then(() => {
          renderInventoryTable();
          renderStocksTable();
        });
      })
      .catch(error => console.error('Error deleting category:', error));
    }
  };


  async function loadAllProductsData() {
      try {
          const response = await fetch('products.xml');
          if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
          const text = await response.text();
          const parser = new DOMParser();
          const xml = parser.parseFromString(text, 'text/xml');

          if (xml.getElementsByTagName("parsererror").length > 0) {
              const parserError = xml.getElementsByTagName("parsererror")[0].textContent;
              console.error("Error parsing products.xml:", parserError);
              if (text.trim() !== '<products></products>' && text.trim() !== '<?xml version="1.0" encoding="UTF-8"?><products/>' && text.trim() !== '') {
                  alert("Error loading product data. Please check products.xml for syntax errors in your browser console: " + parserError);
              }
              window.productsData = [];
              return;
          }

          window.productsData = Array.from(xml.getElementsByTagName('product')).map(p => ({
              name: getText(p, 'name'),
              category: getText(p, 'category'),
              description: getText(p, 'description'),
              tags: getText(p, 'tags'),
              price: parseFloat(getText(p, 'price')).toFixed(2),
              quantity: parseInt(getText(p, 'quantity')),
              photo: getText(p, 'photo')
          }));
          console.log("Products data loaded:", window.productsData.length, "products.");
      } catch (error) {
          console.error('Error loading products.xml:', error);
          alert("Could not load product data. Check if 'products.xml' exists and is accessible. Error: " + error.message);
          window.productsData = [];
      }
  }

  function renderInventoryTable() {
    const tbody = document.getElementById('inventory-tbody');
    if (!tbody) { console.error("Error: #inventory-tbody not found for rendering."); return; }
    tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">Loading inventory...</td></tr>';

    const searchTerm = inventorySearchInput.value.trim().toLowerCase();

    const filtered = window.productsData.filter(p => {
      return (
        (p.name && p.name.toLowerCase().includes(searchTerm)) ||
        (p.category && p.category.toLowerCase().includes(searchTerm)) ||
        (p.description && p.description.toLowerCase().includes(searchTerm)) ||
        (p.tags && p.tags.toLowerCase().includes(searchTerm))
      );
    });

    tbody.innerHTML = '';

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8">No products match your search.</td></tr>`;
      return;
    }

    filtered.forEach(p => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${p.name}</td>
        <td>${p.category}</td>
        <td>${p.description}</td>
        <td>${p.tags}</td>
        <td>₱${p.price}</td>
        <td>${p.quantity}</td>
        <td><img src="${p.photo}" alt="${p.name}" style="width: 40px; height: 40px; object-fit: cover;"></td>
        <td>
          <button class="edit-btn">Edit</button>
          <button class="delete-btn" data-name="${p.name}">Delete</button>
        </td>`;
      tbody.appendChild(tr);
    });

    attachProductActionEvents();
    console.log("Inventory table rendered.");
  }

  function attachProductActionEvents() {
    document.querySelectorAll('#inventory-table .view-photo-btn').forEach(b => {
      b.onclick = () => {
        if (modalImage && photoModal) {
          modalImage.src = b.dataset.src;
          photoModal.style.display = 'flex';
        }
      };
    });

    document.querySelectorAll('#inventory-table .edit-btn').forEach(b => {
      b.onclick = () => {
        currentEditRow = b.closest('tr');
        const cells = currentEditRow.querySelectorAll('td');
        if (document.getElementById('edit-name')) document.getElementById('edit-name').value = cells[0].innerText;
        if (document.getElementById('edit-category')) document.getElementById('edit-category').value = cells[1].innerText;
        if (document.getElementById('edit-description')) document.getElementById('edit-description').value = cells[2].innerText;
        if (document.getElementById('edit-tags')) document.getElementById('edit-tags').value = cells[3].innerText;
        if (document.getElementById('edit-price')) document.getElementById('edit-price').value = cells[4].innerText.replace('₱','');
        if (document.getElementById('edit-quantity')) document.getElementById('edit-quantity').value = cells[5].innerText;
        if (editPhotoInput) editPhotoInput.value = '';
        const currentPhotoSrc = cells[6].querySelector('img')?.src;
        if (editPhotoPreview && currentPhotoSrc) {
            editPhotoPreview.src = currentPhotoSrc;
            editPhotoPreview.style.display = 'block';
            if (editPhotoFilename) editPhotoFilename.textContent = currentPhotoSrc.split('/').pop();
        } else if (editPhotoFilename) {
             editPhotoFilename.textContent = 'No file chosen';
             if (editPhotoPreview) editPhotoPreview.style.display = 'none';
        }
        if (document.getElementById('edit-modal')) document.getElementById('edit-modal').style.display = 'flex';
        populateCategoryDropdowns();
      };
    });

    document.querySelectorAll('#inventory-table .delete-btn').forEach(b => {
      b.onclick = () => {
        nameToDelete = b.dataset.name;
        if (deleteMessage && deleteProductModal) {
          deleteMessage.textContent = `Are you sure you want to delete "${nameToDelete}"?`;
          deleteProductModal.style.display = 'flex';
        }
      };
    });
  }

  function loadUsers() {
    if (!usersTbody) { console.error("Error: #users-tbody not found for loading users."); return; }
    usersTbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Loading users...</td></tr>';

    const searchTerm = usersSearchInput.value.trim().toLowerCase();

    fetch('fetch_users.php')
      .then(response => {
        if (!response.ok) throw new Error("Failed to fetch users");
        return response.json();
      })
      .then(users => {
        usersTbody.innerHTML = '';
        
        const filteredUsers = users.filter(user => {
            return (
                (user.ID && String(user.ID).toLowerCase().includes(searchTerm)) ||
                (user.FirstName && user.FirstName.toLowerCase().includes(searchTerm)) ||
                (user.MiddleName && user.MiddleName.toLowerCase().includes(searchTerm)) ||
                (user.LastName && user.LastName.toLowerCase().includes(searchTerm)) ||
                (user.Username && user.Username.toLowerCase().includes(searchTerm)) ||
                (user.Email && user.Email.toLowerCase().includes(searchTerm))
            );
        });

        if (filteredUsers.length === 0) {
          usersTbody.innerHTML = `<tr><td colspan="7">No users found.</td></tr>`;
          return;
        }
        filteredUsers.forEach(user => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${user.ID || 'N/A'}</td>
            <td>${user.FirstName || ''}</td>
            <td>${user.MiddleName || ''}</td>
            <td>${user.LastName || ''}</td>
            <td>${user.Username || ''}</td>
            <td>${user.Email || ''}</td>
            <td>
              <button class="edit-user-btn" data-id="${user.ID}">Edit</button>
              <button class="delete-user-btn" data-id="${user.ID}">Delete</button>
            </td>
          `;
          usersTbody.appendChild(tr);
        });
        attachUserActionEvents();
      })
      .catch(error => {
        console.error('Error loading users:', error);
        usersTbody.innerHTML = `<tr><td colspan="7">Failed to load users.</td></tr>`;
      });
  }

  function attachUserActionEvents() {
      document.querySelectorAll('.edit-user-btn').forEach(btn => {
          btn.onclick = () => {
              const row = btn.closest('tr');
              const userId = btn.dataset.id;
              const cells = row.querySelectorAll('td');

              if (document.getElementById('edit-user-id')) document.getElementById('edit-user-id').value = userId;
              if (document.getElementById('edit-user-fname')) document.getElementById('edit-user-fname').value = cells[1].innerText;
              if (document.getElementById('edit-user-mname')) document.getElementById('edit-user-mname').value = cells[2].innerText;
              if (document.getElementById('edit-user-lname')) document.getElementById('edit-user-lname').value = cells[3].innerText;
              if (document.getElementById('edit-user-username')) document.getElementById('edit-user-username').value = cells[4].innerText;
              if (document.getElementById('edit-user-email')) document.getElementById('edit-user-email').value = cells[5].innerText;
              if (document.getElementById('edit-user-password')) document.getElementById('edit-user-password').value = '';

              if (editUserModal) editUserModal.style.display = 'flex';
          };
      });

      document.querySelectorAll('.delete-user-btn').forEach(btn => {
          btn.onclick = async () => {
              const userId = btn.dataset.id;
              if (confirm('Are you sure you want to delete user ID: ' + userId + '?')) {
                  try {
                      const response = await fetch('delete_user.php', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                          body: `id=${encodeURIComponent(userId)}`
                      });
                      if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                      const data = await response.json();

                      if (data.success) {
                          alert(data.message);
                          loadUsers();
                      } else {
                          alert('Error deleting user: ' + data.message);
                          console.error('Delete user error:', data.message);
                      }
                  } catch (error) {
                      alert('An error occurred during user deletion: ' + error.message);
                      console.error('Delete user fetch error:', error);
                  }
              }
          };
      });
  }


  function renderStocksTable() {
    if (!stocksTableBody) { console.error("Error: #stocks-tbody not found for rendering stocks."); return; }
    stocksTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Loading stocks...</td></tr>';

    const searchTerm = stocksSearchInput.value.trim().toLowerCase();

    const filteredProducts = window.productsData.filter(product => {
        return (
            (product.name && product.name.toLowerCase().includes(searchTerm)) ||
            (product.category && product.category.toLowerCase().includes(searchTerm)) ||
            (product.tags && product.tags.toLowerCase().includes(searchTerm))
        );
    });

    stocksTableBody.innerHTML = '';
    if (filteredProducts.length === 0) {
      stocksTableBody.innerHTML = `<tr><td colspan="3">No products available for stock management.</td></tr>`;
      return;
    }

    filteredProducts.forEach(({ name, quantity }) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${name}</td>
        <td>${quantity}</td>
        <td><button data-name="${name}" class="add-stock-btn">Add Stocks</button></td>
      `;
      stocksTableBody.appendChild(tr);
    });
    attachStockAddEvents();
    console.log("Stocks table rendered.");
  }

  function attachStockAddEvents() {
    document.querySelectorAll('#stocks-tbody .add-stock-btn').forEach(button => {
      button.onclick = (e) => {
        currentProductNameForStock = e.target.dataset.name;
        addStockQuantityInput.value = '';
        if (modalProductName) modalProductName.textContent = `Product: ${currentProductNameForStock}`;
        if (addStocksModal) addStocksModal.style.display = 'flex';
        if (addStockQuantityInput) addStockQuantityInput.focus();
      };
    });
  }

  function deductStockFromTransaction(transactionId) {
    const transaction = window.transactionsData.find(t => t.id === transactionId);
    if (!transaction) {
      console.error('Transaction data not found for ID:', transactionId);
      return;
    }

    let stockUpdates = [];

    transaction.items.forEach(item => {
      const product = window.productsData.find(p => p.name === item.name);
      if (product) {
        const qtyToDeduct = parseInt(item.quantity);

        if (product.quantity >= qtyToDeduct) {
          product.quantity -= qtyToDeduct;
        } else {
          alert(`Insufficient stock for product "${product.name}". Deducting all available stock.`);
          product.quantity = 0;
        }
        stockUpdates.push({ name: product.name, newQuantity: product.quantity });
      } else {
        console.warn(`Product "${item.name}" not found in current products data.`);
      }
    });

    renderStocksTable();
    renderInventoryTable();

    stockUpdates.forEach(update => {
      fetch('update_stocks.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ productName: update.name, newQuantity: update.newQuantity, deduct: true })
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          console.error(`Failed to update stock for ${update.name}: ${data.message}`);
        }
      })
      .catch(err => console.error('Error updating stock:', err));
    });
  }


  function addTransactionRow(transaction) {
    const tableBody = transactionsTableBody;
    if (!tableBody) { console.error("Error: #transactions-tbody not found for adding transaction row."); return; }

    const id = transaction.id;
    const paymentMethod = transaction.payment_method;
    const total = parseFloat(transaction.total).toFixed(2);
    const checkoutDatetime = new Date(transaction.checkout_datetime).toLocaleString();

    const address = `
      ${transaction.shipping_address.house_no} ${transaction.shipping_address.street},
      ${transaction.shipping_address.brgy}, ${transaction.shipping_address.city},
      ${transaction.shipping_address.province}, ${transaction.shipping_address.country}
      ${transaction.shipping_address.postal_code}
    `;

    let itemsHtml = "<ul style='padding: 0; list-style: none;'>";
    transaction.items.forEach(item => {
      itemsHtml += `
        <li style="display: flex; align-items: center; margin-bottom: 8px;">
          <img src="${item.photo}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
          <div>
            <div><strong>${item.name}</strong> (${item.size}, ${item.color})</div>
            <div>₱${parseFloat(item.price).toFixed(2)} × ${item.quantity}</div>
          </div>
        </li>
      `;
    });
    itemsHtml += "</ul>";

    const row = document.createElement("tr");
    const currentStatus = transaction.status || 'Pending';
    const isActionDisabled = (currentStatus.toLowerCase() === 'approved' || currentStatus.toLowerCase() === 'declined' || currentStatus.toLowerCase() === 'cancelled');

    row.innerHTML = `
      <td class="transaction-id">${id}</td>
      <td>${itemsHtml}</td>
      <td>₱${total}</td>
      <td>${paymentMethod}</td>
      <td>${address}</td>
      <td>${checkoutDatetime}</td>
      <td style="vertical-align: top;">
        <div style="display: flex; flex-direction: column; gap: 5px;" id="actions-${id}">
          <button class="approve-btn" data-id="${id}" ${isActionDisabled ? 'disabled' : ''}>Approve</button>
          <button class="decline-btn" data-id="${id}" ${isActionDisabled ? 'disabled' : ''}>Decline</button>
          <span class="status-text" style="font-weight:bold; margin-top: 5px;">${currentStatus.toUpperCase()}</span>
        </div>
      </td>
    `;

    tableBody.appendChild(row);
  }

  function loadTransactions() {
    if (!transactionsTableBody) { console.error("Error: #transactions-tbody not found for loading transactions."); return; }
    transactionsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Loading transactions...</td></tr>';
    
    const searchTerm = transactionsSearchInput.value.trim().toLowerCase();


    fetch('transactions.xml')
      .then(response => response.text())
      .then(xmlString => {
        const parser = new DOMParser();
        const xml = parser.parseFromString(xmlString, "application/xml");
        const transactions = xml.getElementsByTagName("transaction");

        window.transactionsData = [];
        
        for (let i = 0; i < transactions.length; i++) {
          const t = transactions[i];

          const transaction = {
            id: getText(t, "id"),
            payment_method: getText(t, "payment_method"),
            total: getText(t, "total"),
            checkout_datetime: getText(t, "checkout_datetime"),
            shipping_address: {
              house_no: getText(t.getElementsByTagName("shipping_address")[0], "house_no"),
              street: getText(t.getElementsByTagName("shipping_address")[0], "street"),
              brgy: getText(t.getElementsByTagName("shipping_address")[0], "brgy"),
              city: getText(t.getElementsByTagName("shipping_address")[0], "city"),
              province: getText(t.getElementsByTagName("shipping_address")[0], "province"),
              country: getText(t.getElementsByTagName("shipping_address")[0], "country"),
              postal_code: getText(t.getElementsByTagName("shipping_address")[0], "postal_code"),
            },
            items: [],
            status: getText(t, "status") || 'Pending'
          };

          const itemNodes = t.getElementsByTagName("item");
          for (let j = 0; j < itemNodes.length; j++) {
            const item = itemNodes[j];
            transaction.items.push({
              name: getText(item, "name"),
              size: getText(item, "size"),
              color: getText(item, "color"),
              quantity: getText(item, "quantity"),
              price: getText(item, "price"),
              photo: getText(item, "photo")
            });
          }
          window.transactionsData.push(transaction);
        }

        const filteredTransactions = window.transactionsData.filter(t => {
            const fullAddress = `${t.shipping_address.house_no} ${t.shipping_address.street} ${t.shipping_address.brgy} ${t.shipping_address.city} ${t.shipping_address.province} ${t.shipping_address.country} ${t.shipping_address.postal_code}`;
            const itemNames = t.items.map(item => item.name).join(' ');
            
            return (
                (t.id && t.id.toLowerCase().includes(searchTerm)) ||
                (t.payment_method && t.payment_method.toLowerCase().includes(searchTerm)) ||
                (t.status && t.status.toLowerCase().includes(searchTerm)) ||
                (fullAddress.toLowerCase().includes(searchTerm)) ||
                (itemNames.toLowerCase().includes(searchTerm))
            );
        });

        transactionsTableBody.innerHTML = '';

        if (filteredTransactions.length === 0) {
            transactionsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No transactions match your search.</td></tr>';
        } else {
            filteredTransactions.forEach(transaction => {
                addTransactionRow(transaction);
            });
        }
      })
      .catch(error => {
        console.error('Error loading transactions.xml:', error);
        transactionsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">Failed to load transactions.</td></tr>';
      });
  }

  async function updateTransactionStatus(transactionId, newStatus) {
      try {
          const response = await fetch('update_transaction_status.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id: transactionId, status: newStatus })
          });
          if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
          const data = await response.json();
          if (data.success) {
              console.log(`Transaction ${transactionId} status updated to ${newStatus}`);
          } else {
              console.error(`Failed to update transaction status: ${data.message}`);
          }
      } catch (error) {
          console.error('Error updating transaction status:', error);
      }
  }


  function handleApproveTransaction(button) {
    const actionDiv = button.parentElement;
    const statusText = actionDiv.querySelector('.status-text');
    const transactionId = button.dataset.id;
    const newStatus = 'Approved';

    statusText.textContent = newStatus.toUpperCase();
    button.textContent = 'Approved';
    button.disabled = true;
    const declineBtn = actionDiv.querySelector('.decline-btn');
    if (declineBtn) declineBtn.style.display = 'none';

    updateTransactionStatus(transactionId, newStatus);
    deductStockFromTransaction(transactionId);
  }

  function handleDeclineTransaction(button) {
    const actionDiv = button.parentElement;
    const statusText = actionDiv.querySelector('.status-text');
    const transactionId = button.dataset.id;
    const newStatus = 'Declined';

    statusText.textContent = newStatus.toUpperCase();
    button.textContent = 'Declined';
    button.disabled = true;
    const approveBtn = actionDiv.querySelector('.approve-btn');
    if (approveBtn) approveBtn.style.display = 'none';

    updateTransactionStatus(transactionId, newStatus);
  }

  document.addEventListener('DOMContentLoaded', async () => {
    await loadAllProductsData();
    loadCategories();
    populateCategoryDropdowns();
    loadTransactions();
    loadUsers();


    links.forEach(link => link.addEventListener('click', e => {
      e.preventDefault();
      const targetSection = link.dataset.section;

      links.forEach(l => l.classList.remove('active'));
      Object.values(sections).forEach(sec => {
        if (sec) sec.style.display = 'none';
      });

      link.classList.add('active');
      if (sections[targetSection]) {
        sections[targetSection].style.display = 'block';
        contentTitle.textContent = link.textContent;

        if (targetSection === 'categories') {
          loadCategories();
          populateCategoryDropdowns();
        } else if (targetSection === 'products') {
          populateCategoryDropdowns();
        } else if (targetSection === 'users') {
          loadUsers();
        } else if (targetSection === 'stocks') {
          renderStocksTable();
        } else if (targetSection === 'transactions') {
          loadTransactions();
        } else if (targetSection === 'inventory') {
          renderInventoryTable();
        } else if (targetSection === 'logout') {
        }
      } else {
        console.warn(`Section "${targetSection}" not found.`);
      }
    }));

    if (sections.dashboard && links[0]) {
        links[0].click();
    } else {
        console.error("Dashboard section or initial link not found.");
    }

    if (document.getElementById('category-form')) {
        document.getElementById('category-form').addEventListener('submit', function (e) {
          e.preventDefault();
          const nameInput = document.getElementById('category-name');
          const categoryName = nameInput.value.trim();

          if (!categoryName) {
            alert('Please enter a category name.');
            return;
          }

          fetch('add_category.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'category_name=' + encodeURIComponent(categoryName)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              nameInput.value = '';
              loadCategories();
              populateCategoryDropdowns();
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            alert('Error occurred while adding category: ' + error.message);
            console.error('Add category error:', error);
          });
        });
    }


    if (productForm) {
      productForm.addEventListener('submit', async e => {
        e.preventDefault();
        if (addingProduct) return;
        addingProduct = true;
        const btn = productForm.querySelector('button[type="submit"]');
        if (btn) btn.disabled = true;

        const formData = new FormData(productForm);
        formData.set('category', document.getElementById('product-category').value);

        try {
            const response = await fetch('add_product.php', { method: 'POST', body: formData });
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            if (data.success) {
              alert(data.message);
              productForm.reset();
              if (productPhotoFilename) productPhotoFilename.textContent = 'No file chosen';
              if (productPhotoPreview) productPhotoPreview.style.display = 'none';
              await loadAllProductsData();
              renderInventoryTable();
              renderStocksTable();
            } else {
                console.error("Add product error:", data.message);
                alert('Error adding product: ' + data.message);
            }
        } catch (error) {
            console.error("Error adding product:", error);
            alert('An error occurred while adding product: ' + error.message);
        } finally {
            addingProduct = false;
            if (btn) btn.disabled = false;
        }
      });
    }

    if (editForm) {
      editForm.addEventListener('submit', async e => {
        e.preventDefault();
        if (!currentEditRow) return;

        const name = document.getElementById('edit-name').value;
        const category = document.getElementById('edit-category').value;
        const desc = document.getElementById('edit-description').value;
        const tags = document.getElementById('edit-tags').value;
        const price = parseFloat(document.getElementById('edit-price').value).toFixed(2);
        const qty = document.getElementById('edit-quantity').value;
        const photoFile = document.getElementById('edit-photo').files[0];

        const formData = new FormData();
        formData.append('originalName', currentEditRow.cells[0].innerText);
        formData.append('name', name);
        formData.append('category', category);
        formData.append('description', desc);
        formData.append('tags', tags);
        formData.append('price', price);
        formData.append('quantity', qty);
        if (photoFile) {
            formData.append('photo', photoFile);
        }

        try {
            const response = await fetch('update_product.php', {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                document.getElementById('edit-modal').style.display = 'none';
                currentEditRow = null;
                if (editPhotoFilename) editPhotoFilename.textContent = 'No file chosen';
                if (editPhotoPreview) editPhotoPreview.style.display = 'none';

                await loadAllProductsData();
                renderInventoryTable();
                renderStocksTable();
            } else {
                console.error("Edit product error:", data.message);
                alert('Error updating product: ' + data.message);
            }
        } catch (error) {
            console.error('Error during product edit:', error);
            alert('An error occurred during product edit: ' + error.message);
        }
      });
    }

    if (deleteYesBtn) deleteYesBtn.onclick = async () => {
      if (deleteProductModal) deleteProductModal.style.display = 'none';
      if (nameToDelete) {
          try {
              const response = await fetch('delete_product.php',{
                  method:'POST',
                  headers:{'Content-Type':'application/x-www-form-urlencoded'},
                  body:`name=${encodeURIComponent(nameToDelete)}`
              });
              if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
              const data = await response.json();

              if (data.success) {
                  alert(data.message);
                  await loadAllProductsData();
                  renderInventoryTable();
                  renderStocksTable();
              } else {
                  console.error("Delete product error:", data.message);
                  alert('Error deleting product: ' + data.message);
              }
          } catch (error) {
              console.error("Error deleting product:", error);
              alert("An error occurred while deleting product: " + error.message);
          }
      }
    };
    if (deleteNoBtn) deleteNoBtn.onclick = () => { if (deleteProductModal) deleteProductModal.style.display = 'none'; };
    if (deleteProductModal) window.addEventListener('click', e => { if (e.target===deleteProductModal) deleteProductModal.style.display='none'; });

    if (productPhotoInput) {
      productPhotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          if (productPhotoFilename) productPhotoFilename.textContent = file.name;
          const reader = new FileReader();
          reader.onload = function (e) {
            if (productPhotoPreview) {
              productPhotoPreview.src = e.target.result;
              productPhotoPreview.style.display = 'block';
            }
          };
          reader.readAsDataURL(file);
        } else {
          if (productPhotoFilename) productPhotoFilename.textContent = 'No file chosen';
          if (productPhotoPreview) {
            productPhotoPreview.style.display = 'none';
            productPhotoPreview.src = '';
          }
        }
      });
    }

    if (editPhotoInput) {
      editPhotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          if (editPhotoFilename) editPhotoFilename.textContent = file.name;
          const reader = new FileReader();
          reader.onload = function (e) {
            if (editPhotoPreview) {
              editPhotoPreview.src = e.target.result;
              editPhotoPreview.style.display = 'block';
            }
          };
          reader.readAsDataURL(file);
        } else {
          if (editPhotoFilename) editPhotoFilename.textContent = 'No file chosen';
          if (editPhotoPreview) {
            editPhotoPreview.style.display = 'none';
            editPhotoPreview.src = '';
          }
        }
      });
    }

    if (photoModalCloseBtn) photoModalCloseBtn.onclick = () => { if (photoModal) photoModal.style.display = 'none'; };
    if (photoModal) window.addEventListener('click', e => { if (e.target===photoModal) photoModal.style.display='none'; });

    const closeEditBtn = document.querySelector('.close-edit');
    if (closeEditBtn) closeEditBtn.onclick = () => { if (document.getElementById('edit-modal')) document.getElementById('edit-modal').style.display='none'; };
    if (document.getElementById('edit-modal')) window.addEventListener('click', e => { if (e.target && e.target.id==='edit-modal') e.target.style.display='none'; });

    if (cancelStockBtn) cancelStockBtn.addEventListener('click', () => {
      if (addStocksModal) addStocksModal.style.display = 'none';
      currentProductNameForStock = null;
    });

    if (saveStockBtn) saveStockBtn.addEventListener('click', () => {
      const addedQty = parseInt(addStockQuantityInput.value);
      if (isNaN(addedQty) || addedQty <= 0) {
        alert('Please enter a valid positive quantity');
        return;
      }
      if (!currentProductNameForStock) {
        alert('No product selected for stock update.');
        return;
      }

      fetch('update_stocks.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ productName: currentProductNameForStock, addedQuantity: addedQty })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Stock updated successfully!');
          if (addStocksModal) addStocksModal.style.display = 'none';
          const product = window.productsData.find(p => p.name === currentProductNameForStock);
          if (product) {
            product.quantity += addedQty;
          }
          renderStocksTable();
          renderInventoryTable();
        } else {
          alert('Failed to update stock: ' + data.message);
        }
      })
      .catch(err => {
        alert('Error updating stock: ' + err.message);
        console.error('Error updating stock:', err);
      });
    });

    if (transactionsTableBody) {
      transactionsTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn')) {
          handleApproveTransaction(e.target);
        } else if (e.target.classList.contains('decline-btn')) {
          handleDeclineTransaction(e.target);
        }
      });
    }

    if(inventorySearchInput){
      inventorySearchInput.addEventListener('input', () => {
        renderInventoryTable();
      });
    }
    if(usersSearchInput){
        usersSearchInput.addEventListener('input', () => {
            loadUsers();
        });
    }
    if(stocksSearchInput){
        stocksSearchInput.addEventListener('input', () => {
            renderStocksTable();
        });
    }
    if(transactionsSearchInput){
        transactionsSearchInput.addEventListener('input', () => {
            loadTransactions();
        });
    }

    if (logoutBtn) {
      logoutBtn.addEventListener('click', () => {
        window.location.href = 'loginpage.php';
      });
    }

    if (editUserForm) {
        editUserForm.addEventListener('submit', async e => {
            e.preventDefault();

            const formData = new FormData(editUserForm);
            if (formData.get('password') === '') {
                formData.delete('password');
            }

            try {
                const response = await fetch('edit_user.php', {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                });
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    if (editUserModal) editUserModal.style.display = 'none';
                    loadUsers();
                } else {
                    alert('Error updating user: ' + data.message);
                    console.error('Update user error:', data.message);
                }
            } catch (error) {
                alert('An error occurred during user update: ' + error.message);
                console.error('Update user fetch error:', error);
            }
        });
    }

    if (closeUserEditBtn) {
        closeUserEditBtn.onclick = () => {
            if (editUserModal) editUserModal.style.display = 'none';
        };
    }
    if (editUserModal) {
        window.addEventListener('click', e => {
            if (e.target === editUserModal) editUserModal.style.display = 'none';
        });
    }
  });
</script>

</body>
</html>