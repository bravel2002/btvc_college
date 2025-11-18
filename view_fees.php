<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config/db_connect.php';

$admission_no = $_SESSION['admission'];
$sql = "SELECT * FROM fee_payments WHERE admission_no = '$admission_no' ORDER BY payment_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Fee Records</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-family: 'Poppins', sans-serif; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #fafafa; }
        .paid { color: green; font-weight: bold; }
        .unpaid { color: red; font-weight: bold; }
        .mpesa { color: green; font-weight: bold; }
    </style>
</head>
<body>
<h2>My Fee Records</h2>
<table>
    <tr>
        <th>Payment Method</th>
        <th>Amount Paid</th>
        <th>Payment Date</th>
        <th>Status</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Payment method style (green if M-Pesa)
            $method = ($row['payment_method'] == 'M-Pesa') ? "<span class='mpesa'>{$row['payment_method']}</span>" : $row['payment_method'];

            // Status color
            $status_class = (strtolower($row['status']) == 'paid' || strtolower($row['status']) == 'success') ? 'paid' : 'unpaid';

            echo "<tr>
                    <td>{$method}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['payment_date']}</td>
                    <td class='{$status_class}'>{$row['status']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center; color:#555;'>No records found</td></tr>";
    }
    ?>
</table>
</body>
</html>
