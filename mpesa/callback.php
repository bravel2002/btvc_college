<?php
include('../config/db_connect.php');

// Read callback JSON
$callbackData = file_get_contents('php://input');
$callbackJSON = json_decode($callbackData, true);

// Validate callback
if (!$callbackJSON || !isset($callbackJSON['Body']['stkCallback'])) exit;

$stkCallback = $callbackJSON['Body']['stkCallback'];
$resultCode = $stkCallback['ResultCode'] ?? 1;
$resultDesc = $stkCallback['ResultDesc'] ?? 'Unknown error';
$checkoutRequestID = $stkCallback['CheckoutRequestID'] ?? '';

$amount = 0;
$mpesaCode = '';
$phone = '';

// Only read metadata if it exists
if (isset($stkCallback['CallbackMetadata']['Item'])) {
    foreach ($stkCallback['CallbackMetadata']['Item'] as $item) {
        if ($item['Name'] == 'Amount') $amount = $item['Value'];
        if ($item['Name'] == 'MpesaReceiptNumber') $mpesaCode = $item['Value'];
        if ($item['Name'] == 'PhoneNumber') $phone = $item['Value'];
    }
}

// Set status
$status = ($resultCode == 0) ? 'Success' : 'Failed';

// Update DB
$stmt = $conn->prepare("UPDATE fee_payments 
    SET status=?, mpesa_code=?, amount=?, phone=?, result_desc=? 
    WHERE checkout_request_id=?");
$stmt->bind_param("ssdsss", $status, $mpesaCode, $amount, $phone, $resultDesc, $checkoutRequestID);
$stmt->execute();
$stmt->close();

// Optional: log for debugging
file_put_contents('callback_debug.log', date('Y-m-d H:i:s') . " - $checkoutRequestID updated to $status ($resultCode: $resultDesc)\n", FILE_APPEND);
?>
