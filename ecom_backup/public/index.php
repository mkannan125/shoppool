<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <?php include(TEMPLATE_FRONT . DS . "side_nav.php") ?>

            <!-- Categories here -->

            <div class="col-md-9">

                <div class="row">

                    <?php
                    display_message();
                    if (logged_in()) {
                        get_products();
                    } else {
                        echo "<h1>Log In to get started.</h1>";
                    }
                    ?>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT . DS . "footer.php") ?>


</body>

</html>
