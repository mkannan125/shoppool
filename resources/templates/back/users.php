
                    <div class="col-lg-12">
                      

                        <h1 class="page-header">
                            Users
                         
                        </h1>
                          <p class="bg-success">
                        </p>

                        <a href="index.php?add_user" class="btn btn-primary">Add User</a>


                        <div class="col-md-12">

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>

                                        <th>Username</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <tr>
                                        <?php display_users();  ?>
                                    </tr>




                                    
                                    
                                </tbody>
                            </table> <!--End of Table-->
                        

                        </div>










                        
                    </div>
    












            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../../../public/admin/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../../public/admin/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../../../public/admin/js/plugins/morris/raphael.min.js"></script>
    <script src="../../../public/admin/js/plugins/morris/morris.min.js"></script>
    <script src="../../../public/admin/js/plugins/morris/morris-data.js"></script>

</body>

</html>
