<?php
// request_otp.php
?>
<!doctype html>
<html>
  <head><meta charset="utf-8"><title>Request OTP</title></head>
  <body>
    <h2>Request OTP</h2>
    <form action="send_otp.php" method="post">
      <label>Phone number (e.g. 09171234567):</label><br>
      <input type="text" name="phone" required><br><br>
      <button type="submit">Send OTP</button>
    </form>
  </body>
</html>
