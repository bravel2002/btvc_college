<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['admission'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BTVC Student Dashboard</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Reset & base */
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f6fa;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Topbar */
.topbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: #004aad;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 30px;
    z-index: 1000;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
.portal-name { font-weight: bold; font-size: 20px; }
.user-actions span { margin-right: 15px; }
.user-actions a { color: #fff; text-decoration: none; font-weight: 500; }
.user-actions a:hover { text-decoration: underline; }

/* Sidebar */
.sidebar {
    width: 250px;
    background: #003080;
    color: #fff;
    height: 100vh;
    position: fixed;
    top: 60px;
    left: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* Sidebar top content */
.sidebar-top {
    text-align: center;
    padding: 20px 10px;
    flex-shrink: 0;
}
.logo-img { width: 80px; margin-bottom: 10px; }
.student-name { margin-bottom: 5px; font-size: 15px; }
.online-status { font-size: 12px; }
.online-status .dot { height: 8px; width: 8px; background-color: #0f0; border-radius: 50%; display: inline-block; margin-right: 5px; }

/* Nav title */
.nav-title {
    padding: 10px 20px;
    font-weight: bold;
    font-size: 13px;
    color: #ffd700;
    flex-shrink: 0;
}

/* Scrollable nav list */
.sidebar nav { flex: 1; overflow-y: auto; }
.sidebar nav ul { list-style: none; padding: 0; }
.sidebar nav ul li {
    padding: 10px 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: 0.3s;
    font-size: 14px;
}
.sidebar nav ul li i { margin-right: 10px; }
.sidebar nav ul li:hover { background: #002060; border-radius: 6px; }

/* Dropdown menu - vertical */
.dropdown .dropdown-menu {
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    background: #002060;
    width: 100%;
    flex-direction: column; /* vertical stack */
}
.dropdown-menu li {
    padding: 8px 30px;
    font-size: 13px;
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    cursor: pointer;
    border-radius: 4px;
}
.dropdown-menu li i { margin-right: 8px; }
.dropdown-menu li span { word-wrap: break-word; flex: 1; }
.dropdown-menu li:hover { background: #0050b0; }

/* Main content */
.main-content {
    margin-left: 250px;
    flex: 1;
    padding: 20px 30px;
    min-height: calc(100vh - 60px);
    overflow-y: auto;
    background: #f5f6fa;
}

/* Footer */
footer {
    background:#004aad;
    color:#fff;
    text-align:center;
    padding:15px 0;
    font-size:14px;
}
footer a { color:#ffd700; text-decoration:none; }
footer a:hover { text-decoration:underline; }

/* Dropdown caret pointer */
.drop-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; }

/* Responsive */
@media (max-width:768px) {
    .sidebar { width: 60px; }
    .sidebar .nav-title, .sidebar .drop-header span { display:none; }
    .main-content { margin-left: 60px; padding: 20px 15px; }
}
</style>
</head>
<body>

<!-- Topbar -->
<header class="topbar">
    <div class="portal-name">BTVC STUDENT PORTAL</div>
    <div class="user-actions">
        <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</span>
        <a href="student_logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</header>

<!-- Dashboard wrapper -->
<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <img src="image/logo.png" alt="School Logo" class="logo-img">
            <h3 class="student-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></h3>
            <div class="online-status"><span class="dot"></span> Online</div>
        </div>

        <div class="nav-title">Main Navigation</div>

        <nav>
            <ul>
                <li onclick="loadPage('idashboard.php')"><i class="fa fa-home"></i> Dashboard</li>

                <!-- Financials -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-wallet"></i> Financials <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('fee_payment.php')"><i class="fa fa-credit-card"></i> Fee Payment</li>
                        <li onclick="loadPage('view_fees.php')"><i class="fa fa-file-invoice-dollar"></i> View Fee Records</li>
                    </ul>
                </li>

                <!-- Academics -->
                <li class="dropdown">
    <div class="drop-header"><i class="fa fa-book"></i> Academics <i class="fa fa-caret-down"></i></div>
    <ul class="dropdown-menu">
        <li onclick="loadPage('class_timetable.php')"><i class="fa fa-calendar"></i> Class Timetable</li>
        <li onclick="loadPage('register_units.php')"><i class="fa fa-graduation-cap"></i> Register Units</li>
        <li onclick="loadPage('assignments.php')"><i class="fa fa-tasks"></i> Assignments</li>
        <li onclick="loadPage('exam_card.php')"><i class="fa fa-id-card"></i> Exam Card</li>
    </ul>
</li>

                <!-- Graduation -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-graduation-cap"></i> Graduation <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('apply_graduation.php')"><i class="fa fa-file-signature"></i> Apply for Graduation</li>
                        <li onclick="loadPage('graduation_status.php')"><i class="fa fa-info-circle"></i> Graduation Status</li>
                        <li onclick="loadPage('graduation_fee.php')"><i class="fa fa-credit-card"></i> Graduation Fee</li>
                        <li onclick="loadPage('graduation_list.php')"><i class="fa fa-list"></i> Graduation List</li>
                    </ul>
                </li>

                <!-- Student Requests -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-envelope"></i> Student Requests <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('deferment.php')"><i class="fa fa-file-text"></i> Deferment</li>
                    </ul>
                </li>

                <!-- Downloads -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-download"></i> Downloads <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('Transfer_Letter.php')"><i class="fa fa-file-pdf"></i> Transfer Letter</li>
                    </ul>
                </li>

                <!-- Student Clearance -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-check-circle"></i> Student Clearance <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('graduation_clearance.php')"><i class="fa fa-edit"></i> Graduation Clearance</li>
                    </ul>
                </li>

                <!-- Certificate Issuance -->
                <li class="dropdown">
                    <div class="drop-header"><i class="fa fa-certificate"></i> Certificate Issuance <i class="fa fa-caret-down"></i></div>
                    <ul class="dropdown-menu">
                        <li onclick="loadPage('apply_certificate.php')"><i class="fa fa-pen"></i> Apply for Certificate</li>
                        <li onclick="loadPage('collection_status.php')"><i class="fa fa-box"></i> Collection Status</li>
                    </ul>
                </li>


                <!-- Social Media Panel -->
        <div class="nav-title">Connect With Us</div>
        <div class="social-panel" style="padding: 16px 20px;">
            <a href="https://facebook.com/YourCollegePage" target="_blank" title="Facebook" style="margin-right:15px;">
                <i class="fab fa-facebook fa-lg" style="color: #3b5998;"></i>
            </a>
            <a href="https://twitter.com/YourCollegePage" target="_blank" title="Twitter" style="margin-right:15px;">
                <i class="fab fa-twitter fa-lg" style="color: #00acee;"></i>
            </a>
            <a href="https://wa.me/YourCollegeNumber" target="_blank" title="WhatsApp">
                <i class="fab fa-whatsapp fa-lg" style="color: #25D366;"></i>
            </a>
         </div>
        </nav>
        
    </aside>

    <!-- Main content -->
    <main class="main-content" id="main-content">
        <!-- SPA content loads here -->
    </main>
</div>

<!-- Footer -->
<footer>
    &copy; 2025 BTVC College. Crafted with ❤️ by <a href="#">btech solutions</a>
</footer>

<script>
// Accordion style dropdown: only one open at a time
document.querySelectorAll('.dropdown .drop-header').forEach(header => {
    header.addEventListener('click', () => {
        // Close all other dropdowns
        document.querySelectorAll('.dropdown').forEach(drop => {
            if (drop !== header.parentElement) {
                drop.classList.remove('active');
                drop.querySelector('.dropdown-menu').style.display = 'none';
            }
        });

        // Toggle current dropdown
        const parent = header.parentElement;
        parent.classList.toggle('active');
        const menu = parent.querySelector('.dropdown-menu');
        menu.style.display = parent.classList.contains('active') ? 'flex' : 'none';
        menu.style.flexDirection = 'column';
    });
});

// SPA page loader
function loadPage(page) {
    fetch(page)
    .then(response => response.text())
    .then(data => { document.querySelector('.main-content').innerHTML = data; })
    .catch(error => console.error('Error loading page:', error));
}

// Load Dashboard by default
window.addEventListener('DOMContentLoaded', () => { loadPage('idashboard.php'); });
</script>

</body>
</html>
