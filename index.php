<?php
session_start();
include "db/connection.php";

$message = "";

if (isset($_POST['add_to_cart'])) {

    if (!isset($_SESSION['customer_id'])) {

        header("Location: login.php");
        exit();

    }

    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];

    // Check if product is already in cart
    $check = "SELECT * FROM cart
              WHERE customer_id='$customer_id'
              AND product_id='$product_id'";

    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {

        // Increase quantity
        $update = "UPDATE cart
                   SET quantity = quantity + 1
                   WHERE customer_id='$customer_id'
                   AND product_id='$product_id'";

        mysqli_query($conn, $update);

    } else {

        // Add new product
        $insert = "INSERT INTO cart
                   (customer_id, product_id, quantity)
                   VALUES
                   ('$customer_id', '$product_id', 1)";

        mysqli_query($conn, $insert);
    }

    $message = "Product added to cart!";
}

$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Clothes Ordering System</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <h1>Welcome to Our Clothing Store</h1>

    <?php

    if (isset($_SESSION['customer_name'])) {

        echo "<p>Welcome, " . $_SESSION['customer_name'] . "!</p>";

        echo "<p>
                <a href='cart.php'>View Cart</a> |
                <a href='logout.php'>Logout</a>
              </p>";

    } else {

        echo "<p>
                <a href='login.php'>Login</a> |
                <a href='register.php'>Register</a>
              </p>";
    }

    ?>

    <?php

    if ($message != "") {
        echo "<p>$message</p>";
    }

    ?>

    <h2>Our Products</h2>

    <p>Choose from T-Shirts, Shirts, Jeans and Pants.</p>

    <div class="products">

        <?php

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<div class='product-card'>";

            echo "<img src='images/" . $row['image'] . "'>";

            echo "<h3>" . $row['name'] . "</h3>";

            echo "<p>" . $row['description'] . "</p>";

            echo "<p class='price'>₹" . $row['price'] . "</p>";

            echo "<form method='POST'>";

            echo "<input type='hidden'
                         name='product_id'
                         value='" . $row['product_id'] . "'>";

            echo "<button type='submit'
                          name='add_to_cart'>
                          Add to Cart
                  </button>";

            echo "</form>";

            echo "</div>";
        }

        ?>

    </div>

</body>

</html>



