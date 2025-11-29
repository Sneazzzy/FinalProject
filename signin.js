document
  .querySelector(".toggle-password")
  .addEventListener("click", function () {
    const input = this.parentElement.querySelector("input");
    if (input.type === "password") {
      input.type = "text";
      this.textContent = "🙈";
    } else {
      input.type = "password";
      this.textContent = "👁️";
    }
  });

document.getElementById("signin").addEventListener("submit", function (event) {
  event.preventDefault();
  const emailOrNum = document.getElementById("emailornum").value.trim();
  const password = document.getElementById("password").value.trim();

  fetch("/api/signin", {
    method: "POST",
    headers: {  "Content-Type": "application/json"},
    body: JSON.stringify({ emailOrNum, password }),
  })
    .then((response) => response.json()) 
    .then((data) => {
      if (data.success) {
        window.location.href = "/dashboard";
      } else {
        alert(data.message || "Sign-in failed. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error during sign-in:", error);
      alert("An error occurred. Please try again later.");
    });

});
