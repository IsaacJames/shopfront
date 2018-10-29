<?php
clearstatcache(); // http://php.net/manual/en/function.clearstatcache.php

define("STOCK_FILE_NAME", "stock.txt"); // Local file - insecure!
define("STOCK_FILE_LINE_SIZE", 256); // 256 line length should enough.

if (!file_exists(STOCK_FILE_NAME)) {
  die("File not found for read - " . STOCK_FILE_NAME . "\n"); // Script exits.
}

$f = fopen(STOCK_FILE_NAME, "r");
$stock_list = null;
print_r($stock_list);
while (($row = fgetcsv($f, STOCK_FILE_LINE_SIZE)) != false) {
  $stock_item = array(
    "id" => $row[0], /// needs to be unique!
    "name" => $row[1],
    "price" => $row[3]);
  $stock_list[$row[0]] = $stock_item; // Add stock.
}
fclose($f);
?>

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
function isValidQuantities($stock_list) {
  $empty_order = true;
  foreach (array_keys($stock_list) as $id) {
    $q = $_POST[$id];

    if (!ctype_digit($q)) {
      return false;
    }
    elseif ($q > 0) {
      $empty_order = false;
    }
  }
  return !$empty_order;
}

$try_again = "\n<p>Please press the BACK button on your browser, or <a href=\"shopfront.php\">click here</a> to try again</p>";

if (!($cc_type = getFormInfo("cc_type")) ||
    !($cc_number = getFormInfo("cc_number")) ||
    !($cc_name = getFormInfo("cc_name")) ||
    !($cc_code = getFormInfo("cc_code")) ||
    !($delivery_address = getFormInfo("delivery_address")) ||
    !($delivery_postcode = getFormInfo("delivery_postcode")) ||
    !($delivery_country = getFormInfo("delivery_country")) ||
    !($email = getFormInfo("email"))
  ) {
  echo "<p>Some essential information is missing.</p>", $try_again;
}
else if (!isValidCardNumber($cc_type, $cc_number) || !isValidCardCode($cc_code)) {
    echo "<p>Card could not be processed.</p>", $try_again;
  }
else if (!isValidEmail($email)) {
    echo "<p>Email is invalid</p>", $try_again;
  }
else if (!isValidQuantities($stock_list)) {
    echo "<p>Invalid order quantitiy.</p>", $try_again;
  }

else {
  echo "<p>Date of order: ", date('l jS \of F Y h:i:s A'), "</p>\n";
  echo "<p>Transaction id: ", hash("md5", date('l jS \of F Y h:i:s A')), "</p\n>";
  echo "<hr />\n";

  // Print stock list headers
  echo "<stock_list>\n";
  echo "  <stock_item>\n";
  echo "    <item_name class=\"heading\">Name</item_name>\n";
  echo "    <item_price class=\"heading\"> &pound; (exc. VAT)</item_price>\n";
  echo "    <item_quantity class=\"heading\">Quantity</item_quantity>\n";
  echo "    <line_cost class=\"heading\">Cost</line_cost>\n";
  echo "  </stock_item>\n";

  $sub_total = 0;

  foreach(array_keys($stock_list) as $id) {
    $q = getFormInfo($id);

    if ($q > 0) {
      $item = $stock_list[$id];
      $lc = $item["price"] * $q;
      $lc = number_format($lc, 2);
      $sub_total += $lc;
      echo "  <stock_item id=\"{$id}\">\n";
      echo "    <item_name>{$item["name"]}</item_name>\n";
      echo "    <item_price>{$item["price"]}</item_price>\n";
      echo "    <item_quantity>{$q}</item_quantity>\n";
      echo "    <line_cost>{$lc}</line_cost>\n";
      echo "  </stock_item>\n\n";
      }
  }
  echo "</stock_list>\n";

  $sub_total = number_format($sub_total, 2);
  $delivery_charge = $sub_total * 0.1;
  $delivery_charge = number_format($delivery_charge, 2);
  $vat = ($sub_total + $delivery_charge) * 0.2;
  $vat = number_format($vat, 2);
  $total = $sub_total + $delivery_charge + $vat;

  echo "<hr />\n";
  echo "<div class=\"row\">\n";
  echo "  <div class=\"column\">\n";
  echo "    <h3>Delivery Address</h2>\n";
  echo "    <p>{$cc_name}</p>\n";
  echo "    <p>{$delivery_address}</p>\n";
  echo "    <p>{$delivery_postcode}</p>\n";
  echo "    <p>{$delivery_country}</p>\n";
  echo "  </div>\n";
  echo "  <div class=\"column\">\n";
  echo "    <h3>Payment Method</h2>\n";
  echo "    <p>{$cc_type}</p>\n";
  echo "    <p>" . substr($cc_number, 0, 2) . "XXXXXXXXXXXX" . substr($cc_number, 14, 2) . "</p>\n";
  echo "  </div>\n";
  echo "  <div class=\"column\">\n";
  echo "    <h3>Order Summary</h2>\n";
  echo "    <p>Sub Total: {$sub_total}</p>\n";
  echo "    <p>Delivery charge: {$delivery_charge}</p>\n";
  echo "    <p>VAT: {$vat}</p>\n";
  echo "    <p>Total: {$total}</p>\n";
  echo "  </div>\n";
  echo "</div>\n";
  echo "<hr />\n";
}

?>

</p>

</body>

</html>
