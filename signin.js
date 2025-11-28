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

  if (!emailOrNum || !password) {
});
