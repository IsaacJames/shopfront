<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="shopfront.css" type="text/css" />
  <title>Items for sale</title>
</head>

<body>

<script src="shopfront.js"></script>

<h1>Items for Sale</h1>

<hr />

<form name="order" action="shopback.php" method="POST">

<stock_list>

  <stock_item>
    <item_photo class="heading">Photo</item_photo>
    <item_name class="heading">Name</item_name>
    <item_info class="heading">Description</item_info>
    <item_price class="heading"> &pound; (exc. VAT)</item_price>
    <item_quantity class="heading">Quantity</item_quantity>
    <line_cost class="heading">Cost</line_cost>
  </stock_item>

  <stock_item id="crawdad">
    <item_photo><a href="piks/large/crawdad.jpg"><img src="piks/thumbnail/crawdad.jpg" border="0" /></a></item_photo>
    <item_name>Crawdad</item_name>
    <item_info>It's actually a 'crayfish'.</item_info>
    <item_price>4.50</item_price>
    <item_quantity><input name="crawdad" type="text" value="0" pattern="[0-9]+" size="3" onchange="updateLineCost(this, 'crawdad');" /></item_quantity>
    <line_cost>0.00</line_cost>
  </stock_item>

  <stock_item id="gorilla">
    <item_photo><a href="piks/large/gorilla.jpg"><img src="piks/thumbnail/gorilla.jpg" border="0" /></a></item_photo>
    <item_name>Gorilla</item_name>
    <item_info>Gives a friendly wave.</item_info>
    <item_price>8.50</item_price>
    <item_quantity><input name="gorilla" type="text" value="0" pattern="[0-9]+" size="3" onchange="updateLineCost(this, 'gorilla');" /></item_quantity>
    <line_cost>0.00</line_cost>
  </stock_item>

  <stock_item id="ninja">
    <item_photo><a href="piks/large/ninja.jpg"><img src="piks/thumbnail/ninja.jpg" border="0" /></a></item_photo>
    <item_name>Ninja</item_name>
    <item_info>Hero in a half-shell.</item_info>
    <item_price>12.50</item_price>
    <item_quantity><input name="ninja" type="text" value="0" pattern="[0-9]+" size="3" onchange="updateLineCost(this, 'ninja');" /></item_quantity>
    <line_cost>0.00</line_cost>
  </stock_item>

  <stock_item id="psion">
    <item_photo><a href="piks/large/psion.jpg"><img src="piks/thumbnail/psion.jpg" border="0" /></a></item_photo>
    <item_name>Psion 5</item_name>
    <item_info>A computing classic - rare.</item_info>
    <item_price>125.00</item_price>
    <item_quantity><input name="psion" type="text" value="0" pattern="[0-9]+" size="3" onchange="updateLineCost(this, 'psion');" /></item_quantity>
    <line_cost>0.00</line_cost>
  </stock_item>

  <stock_item id="totem">
    <item_photo><a href="piks/large/totem.jpg"><img src="piks/thumbnail/totem.jpg" border="0" /></a></item_photo>
    <item_name>Totem</item_name>
    <item_info>Mysterious and wooden (untold supernatural powers).</item_info>
    <item_price>150.00</item_price>
    <item_quantity><input name="totem" type="text" value="0" pattern="[0-9]+" size="3" onchange="updateLineCost(this, 'totem');" /></item_quantity>
    <line_cost>0.00</line_cost>
  </stock_item>


</stock_list>

<br />

<p>Sub-total: <span id="sub_total"></span></p>

<p>Delivery charge: <span id="delivery_charge"></span></p>

<p>VAT: <span id="vat"></span></p>

<p>Total: <span id="total"></span></p>

<hr />

<p>Credit Card type:
<select name="cc_type" size="1" required>
<option value="" selected>-</option>
<option value="mastercard">MasterCard</option>
<option value="visa">Visa</option>
</select>
</p>

<p>Credit Card number:
<input type="text" name="cc_number" pattern="[0-9]{16}" size="16" /></p>

<p>Name on Credit Card (also the name for delivery):
<input type="text" name="cc_name" size="80" /></p>

<p>Credit Card security code:
<input type="text" name="cc_code" pattern="[0-9]{3}" size="3" /></p>

<p>Delivery street address:
<input type="text" name="delivery_address" size="128" /></p>

<p>Delivery postcode:
<input type="text" name="delivery_postcode" size="40" /></p>

<p>Delivery country:
<input type="text" name="delivery_country" size="80" /></p>

<p>Email:
<input type="email" name="email" /></p>

<hr />

<input type="submit" value="Place Order" />

</form>

<hr />

</body>
</html>
