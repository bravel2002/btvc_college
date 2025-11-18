<?php
session_start();
include('config/db_connect.php');

// Ensure student is logged in
if (!isset($_SESSION['admission'])) {
    header("Location: login.php");
    exit();
}

$admission = $_SESSION['admission'];

// 1. Collect total fee & exam card path from students table
$student_query = "SELECT total_fees, exam_card_path FROM students WHERE admission='$admission'";
$student_res = mysqli_query($conn, $student_query);
if (!$student_res) {
    die('Student SQL error: ' . mysqli_error($conn));
}
$student_data = mysqli_fetch_assoc($student_res);

$total_fees = isset($student_data['total_fees']) ? floatval($student_data['total_fees']) : 0;
$exam_card = isset($student_data['exam_card_path']) ? $student_data['exam_card_path'] : '';

// 2. Sum up the amount paid from fee_payments table
$payment_query = "SELECT SUM(amount) AS amount_paid FROM fee_payments WHERE admission_no='$admission_no'";
$payment_res = mysqli_query($conn, $payment_query);
if (!$payment_res) {
    die('Payment SQL error: ' . mysqli_error($conn));
}
$payment_data = mysqli_fetch_assoc($payment_res);
$amount_paid = isset($payment_data['amount_paid']) ? floatval($payment_data['amount_paid']) : 0;

// 3. Condition for download eligibility
$canDownload = ($total_fees > 0) && ($amount_paid >= ($total_fees / 2)) && !empty($exam_card);
?>

<h2 style="color:#004aad;margin-bottom:16px;">Exam Card</h2>

<div style="background:#fff;padding:30px 28px;border-radius:10px;max-width:420px;margin:0 auto;">
<?php if ($canDownload): ?>
    <div style="font-weight:bold; font-size:17px; color:green; margin-bottom:18px;">
        🎉 You are eligible to download your exam card!
    </div>
    <a href="<?php echo 'exam_cards/' . htmlspecialchars($exam_card); ?>"
       download
       style="display:inline-block;padding:10px 23px;background:#007BFF;color:white;border-radius:7px;text-decoration:none;font-size:16px;">
        <i class="fa fa-id-card"></i> Download Exam Card (PDF)
    </a>
<?php elseif (empty($exam_card)): ?>
    <div style="color:#d9534f; font-weight:bold; font-size:16px;">
        Your exam card has not been uploaded yet.<br>Please check back later or contact the admin.
    </div>
<?php elseif ($amount_paid < ($total_fees/2)): ?>
    <div style="color:#d9534f; font-weight:bold; font-size:16px;">
        You must pay at least half of your total fees to be eligible for exam card download.<br>
        <span style="color:#007BFF;">Paid:</span> Ksh <?php echo number_format($amount_paid, 2); ?> <br>
        <span style="color:#007BFF;">Required:</span> Ksh <?php echo number_format($total_fees/2, 2); ?>
    </div>
<?php else: ?>
    <div style="color:#999; font-size:16px;">
        No record of exam card or fee data found. Please contact administration.
    </div>
<?php endif; ?>
</div>