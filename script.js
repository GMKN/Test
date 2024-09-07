document.getElementById('uploadForm').addEventListener('submit', function(event) {
  // Prevent the default form submission
  event.preventDefault();

  // Get the register number value
  const regNumberInput = document.getElementById('regNumber');
  const regNumber = parseInt(regNumberInput.value, 10);
  const regNumberError = document.getElementById('regNumberError');

  // Validate the register number
  if (isNaN(regNumber) || regNumber < 511323243001 || regNumber >= 511323243065) {
    regNumberError.style.display = 'inline';
    return; // Stop the form submission
  } else {
    regNumberError.style.display = 'none';
  }

  // Get reCAPTCHA response
  const recaptchaResponse = grecaptcha.getResponse();
  if (recaptchaResponse.length === 0) {
    alert('Please complete the CAPTCHA.');
    return;
  }

  // Collect form data
  const formData = new FormData(this);
  formData.append('recaptcha_response', recaptchaResponse);

  // Send the form data using fetch
  fetch('upload.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    alert(result.message);
    grecaptcha.reset(); // Reset CAPTCHA after successful submission
  })
  .catch(error => {
    alert('Error: ' + error.message);
  });
});
