// Toggle password visibility
document.querySelectorAll(".toggle-password").forEach((toggle) => {
  toggle.addEventListener("click", function () {
    const input = this.parentElement.querySelector("input");
    if (input.type === "password") {
      input.type = "text";
      this.textContent = "🙈";
    } else {
      input.type = "password";
      this.textContent = "👁️";
    }
  });
});

// Toggle checkbox
document.getElementById("termsCheckbox").addEventListener("click", function () {
  this.classList.toggle("checkbox-checked");
});

// Tab switching (optional)
function switchTab(tab) {
  const tabs = document.querySelectorAll(".tab");
  tabs.forEach((t) => t.classList.remove("active"));
  if (tab === "signin") {
    tabs[0].classList.add("active");
    // You can redirect or show sign-in form here
    alert("Switching to Sign In...");
  } else {
    tabs[1].classList.add("active");
  }
}

document.getElementById("signup").addEventListener("click", function (e) {
  e.preventDefault();

  const name = trim(document.getElementById("name").value);
  const email = trim(document.getElementById("mail").value);
  const password = trim(document.getElementById("password").value);
  const confirmPass = trim(document.getElementById("confirm_pass").value);

  if (password !== confirmPass) {
    alert("Passwords do not match!");
    return;
  }
  const formData = new URLSearchParams();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("password", password);

  fetch("signup.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: formData.toString(),
  })
    .then((response) => response.text())
    .then((data) => alert(data))
    .catch((error) => console.error("Error:", error));
});
