
<?php if(!(isset($_SESSION['is_farmer']) && $_SESSION['is_farmer'] == 1)){
    redirect('../index.php');
    set_message("You do not have access to that page!");
}?>
<h1 class="page-header">
   <?php echo $_SESSION['email']?>'s Products

</h1>
<h3 class="bg-success"><?php display_message(); ?></h3>
<table class="table table-hover">


    <thead>

      <tr>
           <th>Id</th>
           <th>Title</th>
           <th>Category</th>
           <th>Price</th>
          <th>Quantity</th>
      </tr>
    </thead>
    <tbody>

    <?php get_products_in_admin(); ?>
      


  </tbody>
</table>
