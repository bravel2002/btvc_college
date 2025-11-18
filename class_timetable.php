<?php
session_start();
include('config/db_connect.php'); // adjust path if needed

// Check if student is logged in
if(!isset($_SESSION['admission'])){
    echo "<p>Please log in to view your timetable.</p>";
    exit;
}

// Get student class
$student_class = $_SESSION['class'] ?? null;

if(!$student_class){
    echo "<p>Your class information is missing. Contact admin.</p>";
    exit;
}

// Fetch timetable for the student's class
$query = "SELECT day, period1, period2, period3, period4, period5
          FROM class_timetable
          WHERE class='$student_class'
          ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')";

$result = mysqli_query($conn, $query);

if(!$result || mysqli_num_rows($result) == 0){
    echo "<p>No timetable has been posted yet.</p>";
    exit;
}
?>

<h3>Class Timetable for <?php echo htmlspecialchars($student_class); ?></h3>

<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <tr style="background-color: #f2f2f2;">
        <th>Day</th>
        <th>Period 1</th>
        <th>Period 2</th>
        <th>Period 3</th>
        <th>Period 4</th>
        <th>Period 5</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['day']); ?></td>
            <td><?php echo htmlspecialchars($row['period1']); ?></td>
            <td><?php echo htmlspecialchars($row['period2']); ?></td>
            <td><?php echo htmlspecialchars($row['period3']); ?></td>
            <td><?php echo htmlspecialchars($row['period4']); ?></td>
            <td><?php echo htmlspecialchars($row['period5']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>
