<?php
session_start();
include "db/connection.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

/* Increase quantity */
if (isset($_POST['increase'])) {

    $cart_id = $_POST['cart_id'];

    $sql = "UPDATE cart
            SET quantity = quantity + 1
            WHERE cart_id = '$cart_id'
            AND customer_id = '$customer_id'";

    mysqli_query($conn, $sql);
}

/* Decrease quantity */
if (isset($_POST['decrease'])) {

    $cart_id = $_POST['cart_id'];

    $sql = "UPDATE cart
            SET quantity = quantity - 1
            WHERE cart_id = '$cart_id'
            AND customer_id = '$customer_id'
            AND quantity > 1";

    mysqli_query($conn, $sql);
}

/* Remove product */
if (isset($_POST['remove'])) {

    $cart_id = $_POST['cart_id'];

    $sql = "DELETE FROM cart
            WHERE cart_id = '$cart_id'
            AND customer_id = '$customer_id'";

    mysqli_query($conn, $sql);
}

/* Get cart items */
$sql = "SELECT cart.cart_id,
               cart.quantity,
               product.name,
               product.price,
               product.image
        FROM cart
        INNER JOIN product
        ON cart.product_id = product.product_id
        WHERE cart.customer_id = '$customer_id'";

$result = mysqli_query($conn, $sql);

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Your Shopping Cart</h1>

<p>
    <a href="index.php">Continue Shopping</a> |
    <a href="logout.php">Logout</a>
</p>

<?php

if (mysqli_num_rows($result) == 0) {

    echo "<h2>Your cart is empty.</h2>";

} else {

    while ($row = mysqli_fetch_assoc($result)) {

        $subtotal = $row['price'] * $row['quantity'];

        $total = $total + $subtotal;

        echo "<div class='product-card'>";

        echo "<img src='images/" . $row['image'] . "'>";

        echo "<h3>" . $row['name'] . "</h3>";

        echo "<p>Price: ₹" . $row['price'] . "</p>";

        echo "<p>Quantity: " . $row['quantity'] . "</p>";

        echo "<p>Subtotal: ₹" . $subtotal . "</p>";

        /* Quantity buttons */

        echo "<form method='POST'>";

        echo "<input type='hidden'
                     name='cart_id'
                     value='" . $row['cart_id'] . "'>";

        echo "<button type='submit'
                      name='decrease'>
                      −
              </button>";

        echo " ";

        echo "<button type='submit'
                      name='increase'>
                      +
              </button>";

        echo "</form>";

        echo "<br>";

        /* Remove button */

        echo "<form method='POST'>";

        echo "<input type='hidden'
                     name='cart_id'
                     value='" . $row['cart_id'] . "'>";

        echo "<button type='submit'
                      name='remove'>
                      Remove
              </button>";

        echo "</form>";

        echo "</div>";
    }

    echo "<h2>Total: ₹" . $total . "</h2>";

    echo "<p>
            <a href='checkout.php'>
                <button>Proceed to Checkout</button>
            </a>
          </p>";
}

?>

</body>
</html>
