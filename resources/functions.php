<?php




// helper function
function row_count($result) {
    return mysqli_num_rows($result);
}


function clean($string)
{
    return htmlentities($string);
}

function set_message($msg) {
    if(!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message() {
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location) {
    header("Location: $location ");
}

function query($sql) {
    global $connection;
    $result = mysqli_query($connection, $sql);
    confirm($result);
    return $result;

}

function confirm($result) {
    global $connection;

    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

function escape_string($string) {
    global $connection;

    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result) {

    return mysqli_fetch_array($result);
}

// get products

function get_products() {
    $query = query(" SELECT * FROM farmers");
    confirm($query);

    while ($row = fetch_array($query)) {
        $product_query = query(" SELECT * FROM products WHERE farmer_id='{$row['id']}'");
        confirm($product_query);
        echo "<div class=\"col-md-9\"><div class=\"row\"><h1>{$row['farmer_name']}'s Products</h1>";
        while($product_row = fetch_array($product_query)) {
            $product_image = display_image($product_row['product_image']);
            $product = <<<DELIMETER

        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
                <a href=""><img src="../resources/{$product_image}" style="height: 25rem;"></a>
                <div class="caption">
                    <h4 class="pull-right">&#36;{$product_row['product_price']}</h4>
                    <h4><a href="">{$product_row['product_title']}</a>
                    </h4>
                    <a class="btn btn-primary" href="../resources/cart.php?add={$product_row['product_id']}">Add to Cart</a>
                </div>

            </div>
        </div>
DELIMETER;
            echo $product;
        }

        echo "</div></div>";
    }
}

function get_categories() {
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)) {
        $category_links = <<<DELIMETER
        <a href="category.php?id={$row['cat_id']}" class="list-group-item">{$row['cat_title']}</a>
DELIMETER;
        echo $category_links;
    }
}

function get_products_in_cat_page() {
    $query = query(" SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " ");
    confirm($query);
    while ($row = fetch_array($query)) {
        $product_image = display_image($row['product_image']);
        $product = <<<DELIMETER

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img style="height: 25rem;" src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;
        echo $product;
    }
}

function get_products_in_shop_page() {
    $query = query(" SELECT * FROM products");
    confirm($query);
    while ($row = fetch_array($query)) {
        $product_image = display_image($row['product_image']);
        $product = <<<DELIMETER

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" style="height: 25rem;">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>  
DELIMETER;
        echo $product;
    }
}

function send_message() {
    if(isset($_POST['submit'])) {
        $to = "someEmailAddres@gmail.com";
        $from_name = $_POST['name'];
        $subject = $_POST['subject'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $headers = "From: {$from_name} {$email}";
        // not reliable
        $result = mail($to, $subject, $message, $headers);

        if(!$result) {
            set_message("Sorry we could not send your message");
            redirect("contact.php");
        } else {
            set_message("Your message has been sent");
        }
    }
}

function display_orders() {
    $query = query("SELECT * FROM orders");
    confirm($query);
    while ($row = fetch_array($query)) {
        $orders = <<<DELIMETER
<tr>
<td>{$row['order_id']}</td>
<td>{$row['order_amount']}</td>
<td>{$row['order_transaction']}</td>
<td>{$row['order_currency']}</td>
<td>{$row['order_status']}</td>
<td><a class="btn btn-danger" href="../../resources/templates/back/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
DELIMETER;
    echo $orders;
    }
}

/***************************** Admin Products **********************/

function display_image($picture) {

    return "uploads" . DS . $picture;
}

function get_products_in_admin() {
    $query = query(" SELECT * FROM products WHERE farmer_id = " . $_GET['id']);
    confirm($query);
    while ($row = fetch_array($query)) {
        $category = show_product_category_title($row['product_category_id']);
        $product_image = display_image($row['product_image']);
        $product = <<<DELIMETER
        <tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']}<br>
              <a href="index.php?edit_product&id={$row['product_id']}"><img width='100' src="../../resources/{$product_image}" alt=""></a>
            </td>
            <td>{$category}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

        </tr>
DELIMETER;
        echo $product;
    }
}

function add_product() {

    if (isset($_POST['publish'])) {
        $product_title = escape_string($_POST['product_title']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_farmer_id = escape_string($_POST['farmer_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $short_desc = escape_string($_POST['short_desc']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

        $query = query("INSERT INTO products(product_title, farmer_id, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_farmer_id}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
        confirm($query);
        set_message("New Product was Added");
        redirect("index.php?products");
    }

}

function show_categories_add_product_page() {
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)) {
        $category_options = <<<DELIMETER
        <option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;
        echo $category_options;
    }
}

function show_farmers_add_product_page() {
    $query = query("SELECT * FROM farmers");
    confirm($query);

    while($row = fetch_array($query)) {
        $farmer_options = <<<DELIMETER
        <option value="{$row['id']}">{$row['farmer_name']}</option>
DELIMETER;
        echo $farmer_options;
    }
}
function show_product_category_title($product_category_id) {
    $category_query = query("SELECT * FROM categories WHERE cat_id = {$product_category_id}");
    confirm($category_query);
    while ($category_row = fetch_array($category_query)) {
        return $category_row['cat_title'];
    }
}



function update_product() {

    if (isset($_POST['update'])) {
        $product_title = escape_string($_POST['product_title']);
        echo escape_string($_POST['product_category_id']);
        $product_category_id = escape_string($_POST['product_category_id']);
        $product_price = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $short_desc = escape_string($_POST['short_desc']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

        if(empty($product_image)) {
            $get_pic = query("SELECT product_image FROM products WHERE product_id = " . escape_string($_GET['id']) . " ");
            confirm($get_pic);
            $pic = fetch_array($get_pic);
            $product_image = $pic['product_image'];
        }

        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

        $query = "UPDATE products SET ";
        $query .= "product_title = '{$product_title}', ";
        $query .= "product_category_id = '{$product_category_id}', ";
        $query .= "product_price = '{$product_price}', ";
        $query .= "product_description = '{$product_description}', ";
        $query .= "short_desc = '{$short_desc}', ";
        $query .= "product_quantity = '{$product_quantity}', ";
        $query .= "product_image = '{$product_image}' ";
        $query .= "WHERE product_id = " . escape_string($_GET['id']);
        $send_update_query = query($query);
        confirm($send_update_query);
        set_message("Product has been updated");
        redirect("index.php?products");
    }

}

/********************* categories in admin **************************/
function show_categories_in_admin() {
    $query = "SELECT * FROM categories";
    $category_query = query($query);
    confirm($category_query);
    while ($row = fetch_array($category_query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        $category = <<<DELIMETER
    <tr>
    <td>{$cat_id}</td>
    <td>{$cat_title}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

    </tr>
DELIMETER;
    echo $category;

    }
}

function add_category() {
    if (isset($_POST['add_category'])) {
        $cat_title = escape_string($_POST['cat_title']);

        $query = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
        confirm($query);
        //redirect("index.php?categories");
        set_message("Category created!");
    }
}

/***************************** admin users ********************/

function display_users() {
    $query = "SELECT * FROM users";
    $category_query = query($query);
    confirm($category_query);
    while ($row = fetch_array($category_query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
        $user = <<<DELIMETER
    <tr>
    <td>{$user_id}</td>
    <td>{$username}</td>
    <td>{$email}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

    </tr>
DELIMETER;
        echo $user;

    }
}

function add_user() {
    if(isset($_POST['add_user'])) {
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
        $query = query("INSERT INTO users(username, email, password) VALUES('{$username}', '{$email}', '{$password}')");
        confirm($query);
        set_message("USER CREATED");
        redirect("index.php?users");
    }
}
/******************************                ********************/
/******************************                ********************/
/***************************** Login Functions ********************/
/*****************************                 ********************/
/*****************************                 ********************/


function validation_errors($error) {
    $message = <<<DELIMITER
 <div class="alert alert-danger alert-dismissible" role="alert">
  <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>
  <strong>Warning!</strong> $error
</div>
            
DELIMITER;
    echo $message;
}

function email_exists($email) {
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}

function username_exists($username) {
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = query($sql);
    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}

function send_email($email=null, $subject=null, $msg=null) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "shoppool00@gmail.com";
    $mail->Password = "Sh0pp00l2020";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->From = "shoppool00@gmail.com";
    $mail->FromName = "Shoppool Support";
    try {
        $mail->addAddress($email, "Recipient");
        $mail->Subject = $subject;
        $mail->Body = $msg;
        if (!$mail->send()) {
            set_message("Email couldn't be sent");
            return false;
        } else{
            return true;
        }
    } catch (Exception $e) {
        return false;
    }
}

/************************* validation functions *****************************/

function validate_user_registration()
{
    $errors = [];
    $min = 3;
    $max = 20;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);
        $farmer_check = clean($_POST['farmer_check']);

        if (strlen($username) < $min) {
            $errors[] = "Your username cannot be less than {$min} characters";
        }
        if (strlen($username) > $max) {
            $errors[] = "Your username cannot be more than {$max} characters";
        }
        if (email_exists($email)) {
            $errors[] = "Sorry, that email already exists";
        }
        if (username_exists($username)) {
            $errors[] = "Sorry that username is already taken";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Your password fields do not match.";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                // Error display
                validation_errors($error);
            }
        } else {
            if (register_user($username, $email, $password, $farmer_check)) {
                set_message("<p class='bg-success text-center'>Thanks for joining Shoppool. Please login to continue.</p>");
                redirect("index.php");
            } else {
                //set_message("<p class='bg-danger text-center'>Sorry we could not register the user</p>");
                //redirect("index.php");
            }
        }
    }
}

function register_user($username, $email, $password, $farmer_check) {
    $username = escape_string($username);
    $email = escape_string($email);
    $password = escape_string($password);
    if (email_exists($email)) {
        return false;
    } else if (username_exists($username)) {
        return false;
    } else {
        $password = md5($password);
        if ($farmer_check == 'on') {
            $sql = "INSERT INTO users(username, email, password, admin)";
            $sql .= " VALUES('$username', '$email', '$password', 1)";
        } else {
            $sql = "INSERT INTO users(username, email, password)";
            $sql .= " VALUES('$username', '$email', '$password')";
        }
        $result = query($sql);
        confirm($result);
        $subject = "Shoppool Registration";
        $msg = register_user_email();
        if(send_email($email, $subject, $msg)) {
            return true;
        } else {
            return false;
        }
    }
}

/************************* activate functions *****************************/

function activate_user() {
    if($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_GET['email'])) {
            $email = clean($_GET['email']);
            $validation_code = clean($_GET['code']);
            $sql = "SELECT id FROM users WHERE email = '".escape_string($_GET['email'])."' AND validation_code = '".
                escape_string($_GET['code']) . "' ";
            $result = query($sql);
            confirm($result);
            if (row_count($result) == 1) {
                $sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '" . escape_string($email) . "' AND validation_code = '" . escape($validation_code) . "'";

                $result2 = query($sql2);
                confirm($result2);
                set_message("<p class='bg-success'>Your account has been activated. Please login</p>");
                redirect("login.php");
            } else {
                set_message("<p class='bg-danger'>Sorry, your account could not be activated.</p>");
                redirect("login.php");
            }
        }
    }
}

function validate_user_login()
{
    $errors = [];
    $min = 3;
    $max = 20;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if (empty($email)) {
            $errors[] = "Email field cannot be empty";
        }

        if (empty($password)) {
            $errors[] = "Password field cannot be empty";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo validation_errors($error);
            }
        } else {
            login_user($email, $password);
        }
    }

}

function login_user($email, $password) {
    $sql = "SELECT password, id, username, admin FROM users WHERE email = '" . escape_string($email) . "' ";
    $result = query($sql);
    confirm($result);
    if (row_count($result) == 1) {
        $row = fetch_array($result);
        $db_password = $row['password'];
        if (md5($password) === $db_password) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $row['username'];
            if ($row['admin'] == 1) {
                $_SESSION['is_farmer'] = 1;
                redirect("../public/admin/index.php?id=1");
            }else {
                redirect("shop.php");
            }
        } else {
            echo validation_errors("Your credentials are incorrect");
        }
    } else {
        echo validation_errors("Your credentials are incorrect");
    }
}

function logged_in() {
    if (isset($_SESSION['email'])) {
        return true;
    } else {
        return false;
    }
}

function register_user_email() {
   return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Narrative Welcome Email</title>
  <style type="text/css">

  /* Take care of image borders and formatting */

  img {
    max-width: 600px;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
  }

  a {
    border: 0;
    outline: none;
  }

  a img {
    border: none;
  }

  /* General styling */

  td, h1, h2, h3  {
    font-family: Helvetica, Arial, sans-serif;
    font-weight: 400;
  }

  td {
    font-size: 13px;
    line-height: 19px;
    text-align: left;
  }

  body {
    -webkit-font-smoothing:antialiased;
    -webkit-text-size-adjust:none;
    width: 100%;
    height: 100%;
    color: #37302d;
    background: #ffffff;
  }

  table {
    border-collapse: collapse !important;
  }


  h1, h2, h3, h4 {
    padding: 0;
    margin: 0;
    color: #444444;
    font-weight: 400;
    line-height: 110%;
  }

  h1 {
    font-size: 35px;
  }

  h2 {
    font-size: 30px;
  }

  h3 {
    font-size: 24px;
  }

  h4 {
    font-size: 18px;
    font-weight: normal;
  }

  .important-font {
    color: #21BEB4;
    font-weight: bold;
  }

  .hide {
    display: none !important;
  }

  .force-full-width {
    width: 100% !important;
  }

  </style>

  <style type="text/css" media="screen">
      @media screen {
        @import url(http://fonts.googleapis.com/css?family=Open+Sans:400);

        /* Thanks Outlook 2013! */
        td, h1, h2, h3 {
          font-family: \'Open Sans\', \'Helvetica Neue\', Arial, sans-serif !important;
        }
      }
  </style>

  <style type="text/css" media="only screen and (max-width: 600px)">
    /* Mobile styles */
    @media only screen and (max-width: 600px) {

      table[class="w320"] {
        width: 320px !important;
      }

      table[class="w300"] {
        width: 300px !important;
      }

      table[class="w290"] {
        width: 290px !important;
      }

      td[class="w320"] {
        width: 320px !important;
      }

      td[class~="mobile-padding"] {
        padding-left: 14px !important;
        padding-right: 14px !important;
      }

      td[class*="mobile-padding-left"] {
        padding-left: 14px !important;
      }

      td[class*="mobile-padding-right"] {
        padding-right: 14px !important;
      }

      td[class*="mobile-padding-left-only"] {
        padding-left: 14px !important;
        padding-right: 0 !important;
      }

      td[class*="mobile-padding-right-only"] {
        padding-right: 14px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-block"] {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-bottom: 15px !important;
      }

      td[class*="mobile-no-padding-bottom"] {
        padding-bottom: 0 !important;
      }

      td[class~="mobile-center"] {
        text-align: center !important;
      }

      table[class*="mobile-center-block"] {
        float: none !important;
        margin: 0 auto !important;
      }

      *[class*="mobile-hide"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        line-height: 0 !important;
        font-size: 0 !important;
      }

      td[class*="mobile-border"] {
        border: 0 !important;
      }
    }
  </style>
</head>
<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td align="center" valign="top" bgcolor="#ffffff"  width="100%">

    <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td style="background:#1f1f1f" width="100%">

          <center>
            <table cellspacing="0" cellpadding="0" width="600" class="w320">
              <tr>
                <td valign="top" class="mobile-block mobile-no-padding-bottom mobile-center" width="270" style="background:#1f1f1f;padding:10px 10px 10px 20px;">
                  
                </td>
                <td valign="top" class="mobile-block mobile-center" width="270" style="background:#1f1f1f;padding:10px 15px 10px 10px">
                 
                </td>
              </tr>
            </table>
          </center>

        </td>
      </tr>
      <tr>
        <td style="border-bottom:1px solid #e7e7e7;">

          <center>
            <table cellpadding="0" cellspacing="0" width="600" class="w320">
              <tr>
                <td align="left" class="mobile-padding" style="padding:20px 20px 0">

                  <br class="mobile-hide" />

                  <h1>Welcome to Shoppool</h1>

                  <br>
                  We\'re excited you\'re here!<br>
                  <br>

                  <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
                    <tr>
                      <td style="width:130px;background:#D84A38;">
                        <div>
                          <!--[if mso]>
                          <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="#" style="height:33px;v-text-anchor:middle;width:130px;" stroke="f" fillcolor="#D84A38">
                            <w:anchorlock/>
                            <center>
                          <![endif]-->
                              <a href="#"
                        style="background-color:#D84A38;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:33px;text-align:center;text-decoration:none;width:130px;-webkit-text-size-adjust:none;">Activate!</a>
                          <!--[if mso]>
                            </center>
                          </v:rect>
                          <![endif]-->
                        </div>
                      </td>
                      <td width="316" style="background-color:#ffffff; font-size:0; line-height:0;">&nbsp;</td>
                    </tr>
                  </table>
                  <br><br>
                </td>
                <td class="mobile-hide" style="padding-top:20px;padding-bottom:0;vertical-align:bottom">
                  <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td align="right" valign="bottom" width="220" style="padding-right:20px; padding-bottom:0; vertical-align:bottom;">
                      <img  style="display:block" src="https://www.filepicker.io/api/file/AvB8yENR7OdiUqonW05y"  width="174" height="294" alt="iphone"/>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </center>

        </td>
      </tr>
    </table>

    </td>
  </tr>
</table>
</body>
</html>';
}

function order_confirmation_email() {
    $item_quantity = isset($_SESSION['this']) ? $_SESSION['item_quantity']: $_SESSION['item_quantity'] = "0";

    $item_total = isset($_SESSION['item_total']) ? $_SESSION['item_total']: $_SESSION['item_total'] = "0";
    $cart_message = cart_for_email();
    $message = <<<END
<!-- Configuration-->
<?php require_once("../resources/functions.php"); ?>
<!-- Header-->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/shop-homepage.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<!-- Page Content -->
<div class="container">
    <!-- /.row -->
    <div class="row">
        <h1>Checkout</h1>
        <form action="thank_you.php?order=1" method="post">
            {$cart_message}
        </form>
        <!--  ***********CART TOTALS*************-->
        <div class="col-xs-4 pull-right ">
            <h2>Cart Totals</h2>
            <table class="table table-bordered" cellspacing="0">
                <tr class="cart-subtotal">
                    <th>Items:</th>
                    <td><span class="amount">
                        {$item_quantity}
                </tr>
                <tr class="shipping">
                    <th>Shipping and Handling</th>
                    <td>Free Shipping</td>
                </tr>

                <tr class="order-total">
                    <th>Order Total</th>
                    <td><strong><span class="amount">&#36;
            {$item_total}
        </span></strong> </td>
                </tr>
                </tbody>
            </table>
        </div><!-- CART TOTALS-->
    </div><!--Main Content-->
</div>
<!-- /.container -->
<div class="container">

    <hr>

    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; Shoppool 2030</p>
            </div>
        </div>
    </footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>


</body>

</html>
END;
    return $message;
}

/************************** FORGOT PASSWORD FUNCTIONS *****************/
function token_generator()
{
    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}

function recover_password() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {
            $email = clean($_POST['email']);
            echo $email;
            if (email_exists($email)) {
                $validation_code = md5($email + microtime());
                echo $validation_code;
                setcookie('temp_access_code', $validation_code, time() + 10*60);
                $sql = "UPDATE users SET validation_code = '" . escape_string($validation_code) . "' WHERE email = '".escape_string($email)."'";
                $result = query($sql);
                confirm($result);
                $subject = "Please reset your password";
                $message = " Here is your password reset code {$validation_code}
                Click here to reset your password http://localhost/ecom/public/code.php?email=$email&code=$validation_code";
                $headers = "From: noreply@yourwebsite.com";
                if (!send_email($email, $subject, $message, $headers)) {
                    validation_errors("Email could not be sent. ");
                }
                set_message("<p class='bg-success text-center'>Please check your email or spam folder for an activation for a password reset code.</p>");
                redirect("index.php");
            } else {
                validation_errors("email doesn't exist");
            }

        } else {
            redirect("index.php");
        }
        if (isset($_POST['cancel_submit'])) {
            redirect("login.php");
        }
    }
}

function validate_code() {
    if (isset($_COOKIE['temp_access_code'])) {
        if (!isset($_GET['email']) && !isset($_GET['code'])) {
            redirect("index.php");
        } else if (empty($_GET['email']) || empty($_GET['code'])){
            redirect("index.php");
        } else {
            if(isset($_POST['code'])) {
                $email = clean ($_GET['email']);
                $validation_code = clean($_POST['code']);
                $sql = "SELECT id FROM users WHERE validation_code = '" . escape_string($validation_code). "' ";
                $result = query($sql);
                if (row_count($result) == 1) {
                    setcookie('temp_access_code', $validation_code, time() + 5*60);
                    redirect("reset.php?email=$email&code=$validation_code");
                } else {
                    echo validation_errors("Sorry wrong validation code");
                }
            }
        }

    }
    else {
        set_message("<p class='bg-danger text-center'>Sorry, your validation code expired.</p>");
        redirect("recover.php");
    }

}

function password_reset() {
    if (isset($_COOKIE['temp_access_code'])) {
        if (isset($_GET['email']) && isset($_GET['code'])) {
            if (isset($_SESSION['token']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
                if ($_POST['password'] === $_POST['confirm_password']) {
                    $updated_password=md5($_POST['password']);
                    $sql = "UPDATE users SET password = '" . escape_string($updated_password) . "', validation_code = 0 WHERE email = '" . escape_string($_GET['email']) . "'";
                    query($sql);
                    set_message("<p class='bg-success text-center'>Your password has been updated. Please login.</p>");
                    redirect("login.php");
                }
            }
        }
    } else {
        set_message("<p class='bg-danger text-center'>Sorry your time has expired.</p>");
        redirect("recover.php");
    }
}


?>
