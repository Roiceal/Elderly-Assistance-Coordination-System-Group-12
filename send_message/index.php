<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send SMS via iProgSMS</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4" style="width: 450px; border-radius: 15px;">

      <h3 class="text-center mb-4">📩 Send SMS Message</h3>

      <form action="send_sms.php" method="POST">

      

        <!-- Message -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Message</label>
          <textarea name="message" class="form-control" rows="4" placeholder="Type your message..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
          Send Message
        </button>

      </form>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
