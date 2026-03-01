<?php
// supporter/404.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found</title>
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
        .box {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .box h1 {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .box p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 20px;
        }
        .countdown {
            font-weight: bold;
            color: #007bff;
        }
        .box a {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .box a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>404</h1>
        <p>Oops! Page Not Found.</p>
        <p>Redirecting to home page in <span class="countdown">5</span> seconds...</p>
    </div>

    <script>
        let seconds = 5;
        const countdownEl = document.querySelector('.countdown');

        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = 'index.php'; // redirect to home
            }
        }, 1000);
    </script>
</body>
</html>
