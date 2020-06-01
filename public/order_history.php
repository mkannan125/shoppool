
<!-- Configuration-->

<?php require_once("../resources/config.php"); ?>



<!-- Header-->
<?php include(TEMPLATE_FRONT . "/header.php");?>
<?php if(!logged_in()){
    set_message("Please login to continue");
    redirect('login.php');
}?>


<!-- Page Content -->
<div class="container">


    <!-- /.row -->

    <div class="row">
        <h4 class=""text-center bg-danger"><?php display_message(); ?></h4>
        <h1>Order History</h1>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sub-total</th>
                    <th>Order Date</th>

                </tr>
                </thead>
                <tbody>
                <?php prev_orders(); ?>
                </tbody>
            </table>


    </div><!--Main Content-->


</div>
<!-- /.container -->
<?php include(TEMPLATE_FRONT . DS . "footer.php") ?>


</body>

</html>