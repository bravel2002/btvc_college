<?php
include __DIR__ . '/config/db_connect.php';
session_start();

if (!isset($_SESSION['admission'])) {
    echo "Student not logged in.";
    exit();
}

$admission = $_SESSION['admission'];

// Fetch student info
$sql_students = "SELECT fullname, course, year_of_study, total_fees, gender, dob, phone, email, postal_address, id_number
                 FROM students 
                 WHERE admission='$admission'";

$result_student = $conn->query($sql_students);

if ($result_student && $result_student->num_rows > 0) {
    $student = $result_student->fetch_assoc();
    $fullname = $student['fullname'];
    $course = $student['course'];
    $year_of_study = $student['year_of_study'];
    $total_fees = $student['total_fees'];
    $gender = $student['gender'];
    $dob = $student['dob'];
    $phone = $student['phone'];
    $email = $student['email'];
    $address = $student['postal_address'];
    $id_number = $student['id_number'];
} else {
    $fullname = $course = $year_of_study = $gender = $dob = $phone = $email = $address = $id_number = "Unknown";
    $total_fees = 0;
}

// Fetch total fees paid
$sql_paid = "SELECT COALESCE(SUM(amount),0) AS fees_paid 
             FROM fee_payments 
             WHERE admission_no='$admission' AND status='Success'";

$result_paid = $conn->query($sql_paid);
$fees_paid = 0;

if ($result_paid && $result_paid->num_rows > 0) {
    $row_paid = $result_paid->fetch_assoc();
    $fees_paid = $row_paid['fees_paid'];
}

$balance = $total_fees - $fees_paid;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="idashboard.css">
    <style>
        /* --- Top Fee Summary --- */
        .fee-summary {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .fee-card {
            background: #fff;
            flex: 1;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .fee-card h3 { margin-bottom: 10px; color: #004aad; }
        .fee-card p { font-size: 18px; font-weight: bold; }

        /* --- Dashboard Top Section --- */
        .dashboard-top {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* --- Profile Card --- */
        .profile-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 250px;
            text-align: center;
        }
        .profile-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
        }
        .profile-card h2 { font-size: 18px; color: #004aad; margin: 0; }
        .profile-card p { font-size: 14px; color: #555; }

        /* --- Personal Info Card --- */
        .personal-info-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex: 1;
        }
        .personal-info-card h3 { margin-bottom: 15px; color: #004aad; }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }
        .update-btn { text-align: right; margin-top: 15px; }
        .update-btn a {
            background: #004aad;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }
        .update-btn a:hover { background: #003080; }

        /* --- Cards Container (Old cards) --- */
        .cards-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .cards-container .card {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 150px;
            text-align: center;
        }
        .cards-container .card h3 { margin-bottom: 8px; color: #004aad; }
        .cards-container .card p { margin: 0; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Fee summary -->
    <div class="main-content">
         <h2 style="color:#004aad; margin-bottom:15px;">Fee Summary</h2>
        <div class="fee-summary">
            <div class="fee-card total">
                <h3>Total Fees</h3>
                <p>KES <?php echo number_format($total_fees); ?></p>
            </div>
            <div class="fee-card paid">
                <h3>Fees Paid</h3>
                <p>KES <?php echo number_format($fees_paid); ?></p>
            </div>
            <div class="fee-card balance">
                <h3>Balance</h3>
                <p>KES <?php echo number_format($balance); ?></p>
            </div>
        </div>

        <!-- Dashboard top: Profile + Personal Info -->
        <div class="dashboard-top">
            <!-- Profile Card -->
            <div class="profile-card">
                <img src="<?php echo ($gender == 'Male') ? 'images/male.png' : 'images/female.png'; ?>" alt="Profile Picture">
                <h2><?php echo htmlspecialchars($fullname); ?></h2>
                <p><?php echo htmlspecialchars($course); ?></p>
            </div>

            <!-- Personal Info Card -->
            <div class="personal-info-card">
                <h3>Personal Information</h3>
                <div class="info-row"><span>Admission No:</span> <span><?php echo htmlspecialchars($admission); ?></span></div>
                <div class="info-row"><span>ID/Passport:</span> <span><?php echo htmlspecialchars($id_number); ?></span></div>
                <div class="info-row"><span>Full Name:</span> <span><?php echo htmlspecialchars($fullname); ?></span></div>
                <div class="info-row"><span>Gender:</span> <span><?php echo htmlspecialchars($gender); ?></span></div>
                <div class="info-row"><span>Date of Birth:</span> <span><?php echo htmlspecialchars($dob); ?></span></div>
                <div class="info-row"><span>Phone Number:</span> <span><?php echo htmlspecialchars($phone); ?></span></div>
                <div class="info-row"><span>Email Address:</span> <span><?php echo htmlspecialchars($email); ?></span></div>
                <div class="info-row"><span>Postal Address:</span> <span><?php echo htmlspecialchars($address); ?></span></div>
                <div class="update-btn">
                    <a href="update_profile.php">Update</a>
                </div>
            </div>
        </div>

    
    </div>

</body>
</html>
