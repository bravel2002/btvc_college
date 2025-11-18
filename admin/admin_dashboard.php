
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyCollege</title>
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- Topbar -->
<header class="topbar">
    <div class="portal-name">MyCollege Admin Portal</div>
    <div class="admin-info">
        <span>Hi, <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋</span>
        <a href="logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</header>

<!-- Sidebar Navigation -->
<aside class="sidebar">
    <div class="nav-title">Navigation</div>
    <nav>
        <ul>
            <li onclick="loadPage('add_student.php')"><i class="fa fa-user-plus"></i> Add Student</li>
            <li onclick="loadPage('view_students.php')"><i class="fa fa-users"></i> View Students</li>
            <li onclick="loadPage('manage_courses.php')"><i class="fa fa-book"></i> Manage Courses</li>
            <li onclick="loadPage('financials.php')"><i class="fa fa-wallet"></i> Financials</li>
            <li onclick="loadPage('admin_upload_exam_card.php')"><i class="fa fa-file-pdf"></i> Upload Exam Card</li>
            <li onclick="loadPage('settings.php')"><i class="fa fa-cogs"></i> Settings</li>
        </ul>
    </nav>
</aside>

<!-- Main content -->
<main class="main-content" id="main-content">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h2>
    <p>Select an option from the sidebar to manage.</p>
</main>

<!-- SPA JS -->
<script src="admindashboard.js"></script>

</body>
</html>
