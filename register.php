<?php
include "db/connection.php";

$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    $check = "SELECT * FROM customer WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        $message = "Email already registered.";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customer
                (name, email, phone, password, address)
                VALUES
                ('$name', '$email', '$phone', '$hashed_password', '$address')";

        if (mysqli_query($conn, $sql)) {
            $message = "Registration successful!";
        } else {
            $message = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
</head>

<body>

    <h1>Create Account</h1>

    <?php
    if ($message != "") {
        echo "<p>$message</p>";
    }
    ?>

    <form method="POST">

        <label>Name:</label><br>
        <input type="text" name="name" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <label>Address:</label><br>
        <textarea name="address" required></textarea>
        <br><br>

        <button type="submit" name="register">
            Register
        </button>

    </form>

</body>
</html>

