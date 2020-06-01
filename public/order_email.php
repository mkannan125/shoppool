<!-- Configuration-->
<?php require_once("../resources/config.php"); ?>
<!-- Header-->
<?php include(TEMPLATE_FRONT . "/header.php");?>
<!-- Page Content -->
<div class="container">
    <!-- /.row -->
    <div class="row">
        <h1>Checkout</h1>
        <form action="thank_you.php?order=1" method="post">
            <?php cart(); ?>
        </form>
        <!--  ***********CART TOTALS*************-->
        <div class="col-xs-4 pull-right ">
            <h2>Cart Totals</h2>
            <table class="table table-bordered" cellspacing="0">
                <tr class="cart-subtotal">
                    <th>Items:</th>
                    <td><span class="amount">
                        <?php
                        echo isset($_SESSION['item_quantity']) ? $_SESSION['item_quantity']: $_SESSION['item_quantity'] = "0";
                        ?></td>
                </tr>
                <tr class="shipping">
                    <th>Shipping and Handling</th>
                    <td>Free Shipping</td>
                </tr>

                <tr class="order-total">
                    <th>Order Total</th>
                    <td><strong><span class="amount">&#36;
            <?php
            echo isset($_SESSION['item_total']) ? $_SESSION['item_total']: $_SESSION['item_total'] = "0";
            ?>
        </span></strong> </td>
                </tr>
                </tbody>
            </table>
        </div><!-- CART TOTALS-->
    </div><!--Main Content-->
</div>
<!-- /.container -->
<?php

include(TEMPLATE_FRONT . DS . "footer.php");

$HtmlCode= ob_get_contents();
ob_end_flush();

$fh=fopen('email.html','w');
fwrite($fh,$HtmlCode);
fclose($fh);

?>


</body>

</html>