<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>

<?php if(!logged_in()){
    redirect('login.php');
    set_message("Please login to continue");
}?>

<!-- Page Content -->
<div class="container">

    <!-- Jumbotron Header -->
    <header>
        <h1>Shop</h1>
    </header>

    <hr>

    <!-- Title -->
    <!-- /.row -->

    <!-- Page Features -->

    <div class="row text-center">
        <?php get_products_in_shop_page(); ?>
    </div>
    <!-- /.row -->

<!-- /.container -->
<?php include(TEMPLATE_FRONT . DS . "footer.php") ?>

</div>

</body>

</html>
