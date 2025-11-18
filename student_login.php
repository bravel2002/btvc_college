<?php
session_start();
include('config/db_connect.php');

$error = ""; // initialize error variable

if (isset($_POST['login'])) {
    $admission = mysqli_real_escape_string($conn, $_POST['admission']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM students WHERE admission = '$admission' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
        $_SESSION['admission'] = $student['admission'];
        $_SESSION['fullname'] = $student['fullname'];
        $_SESSION['course'] = $student['course'];
        $_SESSION['year_of_study'] = $student['year_of_study'];

        // Redirect to dashboard
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid admission number or password.";
    }
}
?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Bunyala Student Portal</title>
        <link rel="stylesheet" href="studentlogin_style.css">
    </head>
    <body>
        <div class="login-container">
            <div class="login-box">
                <div class="login-header">
                    <h2>Bunyala Student Portal</h2>
                    <img src="image/logo.png" alt="College Logo" class="logo">
                </div>
                <form action="" method="POST">
                    <label>Admission Number:</label>
                    <input type="text" name="admission" placeholder="Enter admission number" required>

                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Enter password" required>

                    <div class="btn-group">
                        <button type="button" class="forgot-btn">Forgot Password?</button>
                        <button type="submit" name="login" class="login-btn">Sign In</button>
                    </div>

                    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
                </form>
            </div>
        </div>
    </body>
    </html>
