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

// Check that all personal details and payment details are complete
function isDetailsComplete() {
  if (!($cc_type = getFormInfo("cc_type")) ||
    !($cc_number = getFormInfo("cc_number")) ||
    !($cc_name = getFormInfo("cc_name")) ||
    !($cc_code = getFormInfo("cc_code")) ||
    !($delivery_address = getFormInfo("delivery_address")) ||
    !($delivery_postcode = getFormInfo("delivery_postcode")) ||
    !($email = getFormInfo("email"))
    ) {
    return false;
  }
  return true;
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
    echo isValidCardNumber($cc_type, $cc_number);
    return true;
  }
}

/*
function isValidOrder() {
  if (!isDetailsComplete()) {
    echo "<p>Some essential information is missing.</p>";
    return false;
  }
  if (!isValidCardNumber() || !isValidCardCode()) {
    echo "<p>Card could not be processed.</p>";
    return false;
  }
  if (!isValidEmail()) {
    echo "<p>Email is invalid</p>";
    return false;
  }
  if (!isValidQuantities()) {
    echo "<p>Ivalid order quantitiy.</p>";
    return false;
  }
}*/

if (isValidOrder()) {
  echo "Date of order: ", date('l jS \of F Y h:i:s A'), "<br />\n";
  echo "Transaction id: ", hash("md5", date('l jS \of F Y h:i:s A')), "<hr />\n";

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
    }
  }
}
else {
  echo "<p>Please press the BACK button on your browser, or <a href=\"shopfront.php\">click here</a> to try again</p>";
}

?>

</p>

</body>
</html>
