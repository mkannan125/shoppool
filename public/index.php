<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row transbox">
            <div class="bg"></div>
            <h1>Welcome to Shoppool</h1>
            <?php //include(TEMPLATE_FRONT . DS . "side_nav.php") ?>

            <!-- Categories here -->



                    <?php
                    display_message();
                    if (logged_in()) {
                        get_products();
                    } else {
                        echo "<div class=\"col-md-9\">

                                    <div class=\"row\">
                                        <h3>Log In to get started.</h3>
                                    </div>

                                </div>";
                    }
                    ?>

        </div>

    </div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT . DS . "footer.php") ?>


</body>

</html>
