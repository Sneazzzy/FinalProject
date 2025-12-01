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
  .then(response => {
    // First, get the response as plain text to check it
    return response.text();
  })
  .then(text => {
    try {
      // Try to parse the text as JSON
      const data = JSON.parse(text);
      
      // Now, check the status from the parsed data
      if (data.status === 'success') {
        // If successful, redirect the user
        window.location.href = data.redirect;
      } else {
        // If the JSON indicates an error, show the message
        alert(data.message);
      }
    } catch (error) {
      // If parsing fails, it means the response wasn't valid JSON.
      // This is what was likely happening before. We'll alert the raw text.
      console.error("Failed to parse JSON:", text);
      alert(text); // Show the raw response if it's not JSON
    }
  })
  .catch(error => {
    // Handle any network errors
    console.error('Fetch Error:', error);
    alert('An unexpected network error occurred. Please try again.');
  });
});
