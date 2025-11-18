<?php
session_start();
include('../config/db_connect.php'); // DB connection
include('access_token.php');        // Access token generator

if (!isset($_SESSION['admission'])) {
    die('<p style="color:red;">Error: Student not logged in.</p>');
}

$admission = $_SESSION['admission'];
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

if (empty($phone) || $amount <= 0) {
    die('<p style="color:red;">Phone number and amount are required!</p>');
}

// STK Push details
$BusinessShortCode = '174379';
$PartyA = $phone;
$PartyB = '174379';
$AccountReference = 'BTVC_COLLEGE_FEES';
$TransactionDesc = 'Fee Payment';
$Timestamp = date("YmdHis");
$PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$Password = base64_encode($BusinessShortCode . $PassKey . $Timestamp);

// Payload
$payload = [
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $PartyA,
    'PartyB' => $PartyB,
    'PhoneNumber' => $phone,
    'CallBackURL' => 'https://btvc-college.onrender.com/mpesa/callback.php',
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
];

// Send request
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($curl);
curl_close($curl);

$responseData = json_decode($response, true);
if (!$responseData) {
    die('<p style="color:red;">Failed to get response from Safaricom API.</p>');
}

// Insert payment as Pending
$merchantRequestID = $responseData['MerchantRequestID'] ?? '';
$checkoutRequestID = $responseData['CheckoutRequestID'] ?? '';
$status = 'Pending';

$stmt = $conn->prepare("INSERT INTO fee_payments 
    (admission_no, phone, amount, status, merchant_request_id, checkout_request_id, payment_date) 
    VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssdsss", $admission, $phone, $amount, $status, $merchantRequestID, $checkoutRequestID);
$stmt->execute();
$stmt->close();

// Inform user
if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == "0") {
    echo "<p style='color:green;'>✅ Payment request sent! Enter your PIN on your phone.</p>";
} else {
    echo "<p style='color:red;'>❌ Payment request failed: " . ($responseData['errorMessage'] ?? 'Unknown error') . "</p>";
}
?>
