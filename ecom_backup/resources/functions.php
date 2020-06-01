<?php

require("../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("../vendor/phpmailer/phpmailer/src/SMTP.php");
require("../vendor/phpmailer/phpmailer/src/Exception.php");


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

    return mysqli_query($connection, $sql);
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
    $query = query(" SELECT * FROM products");
    confirm($query);
    while ($row = fetch_array($query)) {
        $product_image = display_image($row['product_image']);
        $product = <<<DELIMETER
        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
                <a href="item.php?id={$row['product_id']}"><img src="../resources/{$product_image}" alt=""></a>
                <div class="caption">
                    <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                    </h4>
                    <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to Cart</a>
                </div>

            </div>
        </div>
DELIMETER;
echo $product;
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
        <div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img height='100' width='100' src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
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
        <div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
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
    $query = query(" SELECT * FROM products");
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
        $product_price = escape_string($_POST['product_price']);
        $product_description = escape_string($_POST['product_description']);
        $short_desc = escape_string($_POST['short_desc']);
        $product_quantity = escape_string($_POST['product_quantity']);
        $product_image = escape_string($_FILES['file']['name']);
        $image_temp_location = escape_string($_FILES['file']['tmp_name']);

        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

        $query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
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
            if (register_user($username, $email, $password)) {
                set_message("<p class='bg-success text-center'>Please check your email or spam folder for an activation link</p>");
                redirect("index.php");
            } else {
                //set_message("<p class='bg-danger text-center'>Sorry we could not register the user</p>");
                //redirect("index.php");
            }
        }
    }
}

function register_user($username, $email, $password) {
    $username = escape_string($username);
    $email = escape_string($email);
    $password = escape_string($password);
    if (email_exists($email)) {
        return false;
    } else if (username_exists($username)) {
        return false;
    } else {
        $password = md5($password);
        $sql = "INSERT INTO users(username, email, password)";
        $sql .= " VALUES('$username', '$email', '$password')";
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
            if (login_user($email, $password)) {
                redirect("shop.php");
            } else {
                echo validation_errors("Your credentials are not correct");
            }
        }
    }

}

function login_user($email, $password) {
    $sql = "SELECT password, id, username FROM users WHERE email = '" . escape_string($email) . "' ";
    $result = query($sql);
    confirm($result);
    if (row_count($result) == 1) {
        $row = fetch_array($result);
        $db_password = $row['password'];
        if (md5($password) === $db_password) {
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $row['username'];
            return true;
        } else {
            return false;
        }
    } else {
        return false;
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
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Narrative Confirm Email</title>
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
    line-height: 150%;
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


  h1, h2, h3 {
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
                  <a href="#" style="text-decoration:none;">
                    <img src="https://www.filepicker.io/api/file/X9R4FqRPaEIS3vMxFXgl" width="142" height="30" alt="Your Logo"/>
                  </a>
                </td>
                <td valign="top" class="mobile-block mobile-center" width="270" style="background:#1f1f1f;padding:10px 15px 10px 10px">
                  <table border="0" cellpadding="0" cellspacing="0" class="mobile-center-block" align="right">
                    <tr>
                      
                    </tr>
                  </table>
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
                <td align="left" class="mobile-padding" style="padding:20px">

                  <br class="mobile-hide" />

                  <h2>Your order has shipped!</h2><br>
                  Hello,<br>
                  We would like you to know that your order has shipped! To track your order or make any changes please click the "my order" button below.<br>

                  <br>

                  <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
                    <tr>
                      <td style="width:100px;background:#D84A38;">
                        <div>
                          <!--[if mso]>
                          <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="#" style="height:33px;v-text-anchor:middle;width:100px;" stroke="f" fillcolor="#D84A38">
                            <w:anchorlock/>
                            <center>
                          <![endif]-->
                              <a href="localhost/ecom/public/order_history.php"
                        style="background-color:#D84A38;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:33px;text-align:center;text-decoration:none;width:100px;-webkit-text-size-adjust:none;">My Order</a>
                          <!--[if mso]>
                            </center>
                          </v:rect>
                          <![endif]-->
                        </div>
                      </td>
                      <td width="281" style="background-color:#ffffff; font-size:0; line-height:0;">&nbsp;</td>
                    </tr>
                  </table>
                </td>
                <td class="mobile-hide" style="padding-top:20px;padding-bottom:0; vertical-align:bottom;" valign="bottom">
                  <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td align="right" valign="bottom" style="padding-bottom:0; vertical-align:bottom;">
                        <img  style="vertical-align:bottom;" src="https://www.filepicker.io/api/file/9f3sP1z8SeW1sMiDA48o"  width="174" height="294" />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
      <tr>
        <td valign="top" style="background-color:#f8f8f8;border-bottom:1px solid #e7e7e7;">

          <center>
            <table border="0" cellpadding="0" cellspacing="0" width="600" class="w320" style="height:100%;">
              <tr>
                <td valign="top" class="mobile-padding" style="padding:20px;">
                  <table cellspacing="0" cellpadding="0" width="100%">
                  </table>
                  <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td style="padding-top:50px;">
                        <table cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td width="350" style="vertical-align:top;">
                              Thank you for your business. Please  <a href="#">contact us</a> with any questions regarding your order,<br>
                              <h4>Shoppool<h4>
                            </td>
                          </tr>
                        </table>
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


?>
