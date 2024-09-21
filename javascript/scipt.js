const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");
const container = document.getElementById("container");

signUpButton.addEventListener("click", () => {
  container.classList.add("right-panel-active");
});

signInButton.addEventListener("click", () => {
  container.classList.remove("right-panel-active");
});
// login for email verfication

document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    const formData = new FormData(this);

    fetch("../php/userauthenticate.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          // Show error message
          document.getElementById("email-error").textContent = data.error;
        } else if (data.success) {
          // Redirect to dashboard on successful login
          window.location.href = data.redirect;
        }
      });
  });
