<?php
session_start();
session_destroy();
header("Location: student_login.php"); // login.php is in the same folder
exit();
?>
