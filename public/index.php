<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>
<style>
div.transbox {
    background: rgb(0,0,0);
    margin: 30px;
    background: rgba(0,0,0,0.6);
}
body {
background: url("cupertino_market.jpg") no-repeat center center fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}
h1 {

    margin: 5%;
    font-weight: bold;
    color: #FFFFFF;
}
</style>
    <!-- Page Content -->
    <div class="container">

        <div class="row transbox">
            <div class="bg"></div>
            <h1>Welcome</h1>
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
