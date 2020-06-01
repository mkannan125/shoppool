
<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . "/header.php");?>

<?php

if(isset($_GET['order'])) {
    $amount = $_SESSION['item_total'];
    $date = date('Y-m-d H:i:s');
    $query = query("INSERT INTO orders(order_price, order_date) VALUES(" . escape_string($_SESSION['item_total']) . ", NOW())");
    confirm($query);
    $query = query("SELECT * FROM orders WHERE order_id = LAST_INSERT_ID()");
    confirm($query);
    $row = fetch_array($query);
    foreach ($_SESSION as $name => $value) {
        $sub = 0;
        if ($value > 0) {
            if (substr($name, 0, 8) == "product_") {
                unset($_SESSION[$name]);
                $length = strlen($name) - 8;
                $id = substr($name, 8, $length);
                $query = query("INSERT INTO order_details(order_id, product_id, quantity, user_email) VALUES('"
                    . $row['order_id'] . "', '" . escape_string($id) . "', '" . escape_string($value) . "', '"
                    . escape_string($_SESSION['email']) . "')");
                confirm($query);
                $query = query("SELECT product_id, product_quantity FROM products WHERE product_id = " . escape_string($id) . " ");
                confirm($query);
                $products = fetch_array($query);
                $prod_id = $products['product_id'];
                $prod_quantity = $products['product_quantity'];
                if ($prod_quantity - $value == 0) {
                    redirect("../resources/templates/back/delete_product.php?id={$prod_id}");
                } else {
                    $prod_quantity -= $value;
                    $query = "UPDATE products SET ";
                    $query .= "product_quantity = " . escape_string($prod_quantity);
                    $query .= " WHERE product_id = " . escape_string($prod_id);
                    $query = query($query);
                    confirm($query);
                }
            }
        }
    }
    $subject = "Shoppool Registration";
    $msg = order_confirmation_email();
    send_email($_SESSION['email'], $subject, $msg);
    //session_destroy();
}

?>

<!-- Page Content -->
<div class="container">

    <h1 class="text-center">THANK YOU</h1>


</div>
<!-- /.container -->
<?php include(TEMPLATE_FRONT . DS . "footer.php") ?>


</body>

</html>