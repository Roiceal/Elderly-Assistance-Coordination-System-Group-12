<?php
$phone = $_GET['phone'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .otp-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .otp-inputs {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin: 20px 0;
    }

    .otp-inputs input {
      width: 40px;
      height: 50px;
      text-align: center;
      font-size: 24px;
      border: 1px solid #ccc;
      border-radius: 5px;
      outline: none;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .otp-inputs input:focus {
      border-color: #007bff;
      box-shadow: 0 0 5px rgba(0,123,255,0.5);
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="otp-container">
    <h2>Verify OTP</h2>
    <p>OTP sent. Please check your messages.</p>
    <form action="validate_otp.php" method="post" id="otpForm">
      <input type="hidden" name="phone" value="<?=htmlspecialchars($phone)?>">
      <div class="otp-inputs">
        <input type="text" name="otp[]" maxlength="1" required>
        <input type="text" name="otp[]" maxlength="1" required>
        <input type="text" name="otp[]" maxlength="1" required>
        <input type="text" name="otp[]" maxlength="1" required>
        <input type="text" name="otp[]" maxlength="1" required>
        <input type="text" name="otp[]" maxlength="1" required>
      </div>
      <button type="submit">Verify</button>
    </form>
  </div>

  <script>
    const inputs = document.querySelectorAll('.otp-inputs input');

    inputs.forEach((input, index) => {
      input.addEventListener('input', () => {
        if(input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });

      input.addEventListener('keydown', (e) => {
        if(e.key === 'Backspace' && input.value === '' && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    // Optional: combine the 6 digits into a single value on submit
    document.getElementById('otpForm').addEventListener('submit', function(e){
      const otpValues = Array.from(inputs).map(i => i.value).join('');
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'otp_combined';
      hiddenInput.value = otpValues;
      this.appendChild(hiddenInput);
    });
  </script>
</body>
</html>
