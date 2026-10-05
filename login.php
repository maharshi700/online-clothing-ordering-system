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

    <link rel="stylesheet" href="css/style.css">
</head> 
 
<body style="background: url('http://localhost/ecommerece/images/background.jpg') center center / cover fixed no-repeat;"> 

    <div class="auth-page">

        <div class="auth-card">

            <div class="auth-logo">
                CLOTHING STORE
            </div>

            <h1>Welcome Back</h1>

            <p class="auth-subtitle">
                Login to continue shopping
            </p>

            <?php 
            if ($message != "") { 
                echo "<div class='error-message'>$message</div>"; 
            } 
            ?> 
 
            <form method="POST"> 
 
                <label>Email</label>

                <input 
                    type="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                > 
 
                <label>Password</label>

                <input 
                    type="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                > 
 
                <button type="submit" name="login"> 
                    LOGIN
                </button> 
 
            </form> 
 
            <p class="auth-bottom">
                Don't have an account?
                <a href="register.php">Create Account</a>
            </p>

            <a class="back-home" href="index.php">
                ← Back to Store
            </a>

        </div>

    </div>
 
</body> 
</html>


