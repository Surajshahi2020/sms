<?php
// supporter/access_denied.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Access Denied</title>
  <style> 
    body {
      background: #f8f9fa;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .denied-box {
      text-align: center;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .denied-box h1 {
      font-size: 2rem;
      color: #dc3545;
      margin-bottom: 10px;
    }
    .denied-box p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 20px;
    }
    .countdown {
      font-weight: bold;
      color: #007bff;
    }
  </style>
</head>
<body>
  <div class="denied-box">
    <h1>🚫 Access Denied</h1>
    <p>You do not have permission to view this page.</p>
    <p>Redirecting to index page in <span class="countdown">5</span> seconds...</p>
  </div>

  <script>
    let seconds = 5;
    const countdownEl = document.querySelector('.countdown');

    const interval = setInterval(() => {
      seconds--;
      countdownEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = 'index.php'; // redirect after countdown
      }
    }, 1000);
  </script>
</body>
</html>
