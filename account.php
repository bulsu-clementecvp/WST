<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: loginpage.php");
    exit();
}

$username = $_SESSION['username'];

$host = "localhost";
$dbname = "sample";
$dbuser = "root";
$dbpass = "";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $pdo->prepare("SELECT FirstName, MiddleName, LastName, Username, Email FROM user WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    echo "User not found.";
    exit();
  }

  $userFirstName = $user['FirstName'] ?? $username;

} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Account Page</title>
<link rel="stylesheet" href="test.css">
</head>


<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  background-color: #f4f4f4;
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 220px;
  background-color: #000;
  color: #fff;
  padding: 20px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  overflow-y: auto;
  box-sizing: border-box;
}

.sidebar h2 {
  margin-bottom: 30px;
  border-bottom: 1px solid #fff;
  padding-bottom: 10px;
}

.sidebar a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 12px 10px;
  margin-bottom: 10px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.sidebar a:hover,
.sidebar a.active {
  background-color: #fff;
  color: #000;
}

.content {
  margin-left: 220px;
  padding: 30px;
  flex-grow: 1;
  background-color: #fff;
  color: #000;
  min-height: 100vh;
  overflow-y: auto;
  box-sizing: border-box;
}

.section {
  display: none;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.section.active {
  display: block;
}

.info-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 30px auto;
    text-align: center;
}

.info-card h2 {
    color: #333;
    margin-bottom: 25px;
    font-size: 28px;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
}

.info-detail {
    text-align: left;
    margin-bottom: 15px;
    font-size: 16px;
    color: #555;
    line-height: 1.6;
}
.info-detail strong {
    display: inline-block;
    width: 120px;
    color: #000;
    margin-right: 10px;
}

.edit-profile-form {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 30px auto;
}

.edit-profile-form h2 {
    color: #333;
    margin-bottom: 25px;
    font-size: 28px;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
}

.edit-profile-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.edit-profile-form input[type="text"],
.edit-profile-form input[type="email"],
.edit-profile-form input[type="password"] {
    width: calc(100% - 22px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.edit-profile-form button[type="submit"] {
    background-color: #28a745;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}
.edit-profile-form button[type="submit"]:hover:not(:disabled) {
    background-color: #218838;
}
.edit-profile-form button[type="submit"]:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal.active {
  display: flex;
}

.edit-btn, .delete-btn {
  background-color: #000;
  color: #fff;
  border: none;
  padding: 6px 10px;
  margin: 2px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-size: 14px;
}

.edit-btn:hover {
  background-color: #444;
}

.delete-btn {
  background-color: #c0392b;
}

.delete-btn:hover {
  background-color: #e74c3c;
}

.cancelBtn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.cancelBtn:hover:not(:disabled) {
  background-color: #c0392b;
}

.cancelBtn:disabled {
  background-color: #aaa;
  cursor: not-allowed;
  box-shadow: none;
  color: #666;
}

.upload-btn {
  margin-top: 15px;
  display: inline-block;
  background-color: #000;
  color: #fff;
  padding: 10px 25px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.upload-btn:hover {
  background-color: #444;
}

.welcome-message {
    margin-top: 20px;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-align: center;
}

</style>

<body>

<div class="navbar">
  <ul class="nav-menu">
    <div class="nav-left">
      <li><a href="loginpage.php"><img src="img/logo.png" alt="Logo" class="logo"></a></li>
      <li><a href="loginpage.php">AUROVA</a></li>
    </div>
    <div class="nav-right">
      <li><a href="loginpage.php">HOME</a></li>
      <li><a href="all.php">ALL</a></li>
      <li><a href="account.php" class="icons">ACCOUNTS
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="currentColor"
            >
              <path
                d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM12.1597 16C10.1243 16 8.29182 16.8687 7.01276 18.2556C8.38039 19.3474 10.114 20 12 20C13.9695 20 15.7727 19.2883 17.1666 18.1081C15.8956 16.8074 14.1219 16 12.1597 16ZM12 4C7.58172 4 4 7.58172 4 12C4 13.8106 4.6015 15.4807 5.61557 16.8214C7.25639 15.0841 9.58144 14 12.1597 14C14.6441 14 16.8933 15.0066 18.5218 16.6342C19.4526 15.3267 20 13.7273 20 12C20 7.58172 16.4183 4 12 4ZM12 5C14.2091 5 16 6.79086 16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5ZM12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7Z"
              ></path>
            </svg>
          </a></li>
    </div>
  </ul>
</div>

<div class="sidebar">
  <h2>Account</h2>
  <a href="#" class="active" data-section="info">Info</a>
  <a href="#" data-section="edit-profile">Edit Profile</a>
  <a href="#" data-section="address">Address</a>
  <a href="#" data-section="transactions">Transactions</a>
  <a href="#" id="logout">Logout</a>
</div>

<div class="content">
  <div class="welcome-message">
    Welcome, <?= htmlspecialchars($userFirstName) ?>!
  </div>

  <div id="info" class="section active">
    <div class="info-card">
        <h2>Your Personal Information</h2>
        <div class="info-detail"><p><strong>First Name:</strong> <span id="infoFirstName"><?= htmlspecialchars($user['FirstName'] ?? '') ?></span></p></div>
        <div class="info-detail"><p><strong>Middle Name:</strong> <span id="infoMiddleName"><?= htmlspecialchars($user['MiddleName'] ?? '') ?></span></p></div>
        <div class="info-detail"><p><strong>Last Name:</strong> <span id="infoLastName"><?= htmlspecialchars($user['LastName'] ?? '') ?></span></p></div>
        <div class="info-detail"><p><strong>Username:</strong> <span id="infoUsername"><?= htmlspecialchars($user['Username'] ?? '') ?></span></p></div>
        <div class="info-detail"><p><strong>Email:</strong> <span id="infoEmail"><?= htmlspecialchars($user['Email'] ?? '') ?></span></p></div>
    </div>
  </div>

  <div id="edit-profile" class="section">
    <div class="edit-profile-form">
        <h2>Edit Your Profile</h2>
        <form id="editProfileForm">
            <label for="editFirstName">First Name:</label>
            <input type="text" id="editFirstName" name="firstName" required />

            <label for="editMiddleName">Middle Name:</label>
            <input type="text" id="editMiddleName" name="middleName" />

            <label for="editLastName">Last Name:</label>
            <input type="text" id="editLastName" name="lastName" required />

            <label for="editUsername">Username:</label>
            <input type="text" id="editUsername" name="username" required />

            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="email" required />

            <label for="editPassword">New Password (leave blank to keep current):</label>
            <input type="password" id="editPassword" name="password" />

            <button type="submit">Save Changes</button>
        </form>
    </div>
  </div>

  <div id="transactions" class="section">
    <?php
    $xmlFile = 'transactions.xml';
    $transactions = [];

    if (!file_exists($xmlFile)) {
        echo "<p style='color:red; text-align:center;'>Error: 'transactions.xml' not found. Please ensure the file exists in the same directory as account.php.</p>";
        error_log("account.php: transactions.xml not found at path: " . realpath($xmlFile));
    } else {
        $xml = simplexml_load_file($xmlFile);
        if ($xml === false) {
            echo "<p style='color:red; text-align:center;'>Error: Failed to load 'transactions.xml'. Check file syntax or permissions.</p>";
            error_log("account.php: Failed to load transactions.xml. Errors: " . print_r(libxml_get_errors(), true));
            libxml_clear_errors();
        } else {
            $sessionUsernameLower = strtolower(trim($username));

            foreach ($xml->transaction as $transaction) {
                $transactionUsernameLower = isset($transaction->username) ? strtolower(trim((string)$transaction->username)) : '';

                if ($transactionUsernameLower === $sessionUsernameLower) {
                    $transactions[] = $transaction;
                }
            }
        }
    }
    ?>

    <div id="cancelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
         background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">
      <div style="background:#fff; padding:20px; border-radius:8px; max-width:300px; text-align:center;">
        <p>Are you sure you want to cancel this order?</p>
        <button id="confirmCancel" style="margin-right:10px;">Yes</button>
        <button id="cancelCancel">No</button>
      </div>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
      <thead style="background:#000; color:#fff;">
        <tr>
          <th>Transaction ID</th>
          <th>Date Ordered</th>
          <th>Items</th>
          <th>Quantity</th>
          <th>Size</th>
          <th>Color</th>
          <th>Total (₱)</th>
          <th>Shipping Address</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($transactions)): ?>
          <tr><td colspan="10" style="text-align:center;">No transactions found for this user.</td></tr>
        <?php else: ?>
          <?php foreach ($transactions as $transaction): ?>
            <?php
              $totalQuantity = 0;
              foreach ($transaction->items->item as $item) {
                $totalQuantity += (int)$item->quantity;
              }

              $sizes = [];
              $colors = [];
              foreach ($transaction->items->item as $item) {
                $sizes[] = (string)$item->size;
                $colors[] = (string)$item->color;
              }
              $sizes = implode(', ', array_unique($sizes));
              $colors = implode(', ', array_unique($colors));

              $address = $transaction->shipping_address;
              $fullAddress = htmlspecialchars("{$address->house_no}, {$address->street}, {$address->brgy}, {$address->city}, {$address->province}, {$address->country}, {$address->postal_code}");

              $status = isset($transaction->status) ? (string)$transaction->status : 'Pending';
              
              $disabled = (strtolower($status) === 'cancelled' || strtolower($status) === 'approved' || strtolower($status) === 'declined') ? 'disabled' : '';
              
              $dateOrderedRaw = isset($transaction->checkout_datetime) ? (string)$transaction->checkout_datetime : '';
              $dateOrdered = $dateOrderedRaw ? date("F j, Y", strtotime($dateOrderedRaw)) : 'N/A';
            ?>
            <tr id="row-<?= htmlspecialchars($transaction->id) ?>">
              <td><?= htmlspecialchars($transaction->id) ?></td>
              <td><?= htmlspecialchars($dateOrdered) ?></td>

              <td>
                <?php foreach ($transaction->items->item as $item): ?>
                  <div style="display:flex; align-items:center; margin-bottom:8px;">
                    <img src="<?= htmlspecialchars($item->photo) ?>" alt="<?= htmlspecialchars($item->name) ?>" style="width:50px; height:50px; object-fit:cover; margin-right:10px; border-radius:4px; border:1px solid #ccc;" />
                    <div>
                      <div><strong><?= htmlspecialchars($item->name) ?></strong></div>
                      <div>₱<?= number_format((float)$item->price, 2) ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </td>

              <td><?= $totalQuantity ?></td>
              <td><?= htmlspecialchars($sizes) ?></td>
              <td><?= htmlspecialchars($colors) ?></td>
              <td>₱<?= number_format((float)$transaction->total, 2) ?></td>
              <td><?= $fullAddress ?></td>
              <td class="status-cell"><?= htmlspecialchars(ucfirst($status)) ?></td>
              <td>
                <button class="cancelBtn" data-id="<?= htmlspecialchars($transaction->id) ?>" <?= $disabled ?>>Cancel Order</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <script>
      const modal = document.getElementById('cancelModal');
      const confirmBtn = document.getElementById('confirmCancel');
      const cancelBtn = document.getElementById('cancelCancel');

      let currentTransactionId = null;
      let currentButton = null;

      document.querySelectorAll('.cancelBtn').forEach(button => {
        button.addEventListener('click', () => {
          currentTransactionId = button.getAttribute('data-id');
          currentButton = button;
          modal.style.display = 'flex';
        });
      });

      cancelBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        currentTransactionId = null;
        currentButton = null;
      });

      confirmBtn.addEventListener('click', async () => {
        if (!currentTransactionId) return;

        try {
            const response = await fetch('cancel_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(currentTransactionId)
            });
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            if (data.success) {
                const row = document.getElementById('row-' + currentTransactionId);
                if (row) {
                    const statusCell = row.querySelector('.status-cell');
                    statusCell.textContent = 'Cancelled';
                    currentButton.disabled = true;
                    currentButton.textContent = 'Cancelled';
                }
                modal.style.display = 'none';
                currentTransactionId = null;
                currentButton = null;
                alert(data.message);
            } else {
                alert('Failed to cancel order: ' + data.message);
            }
        } catch (error) {
            alert('Error occurred while cancelling order: ' + error.message);
            console.error('Cancel order fetch error:', error);
        }
      });
    </script>
  </div>
  
  <div id="address" class="section">
     
<?php

?>

<h2>Your Addresses</h2>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-bottom:20px;">
    <thead>
        <tr>
        <th>House No.</th>
        <th>Street</th>
        <th>Brgy</th>
        <th>City</th>
        <th>Province</th>
        <th>Country</th>
        <th>Postal Code</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody id="address-tbody">
        <tr><td colspan="8" style="text-align:center;">Loading addresses...</td></tr>
    </tbody>
</table>

<button id="addAddressBtn" class="upload-btn">Add New Address</button>

<div id="addressModal" class="modal">
  <form id="addressForm" style="background:#fff; padding:20px; border-radius:10px; width:350px; position:relative;">
    <h3 id="modalTitle">Add New Address</h3>
    <input type="hidden" name="action" id="formAction" value="add">
    <input type="hidden" name="index" id="editIndex" value="">
    
    <label>House No.:</label><br>
    <input type="text" name="house_no" id="houseNo" required><br><br>
    
    <label>Street:</label><br>
    <input type="text" name="street" id="street" required><br><br>
    
    <label>Barangay (Brgy):</label><br>
    <input type="text" name="brgy" id="brgy" required><br><br>
    
    <label>City:</label><br>
    <input type="text" name="city" id="city" required><br><br>

    <label>Province:</label><br>
    <input type="text" name="province" id="province" required><br><br>
    
    <label>Country:</label><br>
    <input type="text" name="country" id="country" required><br><br>
    
    <label>Postal Code:</label><br>
    <input type="text" name="postal_code" id="postalCode" required><br><br>
    
    <button type="submit" class="upload-btn" style="width:100%;">Save</button>
    <button type="button" id="closeModal" style="margin-top:10px; width:100%; background:#999;">Cancel</button>
  </form>
</div>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.content .section');
    const editProfileForm = document.getElementById('editProfileForm');
    const logoutBtn = document.getElementById('logout');

    const addressTbody = document.getElementById('address-tbody');
    const addAddressBtn = document.getElementById('addAddressBtn');
    const addressModal = document.getElementById('addressModal');
    const closeModalBtn = document.getElementById('closeModal');
    const addressForm = document.getElementById('addressForm');
    const modalTitle = document.getElementById('modalTitle');
    const formAction = document.getElementById('formAction');
    const editIndex = document.getElementById('editIndex');

    const houseNoInput = document.getElementById('houseNo');
    const streetInput = document.getElementById('street');
    const brgyInput = document.getElementById('brgy');
    const cityInput = document.getElementById('city');
    const provinceInput = document.getElementById('province');
    const countryInput = document.getElementById('country');
    const postalCodeInput = document.getElementById('postalCode');


    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            sidebarLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(s => s.style.display = 'none');

            this.classList.add('active');
            const targetSectionId = this.getAttribute('data-section');
            const targetSection = document.getElementById(targetSectionId);
            if (targetSection) {
                targetSection.style.display = 'block';

                if (targetSectionId === 'edit-profile') {
                    populateEditProfileForm();
                } else if (targetSectionId === 'transactions') {
                } else if (targetSectionId === 'address') {
                    loadAddresses();
                }
            }
        });
    });

    function populateEditProfileForm() {
        document.getElementById('infoFirstName').textContent = '<?= htmlspecialchars($user['FirstName'] ?? '') ?>';
        document.getElementById('infoMiddleName').textContent = '<?= htmlspecialchars($user['MiddleName'] ?? '') ?>';
        document.getElementById('infoLastName').textContent = '<?= htmlspecialchars($user['LastName'] ?? '') ?>';
        document.getElementById('infoUsername').textContent = '<?= htmlspecialchars($user['Username'] ?? '') ?>';
        document.getElementById('infoEmail').textContent = '<?= htmlspecialchars($user['Email'] ?? '') ?>';

        document.getElementById('editFirstName').value = '<?= htmlspecialchars($user['FirstName'] ?? '') ?>';
        document.getElementById('editMiddleName').value = '<?= htmlspecialchars($user['MiddleName'] ?? '') ?>';
        document.getElementById('editLastName').value = '<?= htmlspecialchars($user['LastName'] ?? '') ?>';
        document.getElementById('editUsername').value = '<?= htmlspecialchars($user['Username'] ?? '') ?>';
        document.getElementById('editEmail').value = '<?= htmlspecialchars($user['Email'] ?? '') ?>';
        document.getElementById('editPassword').value = '';
    }

    if (editProfileForm) {
        editProfileForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            try {
                const response = await fetch('update_profile.php', {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                });
                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    document.getElementById('infoFirstName').textContent = data.data.FirstName;
                    document.getElementById('infoMiddleName').textContent = data.data.MiddleName;
                    document.getElementById('infoLastName').textContent = data.data.LastName;
                    document.getElementById('infoUsername').textContent = data.data.Username;
                    document.getElementById('infoEmail').textContent = data.data.Email;
                    
                    document.getElementById('editPassword').value = ''; 
                } else {
                    alert('Profile update failed: ' + data.message);
                }
            } catch (error) {
                alert('Error updating profile: ' + error.message);
                console.error('Profile update error:', error);
            } finally {
                submitButton.disabled = false;
            }
        });
    }

    document.querySelector('.sidebar a[data-section="info"]').click();

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = 'logout.php';
            }
        });
    }

    function loadAddresses() {
        if (!addressTbody) {
            console.error("Error: #address-tbody element not found.");
            return;
        }
        addressTbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Loading addresses...</td></tr>';

        fetch('get_addresses.php')
            .then(response => {
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error(`PHP file 'get_addresses.php' not found. Check path and filename.`);
                    }
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(addresses => {
                addressTbody.innerHTML = '';

                if (addresses.length === 0) {
                    addressTbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No addresses found.</td></tr>';
                    return;
                }

                addresses.forEach((addr, index) => {
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-index', index);
                    tr.innerHTML = `
                        <td>${htmlspecialchars(addr.house_no)}</td>
                        <td>${htmlspecialchars(addr.street)}</td>
                        <td>${htmlspecialchars(addr.brgy)}</td>
                        <td>${htmlspecialchars(addr.city)}</td>
                        <td>${htmlspecialchars(addr.province)}</td>
                        <td>${htmlspecialchars(addr.country)}</td>
                        <td>${htmlspecialchars(addr.postal_code)}</td>
                        <td>
                            <button class="edit-btn" data-index="${index}">Edit</button>
                            <button class="delete-btn" data-index="${index}">Delete</button>
                        </td>
                    `;
                    addressTbody.appendChild(tr);
                });
                attachAddressActionEvents();
            })
            .catch(error => {
                console.error('Error loading addresses:', error);
                addressTbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:red;">Failed to load addresses. Error: ${error.message}</td></tr>`;
            });
    }

    function htmlspecialchars(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }


    function attachAddressActionEvents() {
        document.querySelectorAll('#address table .edit-btn').forEach(button => {
            button.onclick = (e) => {
                const row = e.target.closest('tr');
                const index = row.dataset.index;
                houseNoInput.value = row.cells[0].innerText;
                streetInput.value = row.cells[1].innerText;
                brgyInput.value = row.cells[2].innerText;
                cityInput.value = row.cells[3].innerText;
                provinceInput.value = row.cells[4].innerText;
                countryInput.value = row.cells[5].innerText;
                postalCodeInput.value = row.cells[6].innerText;

                modalTitle.textContent = 'Edit Address';
                formAction.value = 'edit';
                editIndex.value = index;
                addressModal.style.display = 'flex';
            };
        });

        document.querySelectorAll('#address table .delete-btn').forEach(button => {
            button.onclick = (e) => {
                if (confirm('Are you sure you want to delete this address?')) {
                    const index = e.target.closest('tr').dataset.index;
                    fetch('delete_address.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'index=' + index
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error(`PHP file 'delete_address.php' not found. Check path and filename.`);
                            }
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            loadAddresses();
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting address:', error);
                        alert('An error occurred while deleting address: ' + error.message);
                    });
                }
            };
        });
    }

    if (addAddressBtn) {
        addAddressBtn.onclick = () => {
            addressForm.reset();
            modalTitle.textContent = 'Add New Address';
            formAction.value = 'add';
            editIndex.value = '';
            addressModal.style.display = 'flex';
        };
    }

    if (closeModalBtn) {
        closeModalBtn.onclick = () => {
            addressModal.style.display = 'none';
        };
    }
    if (addressModal) {
        window.addEventListener('click', (e) => {
            if (e.target === addressModal) {
                addressModal.style.display = 'none';
            }
        });
    }

    if (addressForm) {
        addressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = formAction.value;
            const url = (action === 'add') ? 'add_address.php' : 'update_address.php';

            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    addressModal.style.display = 'none';
                    loadAddresses();
                }
            })
            .catch(error => console.error('Error submitting address:', error));
        });
    }
});
</script>


</body>
</html>