
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send SMS via iProgSMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-header bg-primary text-white text-center fs-4 fw-semibold">
            📱 Send SMS via iProgSMS
          </div>
          <div class="card-body p-4">

            <?php if (!empty($responses)): ?>
              <?php foreach ($responses as $res): ?>
                <div class="alert alert-<?= $res['type'] ?> alert-dismissible fade show" role="alert">
                  <?= $res['text'] ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>

            <form action="" method="POST">
              <div class="mb-3">
                <label class="form-label fw-bold">Recipient Number(s)</label>
                <textarea name="phone" class="form-control" rows="2" placeholder="e.g. 639123456789,639876543210" required><?= htmlspecialchars($_POST['phone'] ?? '') ?></textarea>
                <div class="form-text">Separate multiple numbers with commas or spaces.</div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Message</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Type your SMS message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>