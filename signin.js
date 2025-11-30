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

document.getElementById("signinbtn").addEventListener("click", function (e) {
  e.preventDefault();

  const emailOrnum = document.getElementById("emailornum").value;
  const password = document.getElementById("password").value;

  const formData = new URLSearchParams();
  formData.append("emailOrnum", emailOrnum);
  formData.append("password", password);

  fetch("signin.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: formData.toString()
  })
  .then(response => response.text())
  .then(data => alert(data))
  .catch(error => console.error("Error:", error));
});
