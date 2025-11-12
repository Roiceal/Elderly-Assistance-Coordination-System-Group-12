<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send SMS via iProgSMS</title>
</head>
<body>
  <h2>Send SMS Message</h2>
  <form action="send_sms.php" method="POST">
    <label>Recipient Number (e.g. 639XXXXXXXXX):</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Message:</label><br>
    <textarea name="message" rows="4" cols="40" required></textarea><br><br>

    <button type="submit">Send Message</button>
  </form>
</body>
</html>
