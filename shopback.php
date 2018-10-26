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
