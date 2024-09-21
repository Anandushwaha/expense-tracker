// document.addEventListener("DOMContentLoaded", () => {
//   // Fetch user information and display on dashboard
//   fetch("../php/getUserInfo.php")
//     .then((response) => response.json()) // Parse the JSON response
//     .then((data) => {
//       if (data.name && data.email) {
//         document.getElementById("user-name").innerText = ` ${data.name}`;
//         document.getElementById("user-email").innerText = `${data.email}`;
//       } else {
//         document.getElementById("user-name").innerText = "User not logged in.";
//       }
//     })
//     .catch((error) => console.error("Error fetching user info:", error)); // Log any errors
// });

// document.addEventListener("DOMContentLoaded", function () {
//   const form = document.getElementById("transaction-form");
//   const entryList = document.getElementById("entryList");

//   let offset = 0;
//   const limit = 10;

//   form.addEventListener("submit", function (event) {
//     event.preventDefault(); // Prevent the form from submitting the traditional way

//     const formData = new FormData(form); // Collect form data

//     // Post the form data to add a new transaction
//     fetch("../php/add_transaction.php", {
//       method: "POST",
//       body: formData,
//     })
//       .then((response) => response.text())
//       .then((result) => {
//         console.log(result);
//         updateDashboard(); // Update totals after a transaction is added
//         loadEntries(); // Reload entries to reflect the new transaction
//       })
//       .catch((error) => console.error("Error:", error));
//   });

//   // Function to update the dashboard totals (Income, Expenses, Balance)
//   function updateDashboard() {
//     fetch("../php/dashboard_data.php")
//       .then((response) => response.json())
//       .then((data) => {
//         document.getElementById("totalIncome").textContent =
//           "₹" + data.total_income;
//         document.getElementById("totalExpenses").textContent =
//           "₹" + data.total_expenses;
//         document.getElementById("totalBalance").textContent =
//           "₹" + data.total_balance;
//       })
//       .catch((error) => console.error("Error fetching data:", error));
//   }

//   function loadEntries() {
//     // Clear the existing list to avoid duplicate entries
//     entryList.innerHTML = "";

//     fetch(`../php/fetch_entries.php`)
//       .then((response) => response.json()) // Directly parse as JSON
//       .then((entries) => {
//         entries.forEach((entry) => {
//           const li = document.createElement("li");
//           li.textContent = `${entry.type.toUpperCase()}: ${entry.category} - ₹${
//             entry.amount
//           }`;
//           entryList.appendChild(li);
//         });
//       })
//       .catch((error) => console.error("Error fetching entries:", error));
//   }
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("transaction-form");
  const entryList = document.getElementById("entryList");

  // Fetch user information and display on dashboard
  fetch("../php/getUserInfo.php")
    .then((response) => response.json()) // Parse the JSON response
    .then((data) => {
      if (data.name && data.email) {
        document.getElementById("user-name").innerText = ` ${data.name}`;
        document.getElementById("user-email").innerText = `${data.email}`;
      } else {
        document.getElementById("user-name").innerText = "User not logged in.";
      }
    })
    .catch((error) => console.error("Error fetching user info:", error)); // Log any errors

  // Fetch entries on page load
  loadEntries(); // Call loadEntries to display entries when the page loads

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    const formData = new FormData(form); // Collect form data

    // Post the form data to add a new transaction
    fetch("../php/add_transaction.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((result) => {
        console.log(result);
        updateDashboard(); // Update totals after a transaction is added
        loadEntries(); // Reload entries to reflect the new transaction
      })
      .catch((error) => console.error("Error:", error));
  });

  // Function to update the dashboard totals (Income, Expenses, Balance)
  function updateDashboard() {
    fetch("../php/dashboard_data.php")
      .then((response) => response.json())
      .then((data) => {
        document.getElementById("totalIncome").textContent =
          "₹" + data.total_income;
        document.getElementById("totalExpenses").textContent =
          "₹" + data.total_expenses;
        document.getElementById("totalBalance").textContent =
          "₹" + data.total_balance;
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  function loadEntries() {
    // Clear the existing list to avoid duplicate entries
    entryList.innerHTML = "";

    fetch(`../php/fetch_entries.php`)
      .then((response) => response.json()) // Directly parse as JSON
      .then((entries) => {
        entries.forEach((entry) => {
          const li = document.createElement("li");
          li.textContent = `${entry.type.toUpperCase()}: ${entry.category} - ₹${
            entry.amount
          }`;
          entryList.appendChild(li);
        });
      })
      .catch((error) => console.error("Error fetching entries:", error));
  }

  // Function to add a transaction
  function addTransaction(data) {
    // Perform AJAX request
    fetch("../php/add_transaction.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(data),
    })
      .then((response) => response.json())
      .then((entries) => {
        updateEntriesList(entries); // Update the entry list with new data
        updateTotals(); // Optionally, update totals as well
      })
      .catch((error) => console.error("Error:", error));
  }

  // Function to update the entries list
  function updateEntriesList(entries) {
    const entryList = document.getElementById("entryList");
    entryList.innerHTML = "";

    entries.forEach((entry) => {
      const listItem = document.createElement("li");
      listItem.textContent = `${entry.date}: ${entry.description} - ${entry.amount}`;
      entryList.appendChild(listItem);
    });
  }

  addTransaction(transactionData);
});
