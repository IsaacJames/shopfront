<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="shopback.css" type="text/css" />
  <title>Receipt</title>
</head>

<body>

<h1>Receipt</h1>

<p>

<?php

// http://php.net/manual/en/function.htmlspecialchars.php
function getFormInfo($k) {
  return isset($_POST[$k]) ? htmlspecialchars($_POST[$k]) : null;
}

// Check that card number is 16 digits, an integer and has correct first number for card type
function isValidCardNumber($cc_type, $cc_number) {
  if (strlen($cc_number) == 16 && ctype_digit($cc_number) &&
      (($cc_type == "visa" && $cc_number[0] == 4) ||
      ($cc_type == "mastercard" && $cc_number[0] == 5))) {
    return true;
  }
  return false;
}

// Check that card code is 3 digits, positive and an integer
function isValidCardCode($cc_code) {
  if (strlen($cc_code) == 3 && ctype_digit($cc_code)) {
    return true;
  }
  return false;
}

// Check that email
function isValidEmail($email) {
  // echo preg_match("/\@(?=.*\.)/", $email);
  if (true) {
    return true;
  }
  return false;
}

// Checks quantities are integers, not negative and not all 0
function isValidQuantities() {
  $empty_order = true;
  foreach (array_keys($_POST) as $a) {
    $item = $_POST[$a];

    if (is_array($item)) {
      if (!ctype_digit($item["quantity"])) {
        return false;
      }
      elseif ($item["quantity"] > 0) {
        $empty_order = false;
      }
    }
  }
  return !$empty_order;
}

function isValidOrder() {
  if (!($cc_type = getFormInfo("cc_type")) ||
      !($cc_number = getFormInfo("cc_number")) ||
      !($cc_name = getFormInfo("cc_name")) ||
      !($cc_code = getFormInfo("cc_code")) ||
      !($delivery_address = getFormInfo("delivery_address")) ||
      !($delivery_postcode = getFormInfo("delivery_postcode")) ||
      !($email = getFormInfo("email"))
    ) {
    echo "<p>Some essential information is missing.</p>";
  }
  else if (!isValidCardNumber($cc_type, $cc_number) || !isValidCardCode($cc_code)) {
      echo "<p>Card could not be processed.</p>";
    }
  else if (!isValidEmail($email)) {
      echo "<p>Email is invalid</p>";
    }
  else if (!isValidQuantities()) {
      echo "<p>Invalid order quantitiy.</p>";
    }
  else {
    return true;
  }
}

if (isValidOrder()) {
  $date = date('l jS \of F Y h:i:s A');
  $id = hash("md5", $date);

  echo "<p>Date of order: " . $date . "</p>\n";
  echo "<p>Transaction id: " . $id . "</p\n>";
  echo "<hr />\n";

  define("ORDERS_FILE_NAME", "orders.txt"); // Local file - insecure!
  $f = fopen(ORDERS_FILE_NAME, "a");

  fwrite($f, "$date\n");
  fwrite($f, "{$_POST["sub_total"]},{$_POST["delivery_charge"]},{$_POST["vat"]},{$_POST["total"]}\n");

  // Print stock list headers
  echo "<stock_list>\n";
  echo "  <stock_item>\n";
  echo "    <item_name class=\"heading\">Name</item_name>\n";
  echo "    <item_price class=\"heading\"> &pound; (exc. VAT)</item_price>\n";
  echo "    <item_quantity class=\"heading\">Quantity</item_quantity>\n";
  echo "    <line_cost class=\"heading\">Cost</line_cost>\n";
  echo "  </stock_item>\n";

  foreach (array_keys($_POST) as $k) {
    $item = $_POST[$k];
    if (is_array($item) && $item["quantity"] > 0) {
      echo "  <stock_item id=\"$k\">\n";
      echo "    <item_name>{$item["name"]}</item_name>\n";
      echo "    <item_price>{$item["price"]}</item_price>\n";
      echo "    <item_quantity>{$item["quantity"]}</item_quantity>\n";
      echo "    <line_cost>{$item["cost"]}</line_cost>\n";
      echo "  </stock_item>\n\n";
      fwrite($f, "{$item["name"]},{$item["price"]},{$item["quantity"]},{$item["cost"]}\n");
    }
  }
  fwrite($f, "\n");

  echo "<div class=\"row\">";
  echo  "<div class=\"column\">";
  echo    "<h2>Delivery Address</h2>"
      <p>Some text..</p>
    </div>
    <div class="column" style="background-color:#bbb;">
      <h2>Column 2</h2>
      <p>Some text..</p>
    </div>
    <div class="column" style="background-color:#ccc;">
      <h2>Column 3</h2>
      <p>Some text..</p>
    </div>
  </div>

  echo "<hr />\n";
  echo "<p>Sub Total: {$_POST["sub_total"]}</p>";
  echo "<p>Delivery charge: {$_POST["delivery_charge"]}</p>";
  echo "<p>VAT: {$_POST["vat"]}</p>";
  echo "<p>Total: {$_POST["total"]}</p>";
  echo "<hr />\n";
  echo "<p>Name: {$_POST["cc_name"]}</p>";
  echo "<p>Delivery address: {$_POST["delivery_address"]}</p>";
  echo "<hr />\n";
  echo "<p>Credit card type: {$_POST["cc_type"]}</p>";
  echo "<p>Credit card number: ",substr($_POST["cc_number"], 0, 2),"XXXXXXXXXXXX",substr($_POST["cc_number"], 14, 2),"</p>";

  fclose($f);
}
else {
  echo "<p>Please press the BACK button on your browser, or <a href=\"shopfront.php\">click here</a> to try again</p>";
}

?>

</p>

</body>

</html>
