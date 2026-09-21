<?php
session_start();
include "db/connection.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$address = $_POST['address'];
$payment_method = $_POST['payment_method'];

/* Get customer's cart */

$sql = "SELECT cart.product_id,
               cart.quantity,
               product.price
        FROM cart
        INNER JOIN product
        ON cart.product_id = product.product_id
        WHERE cart.customer_id = '$customer_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Your cart is empty.";
    exit();
}

/* Calculate total */

$total = 0;

$cart_items = [];

while ($row = mysqli_fetch_assoc($result)) {

    $subtotal = $row['price'] * $row['quantity'];

    $total = $total + $subtotal;

    $cart_items[] = $row;
}

/* Create order */

$order_sql = "INSERT INTO orders
              (customer_id, total_amount, status, address)
              VALUES
              ('$customer_id', '$total', 'Pending', '$address')";

if (mysqli_query($conn, $order_sql)) {

    $order_id = mysqli_insert_id($conn);

    /* Add products to order_items */

    foreach ($cart_items as $item) {

        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $item_sql = "INSERT INTO order_items
                     (order_id, product_id, quantity, price)
                     VALUES
                     ('$order_id',
                      '$product_id',
                      '$quantity',
                      '$price')";

        mysqli_query($conn, $item_sql);
    }

    /* Save payment information */

    if ($payment_method == "COD") {
        $payment_status = "Pending";
    } else {
        $payment_status = "Pending";
    }

    $payment_sql = "INSERT INTO payment
                    (order_id, payment_method, payment_status)
                    VALUES
                    ('$order_id',
                     '$payment_method',
                     '$payment_status')";

    mysqli_query($conn, $payment_sql);

    /* Empty customer's cart */

    $delete_cart = "DELETE FROM cart
                    WHERE customer_id = '$customer_id'";

    mysqli_query($conn, $delete_cart);

    /* Show order confirmation */

    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Order Placed</title>";
    echo "<link rel='stylesheet' href='css/style.css'>";
    echo "</head>";

    echo "<body>";

    echo "<h1>Order Placed Successfully!</h1>";

    echo "<h2>Order ID: $order_id</h2>";

    echo "<h2>Total Amount: ₹$total</h2>";

    echo "<p>Payment Method: $payment_method</p>";

    echo "<p>Your order is currently Pending.</p>";

    echo "<br>";

    echo "<a href='index.php'>Continue Shopping</a>";

    echo "</body>";
    echo "</html>";

} else {

    echo "Unable to place the order.";
}
?>

