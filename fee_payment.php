<?php
session_start();
include('config/db_connect.php');

// Initialize message
$msg = '';

// Handle manual payment form submission
if (isset($_POST['submit_payment'])) {
    $admission = $_SESSION['admission'];
    $payment_method = $_POST['payment_method'];
    $mpesa_code = $_POST['mpesa_code'];
    $amount = $_POST['amount'];

   $stmt = $conn->prepare("INSERT INTO fee_payments (admission_no, payment_method, mpesa_code, amount, status) VALUES (?, ?, ?, ?, ?)");
$status = 'Success';
$payment_method = 'M-Pesa'; // force the payment method
$stmt->bind_param("sssds", $admission, $payment_method, $mpesa_code, $amount, $status);

    if ($stmt->execute()) {
        $msg = "<p class='success'>Payment recorded successfully!</p>";
    } else {
        $msg = "<p class='error'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<style>
.fee-container { width: 90%; max-width: 500px; background: #fff; padding: 20px; margin: 20px auto; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-family: 'Poppins', sans-serif; font-size: 14px; }
.fee-container h2 { font-size: 18px; text-align: center; margin-bottom: 20px; color: #004aad; }
.fee-container label { font-weight: 500; display: block; margin-bottom: 5px; color: #333; }
.fee-container input, .fee-container select { width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; }
.fee-container button { width: 100%; background: #004aad; color: #fff; border: none; padding: 10px; border-radius: 6px; cursor: pointer; transition: 0.3s; }
.fee-container button:hover { background: #003080; }
.success { color: green; font-weight: bold; text-align: center; }
.error { color: red; font-weight: bold; text-align: center; }
.payment-history { margin-top: 30px; }
.payment-history table { width: 100%; border-collapse: collapse; font-size: 13px; }
.payment-history th, .payment-history td { padding: 8px; border: 1px solid #ccc; text-align: left; }
.payment-history th { background: #f3f6fa; color: #333; }
</style>

<div class="fee-container">
    <h2>Fee Payment</h2>
    <?php if(isset($msg)) echo $msg; ?>

    <!-- STK Push Form -->
    <form action="mpesa/stk_initiate.php" method="POST">
        <label>Phone Number (254...):</label>
        <input type="text" name="phone" required>

        <label>Amount:</label>
        <input type="number" name="amount" required>

        <button type="submit">Pay Now via M-Pesa</button>
    </form>

   
    <!-- Payment History -->
<div class="payment-history">
    <h3>Your Payment History</h3>
    <?php
    $admission = $_SESSION['admission'];
    $result = mysqli_query($conn, "SELECT * FROM fee_payments WHERE admission_no='$admission' ORDER BY payment_date DESC");

    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>mpesa Code</th>
                    <th>Amount (Ksh)</th>
                    <th>Status</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            // Style M-Pesa method and success status
            $method = $row['payment_method'] == 'M-Pesa' ? "<span style='color:green; font-weight:bold;'>{$row['payment_method']}</span>" : $row['payment_method'];
            $status = strtolower($row['status']) == 'success' ? "<span style='color:green;'>{$row['status']}</span>" : "<span style='color:red;'>{$row['status']}</span>";

            echo "<tr>
                    <td>{$row['payment_date']}</td>
                    <td>{$method}</td>
                    <td>{$row['mpesa_code']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$status}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center; color:#555;'>No payment records found.</p>";
    }
    ?>
</div>
