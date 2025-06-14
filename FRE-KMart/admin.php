<?php
require_once 'config/database.php';

session_start();

// Check for remember me cookie
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['remember_admin'])) {
    // Verify the cookie value (in a real app, use more secure verification)
    if ($_COOKIE['remember_admin'] === hash('sha256', 'admin_songsong2024')) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin-dashboard.php');
        exit();
    }
}

// Handle admin login
$login_error = '';
if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember_me = isset($_POST['remember_me']);

    // Simple authentication (in real application, use proper password hashing)
    if ($username === 'admin' && $password === 'songsong2024') {
        $_SESSION['admin_logged_in'] = true;

        // Set remember me cookie if checked
        if ($remember_me) {
            // Set cookie for 30 days
            setcookie(
                'remember_admin',
                hash('sha256', 'admin_songsong2024'),
                time() + (30 * 24 * 60 * 60),
                '/',
                '',
                true, // Secure flag
                true  // HttpOnly flag
            );
        }

        header('Location: admin-dashboard.php');
        exit();
    } else {
        $login_error = 'Invalid username or password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }

        .admin-login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .admin-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, #cd2e3a, #0047a0);
        }

        .admin-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .admin-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 20px rgba(205, 46, 58, 0.3);
        }

        .admin-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .admin-subtitle {
            color: #666;
            font-size: 0.95rem;
        }

        .korean-badge {
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin: 1rem auto;
            display: inline-block;
        }

        .admin-form {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #cd2e3a;
            background: white;
            box-shadow: 0 0 0 3px rgba(205, 46, 58, 0.1);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 16px;
            height: 16px;
        }

        .remember-me label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .admin-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(205, 46, 58, 0.3);
        }

        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(205, 46, 58, 0.4);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
            font-size: 0.9rem;
        }

        .security-notice {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(205, 46, 58, 0.1);
            border-radius: 10px;
            color: #666;
            font-size: 0.85rem;
        }

        .back-to-site {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-to-site a {
            color: #cd2e3a;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .back-to-site a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .admin-login-container {
                padding: 2rem;
                margin: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-login-container">
        <div class="admin-header">
            <div class="admin-logo">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1 class="admin-title">Admin Login</h1>
            <div class="korean-badge">송송마트 관리자</div>
            <p class="admin-subtitle">Access the SongSong Mart admin dashboard</p>
        </div>

        <?php if ($login_error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($login_error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="admin.php" class="admin-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="admin-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="security-notice">
            <i class="fas fa-shield-alt"></i>
            This is a secure area. Please log in with your admin credentials.
        </div>

        <div class="back-to-site">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to SongSong Mart
            </a>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>