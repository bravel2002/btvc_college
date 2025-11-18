<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/db_connect.php');
session_start();

// Check admin login
if (!isset($_SESSION['admin'])) {
    header('location: admin_login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admission = mysqli_real_escape_string($conn, $_POST['admission']);
    $fullname  = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $course    = mysqli_real_escape_string($conn, $_POST['course']);
    $year      = mysqli_real_escape_string($conn, $_POST['year']);
    $gender    = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob       = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $address   = mysqli_real_escape_string($conn, $_POST['postal_address']);
    $id_number = mysqli_real_escape_string($conn, $_POST['id_number']);

    // Password = admission number
$password = $admission;

    $query = "INSERT INTO students 
        (admission, fullname, email, course, password, year_of_study, gender, dob, phone, postal_address, id_number)
        VALUES 
        ('$admission', '$fullname', '$email', '$course', '$password', '$year', '$gender', '$dob', '$phone', '$address', '$id_number')";

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
    exit();
}
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f7fc;
    margin: 0;
    padding: 0;
}
h2 { text-align: center; color: #007BFF; margin-top: 30px; }
form {
    background: #fff;
    width: 60%;
    margin: 30px auto;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
}
label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; }
input[type="text"], input[type="email"], input[type="date"], select {
    width: 100%; padding: 10px 12px; margin-bottom: 18px;
    border: 1px solid #ccc; border-radius: 8px; transition: 0.3s ease; font-size: 15px;
}
input[type="text"]:focus, input[type="email"]:focus, input[type="date"]:focus, select:focus {
    border-color: #007BFF; outline: none; box-shadow: 0 0 5px rgba(0,123,255,0.3);
}
button {
    background: #007BFF; color: white; font-size: 16px;
    padding: 10px 18px; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s ease;
}
button:hover { background: #0056b3; }
</style>

<h2>Add New Student</h2>

<form id="addStudentForm">
    <label>Admission No:</label>
    <input type="text" name="admission" required>

    <label>Full Name:</label>
    <input type="text" name="fullname" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Course:</label>
    <select name="course" required>
        <option value="">-- Select Course --</option>
        <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
        <option value="Agriculture">Agriculture</option>
        <option value="Business and Social Sciences">Business and Social Sciences</option>
        <option value="Hair Dressing and Beauty Therapy">Hair Dressing and Beauty Therapy</option>
        <option value="Food and Beverage Production">Food and Beverage Production</option>
        <option value="Food and Beverage Service">Food and Beverage Service</option>
    </select>

    <label>Year of Study:</label>
    <input type="text" name="year" required>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

    <label>Date of Birth:</label>
    <input type="date" name="dob" required>

    <label>Phone:</label>
    <input type="text" name="phone" required>

    <label>Postal Address:</label>
    <input type="text" name="postal_address" required>

    <label>ID Number:</label>
    <input type="text" name="id_number" required>

    <button type="submit">Add Student</button>
</form>

<script>
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('add_student.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        console.log("Server Response:", data);
        if (data.trim() === 'success') {
            alert('✅ Student added successfully!');
            this.reset(); // Clear the form
        } else {
            alert('❌ Error adding student: ' + data);
        }
    })
    .catch(err => console.error(err));
});
</script>
