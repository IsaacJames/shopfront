<?php
clearstatcache(); // http://php.net/manual/en/function.clearstatcache.php

define("STOCK_FILE_NAME", "stock.txt"); // Local file - insecure!
define("STOCK_FILE_LINE_SIZE", 256); // 256 line length should enough.

define("PHOTO_DIR", "piks/large/"); // large photo, local files, insecure!
define("THUMBNAIL_DIR", "piks/thumbnail/"); // thumbnail, local files, insecure!

function photoCheck($photo) { // Do we have photos?
  $result = "";
  $p = PHOTO_DIR . $photo;
  $t = THUMBNAIL_DIR . $photo;
  if (!file_exists($p) || !file_exists($t)) { $result = "(No photo)"; }
  else { $result = "<a href=\"{$p}\"><img src=\"{$t}\" border=\"0\" /></a>"; }
  return $result;
}


if (!file_exists(STOCK_FILE_NAME)) {
  die("File not found for read - " . STOCK_FILE_NAME . "\n"); // Script exits.
}

$f = fopen(STOCK_FILE_NAME, "r");
$stock_list = null;
print_r($stock_list);
while (($row = fgetcsv($f, STOCK_FILE_LINE_SIZE)) != false) {
  $stock_item = array(
    "id" => $row[0], /// needs to be unique!
    "photo" => $row[0] . ".jpg",
    "name" => $row[1],
    "info" => $row[2],
    "price" => $row[3]);
  $stock_list[$row[0]] = $stock_item; // Add stock.
}
fclose($f);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="shopfront.css" type="text/css" />
  <title>Items for sale</title>
</head>

<body>

<h1>Items for Sale</h1>

<hr />

<form name="order" action="shopback.php" method="POST">

<!-- tag used to hide form when confirmation message is displayed -->
<div id = "stock">

<stock_list>

  <stock_item>
    <item_photo class="heading">Photo</item_photo>
    <item_name class="heading">Name</item_name>
    <item_info class="heading">Description</item_info>
    <item_price class="heading"> &pound; (exc. VAT)</item_price>
    <item_quantity class="heading">Quantity</item_quantity>
    <line_cost class="heading">Cost</line_cost>
  </stock_item>

<?php

foreach(array_keys($stock_list) as $id) {
  echo "  <stock_item id=\"{$id}\">\n";
  $item = $stock_list[$id];
  $p = photoCheck($item["photo"]);
  echo "    <item_photo>{$p}</item_photo>\n";
  echo "    <item_name>{$item["name"]}</item_name>\n";
  echo "    <item_info>{$item["info"]}</item_info>\n";
  echo "    <item_price>{$item["price"]}</item_price>\n";
  // Any non decimal digit input is ignored (replaced with empty string)
  echo "    <item_quantity><input name=\"{$id}\" type=\"text\" value=\"0\" pattern=\"[0-9]+\" size=\"3\"
            oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\" onchange=\"updateLineCost(this, '{$id}');\" /></item_quantity>\n";
  echo "    <line_cost>0.00</line_cost>\n";
  echo "  </stock_item>\n\n";
}

?>

</stock_list>

<hr />

<p>Sub-total: <span id="sub_total"></span></p>

<p>Delivery charge: <span id="delivery_charge"></span></p>

<p>VAT: <span id="vat"></span></p>

<p>Total: <span id="total"></span></p>

<hr />

<p>Credit Card type:
<select id="cc_type" name="cc_type" size="1" oninput="set_cc_number_pattern()" required>
<option value="" selected>-</option>
<option value="mastercard">MasterCard</option>
<option value="visa">Visa</option>
</select>
</p>

<p>Credit Card number:
  <!-- validate() function sets border colour to default if input is valid
  custom validity must be set to empty string on input event to clear custom validity popover message -->
  <input type="text" id="cc_number" name="cc_number" size="16" pattern="^a"
  onchange="validate(this)" oninput="this.value=this.value.replace(/[^0-9]/g,''); setCustomValidity('');" required/></p>

<p>Name on Credit Card (also the name for delivery):
  <input type="text" name="cc_name" size="80" onchange="validate(this)" required /></p>

<p>Credit Card security code:
  <!-- here a custom validity message is set on invalid event to provide a contextual popover message -->
  <input type="text" name="cc_code" pattern="[0-9]{3}" size="3"
  onchange="validate(this)" oninput="this.value=this.value.replace(/[^0-9]/g,''); setCustomValidity('');"
  oninvalid="setCustomValidity('Please enter a 3 digit positive value')" required/></p>

<p>Delivery street address:
  <input type="text" name="delivery_address" size="128" onchange="validate(this)" required/></p>

<p>Delivery postcode:
  <input type="text" name="delivery_postcode" size="40" onchange="validate(this)" required/></p>

<p>Delivery country:
  <input type="text" name="delivery_country" size="80" onchange="validate(this)" required/></p>

<p>Email:
  <!-- again, custom validity message is set on invalid event -->
  <input type="text" name="email" pattern="^[\w\.]+@(\w+\.)+\w+$"
  onchange="validate(this)" oninvalid="setCustomValidity('Please enter an email containing an \'@\' and at least one \'.\'')"
  oninput="setCustomValidity('')" required/></p>

<hr />

<!-- this button triggers validation of form data and display of confirmation message -->
<input type="button" value="Place Order" onclick="validate_form()"/>

</div>

<!-- tag to contain info from confirmation message -->
<div id = "info"></div>

<!-- tag to contain confirmation buttons -->
<div id = "confirm"></div>

<hr />

</form>

<script src="shopfront.js"></script>

</body>

</html>
