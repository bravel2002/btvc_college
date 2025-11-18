<?php
include __DIR__ . '/config/db_connect.php';
session_start();

if (!isset($_SESSION['admission'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$admission = $_SESSION['admission'];

// Fetch total fees from students table
$sql_student = "SELECT total_fees FROM students WHERE admission='$admission'";
$result_student = $conn->query($sql_student);

$total_fees = 0;
if ($result_student && $result_student->num_rows > 0) {
    $row = $result_student->fetch_assoc();
    $total_fees = $row['total_fees'];
}

// Fetch total paid from fee_payments table
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

echo json_encode([
    'total_fees' => $total_fees,
    'fees_paid' => $fees_paid,
    'balance' => $balance
]);
?>
