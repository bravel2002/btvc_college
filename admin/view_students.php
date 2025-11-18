<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/db_connect.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header('location: admin_login.php');
    exit();
}

// List of courses for dropdown
$courses = [
    "Electrical and Electronic Engineering",
    "Agriculture",
    "Business and Social Sciences",
    "Hair Dressing and Beauty Therapy",
    "Food and Beverage Production",
    "Food and Beverage Service"
];

// Detect course filter from GET
$selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';

// Build query based on selection
$query = "SELECT * FROM students";
if ($selectedCourse && in_array($selectedCourse, $courses)) {
    $safeCourse = mysqli_real_escape_string($conn, $selectedCourse);
    $query .= " WHERE course='$safeCourse'";
}
$result = mysqli_query($conn, $query);
?>

<h2 style="text-align:center; margin-top:10px; font-family:Arial, sans-serif;">Registered Students</h2>

<!-- Course filter form -->
<form method="GET" action="view_students.php" style="width:90%;margin:0 auto 20px auto;display:flex;align-items:center;gap:14px;">
    <label for="course" style="font-weight:bold;font-family:Arial, sans-serif;">Filter by Course:</label>
    <select name="course" id="course" onchange="this.form.submit()" style="padding:8px 12px;border-radius:7px;border:1px solid #ccc;">
        <option value="">-- All Courses --</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?php echo htmlspecialchars($course); ?>" <?php if($selectedCourse == $course) echo 'selected'; ?>>
                <?php echo htmlspecialchars($course); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<table style="
    width: 90%;
    margin: 20px auto;
    border-collapse: separate;
    border-spacing: 0 8px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
    border-radius: 10px;
">
    <tr style="background: #007BFF; color: white; text-align: left;">
        <th style="padding: 12px;">Admission</th>
        <th style="padding: 12px;">Full Name</th>
        <th style="padding: 12px;">Email</th>
        <th style="padding: 12px;">Course</th>
        <th style="padding: 12px;">Year</th>
        <th style="padding: 12px;">Gender</th>
        <th style="padding: 12px;">DOB</th>
        <th style="padding: 12px;">Phone</th>
        <th style="padding: 12px;">Postal Address</th>
        <th style="padding: 12px;">ID Number</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr style='background:#f9f9f9; border-radius:6px;'>
                <td style=\"padding:10px 15px;\">{$row['admission']}</td>
                <td style=\"padding:10px 15px;\">{$row['fullname']}</td>
                <td style=\"padding:10px 15px;\">{$row['email']}</td>
                <td style=\"padding:10px 15px;\">{$row['course']}</td>
                <td style=\"padding:10px 15px;\">{$row['year_of_study']}</td>
                <td style=\"padding:10px 15px;\">{$row['gender']}</td>
                <td style=\"padding:10px 15px;\">{$row['dob']}</td>
                <td style=\"padding:10px 15px;\">{$row['phone']}</td>
                <td style=\"padding:10px 15px;\">{$row['postal_address']}</td>
                <td style=\"padding:10px 15px;\">{$row['id_number']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='10' style='text-align:center; padding:15px;'>No students found</td></tr>";
    }
    ?>
</table>
