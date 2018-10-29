
<?php
clearstatcache(); // http://php.net/manual/en/function.clearstatcache.php

define("ORDERS_FILE_NAME", "orders.txt"); // Local file - insecure!
define("ORDERS_FILE_LINE_SIZE", 256); // 256 line length should enough.

if (!file_exists(ORDERS_FILE_NAME)) {
  die("File not found for read - " . ORDERS_FILE_NAME . "\n"); // Script exits.
}

$f = fopen(ORDERS_FILE_NAME, "r");
$stock_list = null;
print_r($stock_list);

while (($row = fgetcsv($f, ORDERS_FILE_LINE_SIZE)) != false) {

$date = $row[0];
echo "<date>Order Placed: {$date}</date>\n";

$row = fgetcsv($f, ORDERS_FILE_LINE_SIZE);
$sub_total = $row[0];
$delivery_charge = $row[1];
$vat = $row[2];
$total = $row[3];

echo "<total>Total: {$total}</total>\n";

  while (($row = fgetcsv($f, ORDERS_FILE_LINE_SIZE)) != false) {
    echo "<p>$row[0]</p>\n";
    echo "<p>$row[1]</p>\n";
    echo "<p>$row[2]</p>\n";
    echo "<p>$row[3]</p>\n";
  }
}
fclose($f);
?>
