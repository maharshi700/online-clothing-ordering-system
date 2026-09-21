<?php
session_start();
include "db/connection.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT cart.quantity,
               product.name,
               product.price
        FROM cart
        INNER JOIN product
        ON cart.product_id = product.product_id
        WHERE cart.customer_id = '$customer_id'";

$result = mysqli_query($conn, $sql);

$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total = $total + ($row['price'] * $row['quantity']);
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Checkout</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <h1>Checkout</h1>

    <h2>Order Total: ₹<?php echo $total; ?></h2>

    <form method="POST" action="place_order.php">

        <label>Delivery Address:</label>
        <br>

        <textarea
            name="address"
            rows="5"
            cols="40"
            required></textarea>

        <br><br>

        <label>Payment Method:</label>
        <br>

        <input
            type="radio"
            name="payment_method"
            value="COD"
            required>

        Cash on Delivery

        <br>

        <input
            type="radio"
            name="payment_method"
            value="Online">

        Online Payment

        <br><br>

        <button type="submit">
            Place Order
        </button>

    </form>

    <br>

    <a href="cart.php">Back to Cart</a>

</body>

</html>

