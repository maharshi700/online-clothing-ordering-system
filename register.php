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

    <title>Create Account</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body style="background: url('http://localhost/ecommerece/images/background.jpg') center center / cover fixed no-repeat;">

    <div class="auth-page">

        <div class="auth-card register-card">

            <div class="auth-logo">
                CLOTHING STORE
            </div>

            <h1>Create Account</h1>

            <p class="auth-subtitle">
                Join us and start shopping
            </p>

            <?php
            if ($message != "") {
                echo "<div class='success-message'>$message</div>";
            }
            ?>

            <form method="POST">

                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

                <label>Phone</label>

                <input
                    type="text"
                    name="phone"
                    placeholder="Enter your phone number"
                    required
                >

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

                <label>Address</label>

                <textarea
                    name="address"
                    placeholder="Enter your delivery address"
                    required
                ></textarea>

                <button type="submit" name="register">
                    CREATE ACCOUNT
                </button>

            </form>

            <p class="auth-bottom">

                Already have an account?

                <a href="login.php">
                    Login
                </a>

            </p>

            <a class="back-home" href="index.php">
                ← Back to Store
            </a>

        </div>

    </div>

</body>

</html>


