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
if (!($cc_type = getFormInfo($_POST["cc_type"])) ||
  !($cc_number = getFormInfo($_POST["cc_number"])) ||
  !($cc_name = getFormInfo($_POST["cc_name"])) ||
  !($cc_code = getFormInfo($_POST["cc_code"])) ||
  !($delivery_address = getFormInfo($_POST["delivery_address"])) ||
  !($delivery_postcode = getFormInfo($_POST["delivery_postcode"])) ||
  !($email = getFormInfo($_POST["email"]))
  ) {
  echo "<p>Some essential information is missing.</p>\n";
  echo "<p>Please press the BACK button on your browser, or <a href=\"shopfront.php\">click here</a> to try again</p>\n";
}

// Check that card number is 16 digits, an integer and has correct first number for card type
function checkCardNumber() {
  if (($cc_type == "Visa" && ($cc_number.length == 16 || $cc_number[0] == 4)) ||
      ($cc_type == "MasterCard" && ($cc_number.length == 16 || $cc_number[0] == 5)) ||
       is_integer(Number($cc_number))) {
    return true;
  }
  return false;
}

// Check that card code is 3 digits, positive and an integer
function checkCardCode() {
  if ($cc_code.length == 3 && Number($cc_code) > 0 && is_integer(Number($cc_code))) {
    return true;
  }
  return false;
}

// Check that email
function checkEmail() {
  return true;
}

// Checks quantities are integers, not negative and not all 0
function checkValidQuantities() {
  $empty_order = true;
  foreach (array_keys($_POST) as $a) {
    $item = $_POST[$a];
    if (is_array($a)) {
      if ($item["quantity"] < 0 || !is_integer($item["quantity"])) {
        return false;
      }
      elseif ($item["quantity"] > 0) {
        $empty_order = false;
      }
    }
  }
  return !$empty_order;
}

echo "Date of order: ", date('l jS \of F Y h:i:s A'), "<br />\n";
echo "Transaction id: ", hash("md5", date('l jS \of F Y h:i:s A')), "<hr />\n";

?>
<stock_list>

  <stock_item>
    <item_name class="heading">Name</item_name>
    <item_price class="heading"> &pound; (exc. VAT)</item_price>
    <item_quantity class="heading">Quantity</item_quantity>
    <line_cost class="heading">Cost</line_cost>
  </stock_item>

<?php

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
?>
</p>

</body>
</html>
