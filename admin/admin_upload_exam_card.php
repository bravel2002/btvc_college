<?php
// Start session and check admin login
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: admin_login.php'); exit();
}

include('../config/db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['exam_card'], $_POST['admission'])) {
    $admission = mysqli_real_escape_string($conn, $_POST['admission']);
    $file = $_FILES['exam_card'];
    $dir = '../exam_cards/';
    $filename = $admission . '_exam_card_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetPath = $dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $update = "UPDATE students SET exam_card_path='$filename' WHERE admission='$admission'";
        if(mysqli_query($conn, $update)){
            $message = "Exam card uploaded successfully!";
        }else{
            $message = "DB update failed!";
        }
    } else {
        $message = "File upload failed!";
    }
}

// Query all students for dropdown
$students = mysqli_query($conn, "SELECT admission, fullname FROM students ORDER BY fullname ASC");
?>

<h2>Upload Exam Card for Student</h2>
<?php if(!empty($message)) echo "<div style='color: green; margin-bottom: 10px;'>$message</div>"; ?>
<form method="POST" enctype="multipart/form-data" style="background:#fff;padding:18px;border-radius:10px;width:350px;margin-bottom:28px;">
    <label for="admission">Student:</label>
    <select name="admission" required style="width:100%;margin-bottom:14px;">
        <option value="">--Select Student--</option>
        <?php while ($s = mysqli_fetch_assoc($students)): ?>
            <option value="<?php echo $s['admission']; ?>">
                <?php echo htmlspecialchars($s['fullname']); ?> (<?php echo $s['admission']; ?>)
            </option>
        <?php endwhile; ?>
    </select>
    <label for="exam_card">Exam Card PDF:</label>
    <input type="file" name="exam_card" accept="application/pdf" required style="margin-bottom:12px;">
    <button type="submit">Upload Exam Card</button>
</form>