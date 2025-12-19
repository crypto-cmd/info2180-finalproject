document.addEventListener("DOMContentLoaded", () => {
  const resultDiv = document.querySelector("#result");

  // Helper to load content
  function loadPage(url) {
    fetch(`pages/${url}`)
      .then((res) => res.text())
      .then((data) => {
        resultDiv.innerHTML = data;
      })
      .catch((err) => console.error("Error:", err));
  }

  // Default Load
  loadPage("dashboard.php");

  // Sidebar Navigation
  document.querySelector("#nav-home").addEventListener("click", (e) => {
    e.preventDefault();
    loadPage("dashboard.php");
  });

  document.querySelector("#nav-new-contact").addEventListener("click", (e) => {
    e.preventDefault();
    loadPage("new_contact.php");
  });

  // Dashboard '+ Add Contact' button (header)
  const addContactBtn = document.querySelector("#nav-new-contact-btn");
  if (addContactBtn) {
    addContactBtn.addEventListener("click", (e) => {
      e.preventDefault();
      loadPage("new_contact.php");
    });
  }

  document.querySelector("#nav-users").addEventListener("click", (e) => {
    e.preventDefault();
    loadPage("users.php");
  });

  document.addEventListener("click", function (e) {
    const target = e.target;
    // 1. Handle "View" link on Dashboard
    if (target?.textContent === "View") {
      e.preventDefault();
      const href = e.target.getAttribute("href"); // e.g., "view_contact.php?id=1"
      if (href?.includes("view_contact.php")) {
        loadPage(href);
      }
    }

    // 2. Handle header '+ Add Contact' Button (delegated)
    if (target?.id === "nav-new-contact-btn") {
      e.preventDefault();
      loadPage("new_contact.php");
    }

    // 3. Handle "+ Add User" Button
    if (target?.id === "add-user-btn") {
      e.preventDefault();
      loadPage("new_user.php");
    }
    // 4. Handle "Assign to me" Button
    if (target?.id === "btn-assign-to-me") {
      e.preventDefault();
      const contactId = target.getAttribute("data-id");
      const formData = new FormData();
      formData.append("contact_id", contactId);
      formData.append("action", "assign_to_me");

      fetch("api/update_contact_handler.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "Success") {
            loadPage(`view_contact.php?id=${contactId}`); // Reload to see changes
          } else {
            console.log(data);
            alert("Error assigning contact.");
          }
        });
    }

    // 4. Handle "Switch Type" Button
    if (target?.id === "btn-switch-type") {
      e.preventDefault();
      const contactId = target.getAttribute("data-id");
      const formData = new FormData();
      formData.append("contact_id", contactId);
      formData.append("action", "switch_type");

      fetch("api/update_contact_handler.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "Success") {
            loadPage(`view_contact.php?id=${contactId}`); // Reload to see changes
          } else {
            alert("Error switching type.");
          }
        });
    }
    // 5. Handle Dashboard Filter Clicks
    if (target.classList.contains("filter-btn")) {
      e.preventDefault();
      const url = target.getAttribute("href");
      loadPage(url); // load 'dashboard.php?filter=Support', etc.
    }
  });

  document.addEventListener("submit", function (e) {
    const target = e.target;
    //  New Contact Form
    if (target.id === "new-contact-form") {
      e.preventDefault();
      const formData = new FormData(target);
      fetch("api/add_contact_handler.php", { method: "POST", body: formData })
        .then((res) => res.text())
        .then((data) => {
          if (data.includes("Success")) {
            document.querySelector("#msg").innerHTML =
              '<p class="success">Success!</p>';
            target.reset();
          } else {
            document.querySelector(
              "#msg"
            ).innerHTML = `<p class="error">${data}</p>`;
          }
        });
    }

    // New User Form
    if (target.id === "new-user-form") {
      e.preventDefault();
      const formData = new FormData(target);
      fetch("api/add_user_handler.php", { method: "POST", body: formData })
        .then((res) => res.text())
        .then((data) => {
          if (data.includes("Success")) {
            document.querySelector("#msg").innerHTML =
              '<p class="success">Success!</p>';
            e.target.reset();
          } else {
            document.querySelector(
              "#msg"
            ).innerHTML = `<p class="error">${data}</p>`;
          }
        });
    }

    // C. Add Note Form
    if (e.target.id === "add-note-form") {
      e.preventDefault();

      // 1. Grab the ID *before* sending, directly from the input field
      const idInput = e.target.querySelector('input[name="contact_id"]');
      const contactId = idInput ? idInput.value : null;

      if (!contactId) {
        alert("Error: Could not find Contact ID in form.");
        return;
      }

      const formData = new FormData(e.target);

      fetch("api/add_note_handler.php", { method: "POST", body: formData })
        .then((res) => res.text())
        .then((data) => {
          // Check if the response contains "Success"
          if (data.trim().includes("Success")) {
            // 2. Reload the view using the ID we captured at the start
            loadPage(`view_contact.php?id=${contactId}`);
          } else {
            document.querySelector(
              "#note-msg"
            ).innerHTML = `<p class="error">${data}</p>`;
          }
        })
        .catch((err) => console.error(err));
    }
  });
});
