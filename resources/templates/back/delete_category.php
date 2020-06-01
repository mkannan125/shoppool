<?php require_once("../../config.php");

if(isset($_GET['id'])) {
    $query = query("DELETE FROM categories WHERE cat_id = " . escape_string($_GET['id']) . " ");
    confirm($query);
    redirect("../../../public/admin/index.php?categories");
    set_message("Category Deleted");
} else {
    redirect("../../../public/admin/index.php?categories");
}


?>