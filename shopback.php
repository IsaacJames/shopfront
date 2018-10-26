<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="shopback.css" type="text/css" />
  <title>Receipt</title>
</head>

<body>

<h1>Receipt -- PHP yet to be completed!</h1>

<p>
<?php
// http://php.net/manual/en/function.htmlspecialchars.php
function getFormInfo($k) {
  return isset($_POST[$k]) ? htmlspecialchars($_POST[$k]) : null;
}

echo "Date of order: ", date('l jS \of F Y h:i:s A'), "<br />\n";
echo "Transaction id: ", hash("md5", date('l jS \of F Y h:i:s A')), "<br />\n";

echo "<hr />", "<h2> Order details </h2>";

foreach (array_keys($_POST) as $k) {
  $v = getFormInfo($k);
  echo "{$k} : {$v}<br />\n";
}
?>
</p>

</body>
</html>
