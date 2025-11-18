<?php
include('../config/db_connect.php');
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header('location: admin_dashboard.php');
    } else {
        echo "<script>alert('❌ Invalid Username or Password!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MyCollege</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f3f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 380px;
        }

        .logo {
            margin-bottom: 15px;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 25px;
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0,123,255,0.4);
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .footer {
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="image/logo.png" alt="College Logo" class="logo" width="80">
        <h2>Admin Portal</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="footer">
            <p>BTVC College © 2025</p>
        </div>
    </div>
</body>
</html>
