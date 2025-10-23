document.addEventListener("DOMContentLoaded", function() {
  const sections = document.querySelectorAll(".section");
  const sidebarLinks = document.querySelectorAll(".sidebar a[data-section]");

  sidebarLinks.forEach(link => {
    link.addEventListener("click", function(e) {
      e.preventDefault();
      const target = this.getAttribute("data-section");
      if (!target) return;

      // Toggle active sidebar link
      sidebarLinks.forEach(l => l.classList.remove("active"));
      this.classList.add("active");

      // Show correct section
      sections.forEach(sec => {
        sec.classList.toggle("active", sec.id === target);
      });
    });
  });

  // Logout link
  document.getElementById("logout").addEventListener("click", function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to logout?")) {
      window.location.href = "logout.php";
    }
  });

  // ----- Address Section Logic -----
  const addAddressBtn = document.getElementById("addAddressBtn");
  const addressModal = document.getElementById("addressModal");
  const addressForm = document.getElementById("addressForm");
  const modalTitle = document.getElementById("modalTitle");
  const formAction = document.getElementById("formAction");
  const editIndexInput = document.getElementById("editIndex");

  // Fields
  const houseNo = document.getElementById("houseNo");
  const street = document.getElementById("street");
  const brgy = document.getElementById("brgy");
  const city = document.getElementById("city");
  const country = document.getElementById("country");
  const postalCode = document.getElementById("postalCode");

  // Open modal to add new address
  addAddressBtn.addEventListener("click", () => {
    modalTitle.textContent = "Add New Address";
    formAction.value = "add";
    editIndexInput.value = "";
    addressForm.reset();
    addressModal.classList.add("active");
  });

  // Close modal button
  document.getElementById("closeModal").addEventListener("click", () => {
    addressModal.classList.remove("active");
  });

  // Handle form submit for add/edit
  addressForm.addEventListener("submit", function(e) {
    e.preventDefault();

    const data = new FormData(addressForm);

    fetch('save_address.php', {
      method: 'POST',
      body: data,
    })
    .then(response => response.json())
    .then(res => {
      if (res.success) {
        window.location.reload();
      } else {
        alert("Error: " + res.message);
      }
    })
    .catch(err => {
      alert("AJAX error: " + err);
    });
  });

  // ------- Delete Confirmation Modal Logic -------
  let deleteIndex = null;
  const deleteModal = document.createElement("div");
  deleteModal.id = "deleteModal";
  deleteModal.className = "modal";
  deleteModal.innerHTML = `
    <div style="background:#fff; padding:20px; border-radius:10px; width:300px; text-align:center;">
      <h3>Delete Address</h3>
      <p>Are you sure you want to delete this address?</p>
      <button id="confirmDelete" class="upload-btn" style="background-color:#c0392b; width:100%; margin-bottom:10px;">Yes, Delete</button>
      <button id="cancelDelete" class="upload-btn" style="background-color:#777; width:100%;">Cancel</button>
    </div>
  `;
  document.body.appendChild(deleteModal);

  document.getElementById("cancelDelete").addEventListener("click", () => {
    deleteModal.classList.remove("active");
    deleteIndex = null;
  });

  document.getElementById("confirmDelete").addEventListener("click", () => {
    if (deleteIndex !== null) {
      fetch('delete_address.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ index: deleteIndex })
      })
      .then(response => response.json())
      .then(res => {
        if (res.success) {
          // No alert here per your request
          window.location.reload();
        } else {
          alert("Error deleting address: " + res.message);
        }
      })
      .catch(err => {
        alert("AJAX error: " + err);
      });
    }
    deleteModal.classList.remove("active");
  });

  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      deleteIndex = btn.getAttribute("data-index");
      deleteModal.classList.add("active");
    });
  });

  // Edit buttons click
  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const idx = btn.getAttribute("data-index");
      const row = btn.closest("tr");

      modalTitle.textContent = "Edit Address";
      formAction.value = "edit";
      editIndexInput.value = idx;

      houseNo.value = row.children[0].textContent;
      street.value = row.children[1].textContent;
      brgy.value = row.children[2].textContent;
      city.value = row.children[3].textContent;
      country.value = row.children[4].textContent;
      postalCode.value = row.children[5].textContent;

      addressModal.classList.add("active");
    });
  });

});


