<?php
session_start();
include "db/connection.php";

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customer WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $customer = mysqli_fetch_assoc($result);

        if (password_verify($password, $customer['password'])) {

            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['name'];

            header("Location: index.php");
            exit();

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "Customer not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
</head>

<body>

    <h1>Customer Login</h1>

    <?php
    if ($message != "") {
        echo "<p>$message</p>";
    }
    ?>

    <form method="POST">

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="login">
            Login
        </button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</body>
</html>

