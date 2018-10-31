/*
 * item_id: string (id of item)
 * element: string (tag name of element)
 */
function getStockItemValue(item_id, element) {
  let i = document.getElementById(item_id);
  let e = i.getElementsByTagName(element)[0];  // assume only 1!
  let v = e.innerHTML;
  return v;
}

/*
 * item_id: string (id of item)
 * element: string (tag name of element)
 * value: string (the value of the element)
 */
function setStockItemValue(item_id, element, value) {
  let i = document.getElementById(item_id);
  let e = i.getElementsByTagName(element)[0];  // assume only 1!
  e.innerHTML = value;
}

/*
 * e: object from DOM tree (item_quantity that made )
 * item_id: string (id of item)
 */
function updateLineCost(e, item_id) {
  let p = getStockItemValue(item_id, "item_price");
  let q = e.value;
  let c = p * q; // implicit type conversion
  c = c.toFixed(2); // 2 decimal places always.
  setStockItemValue(item_id, "line_cost", c);
  updateSubTotal();
  updateDeliveryCharge();
  updateVAT();
  updateTotal();
}

  /*
   * Calculates and updates sub total value using line cost values
   */
  function updateSubTotal() {
    let t = 0;
    let lc = document.getElementsByTagName("line_cost");
    for (i = 1; i < lc.length; i++) {
      t += Number(lc[i].innerHTML);
    }
    t = t.toFixed(2);
    let s = document.getElementById("sub_total");
    s.innerHTML = t;
  }

  /*
   * Calculates and updates delivery charge value using sub total value
   */
  function updateDeliveryCharge() {
    let s = document.getElementById("sub_total");
    let c = s.innerHTML * 0.1;
    c = c.toFixed(2);
    let d = document.getElementById("delivery_charge");
    d.innerHTML = c;
  }

  /*
   * Calculates and updates VAT value using sub total and delivery charge values
   */
  function updateVAT() {
    let s = document.getElementById("sub_total");
    let d = document.getElementById("delivery_charge");
    let v = (Number(s.innerHTML) + Number(d.innerHTML)) * 0.2;
    v = v.toFixed(2);
    let vt = document.getElementById("vat");
    vt.innerHTML = v;
  }

  /*
   * Calculates and updates total value using sub total, delivery charge and VAT values
   */
  function updateTotal() {
    let s = document.getElementById("sub_total");
    let d = document.getElementById("delivery_charge");
    let v = document.getElementById("vat");
    let t = Number(s.innerHTML) + Number(d.innerHTML) + Number(v.innerHTML);
    t = t.toFixed(2);
    let tl = document.getElementById("total");
    tl.innerHTML = t;
  }

  /*
   * Displays confirmation message confirming order details
   */
  function display_confirmation() {
    let cc_number = document.getElementsByName("cc_number")[0].value;
    let cc_name = document.getElementsByName("cc_name")[0].value;
    let cc_code = document.getElementsByName("cc_code")[0].value;
    let delivery_address = document.getElementsByName("delivery_address")[0].value;
    let delivery_postcode = document.getElementsByName("delivery_postcode")[0].value;
    let delivery_country = document.getElementsByName("delivery_country")[0].value;
    let email = document.getElementsByName("email")[0].value;

    // Re-display items of stock list with positive quantities
    let t = "<stock_list\n" +
              "<stock_item>\n" +
                "<item_name class=\"heading\">Name</item_name> \n" +
                "<item_price class=\"heading\"> &pound; (exc. VAT)</item_price> \n" +
                "<item_quantity class=\"heading\">Quantity</item_quantity> \n" +
                "<line_cost class=\"heading\">Cost</line_cost> \n" +
              "</stock_item>";

    let s = document.getElementsByTagName("stock_item");
    for (var i = 1; i < s.length; i++) {
      let item_name = s[i].getElementsByTagName("item_name")[0].innerHTML;
      let item_price = s[i].getElementsByTagName("item_price")[0].innerHTML;
      let item_quantity = s[i].getElementsByTagName("item_quantity")[0].firstChild.value;
      let line_cost = s[i].getElementsByTagName("line_cost")[0].innerHTML;
      if (item_quantity > 0) {
        let r =  "<stock_item>\n" +
        "    <item_name>"+item_name+"</item_name>\n" +
        "    <item_price>"+item_price+"</item_price>\n" +
        "    <item_quantity>"+item_quantity+"</item_quantity>\n" +
        "    <line_cost>"+line_cost+"</line_cost>\n" +
        "  </stock_item>\n\n";
        t = t + r;
      }
    }
    t = t + "</stock_list>\n" + "<hr />\n";

    // Re-display payment and delivery information
    let c = "<p>Credit Card Number: " + cc_number + "</p>\n" +
            "<p>Name on Credit Card: " + cc_name + "</p>\n" +
            "<p>Credit Card sercurity code: " + cc_code + "</p>\n" +
            "<p>Delivery street address: " + delivery_address + "</p>\n" +
            "<p>Delivery postcode: " + delivery_postcode + "</p>\n" +
            "<p>Delivery country: " + delivery_country + "</p>\n" +
            "<p>Email: " + email + "</p>\n" +
            "<hr />\n";
    let info = document.getElementById("info");
    info.innerHTML = t + c;
    info.style.display = "block";  // Unhide info tag

    let f = document.getElementById("stock");
    f.style.display = "none";  // Hide stock tag

    c = '<p>Is this correct? \
        &nbsp; <input class="yes" type="submit" value="Yes" /> \
        &nbsp; <input class="no" type="button" value="No" onclick="display_form()" /> \
        </p>';
   let confirm = document.getElementById("confirm");
   confirm.innerHTML = c;
   confirm.style.display = "block";  // Unhide confirm tag
  }

  /*
   * Re-displays original form if user rejects confirmation message
   */
  function display_form() {
    let confirm = document.getElementById("confirm");
    confirm.innerHTML = "";
    confirm.style.display = "none";
    let info = document.getElementById("info");
    info.innerHTML = "";
    info.style.display = "none";
    let s = document.getElementById("stock");
    s.style.display = "block";
  }

  /*
   * Validates order details
   */
  function validate_form(e) {
    set_cc_number_pattern();  // Set credit card number pattern corresponding to selected card type
    let valid = true;  // True if details are all valid

    // Check whether order is empty (i.e. all item quantities are zero)
    let empty_order = true;
    let q = document.getElementsByTagName("item_quantity");
    for (i = 1; i < q.length; i++) {
      if (q[i].firstChild.value > 0) empty_order = false;
    }
    if (empty_order) {
      alert("Order is empty!"); // alert user if order is empty
      valid = false;
    }

    // Validate each payment and delivery detail input
    let inputs = document.getElementsByTagName("input");
    for (i = inputs.length - 1; i >= 0; i--) {
      if (!inputs[i].checkValidity()) {
        inputs[i].style.border = "1px solid red";  // If input is invalid highlight with red border
        inputs[i].reportValidity();  // Reports the top most invalid input with popover message
        valid = false;  // Order is invalid and cannot be submitted
      }
    }
    if (valid) display_confirmation();  // Display confirmation message
  }

  /*
   * Validates a single input tag and removes red border if new value is valid
   * e: the input element to be validated
   */
  function validate(e) {
    if (e.checkValidity()) {
      e.style.border = "";
    }
  }

  /*
   * Sets the required pattern of the credit card number input to the pattern
   * corresponding to the selected card type
   */
  function set_cc_number_pattern() {
    let e = document.getElementById("cc_number");
    let cc_type = document.getElementById("cc_type");
    switch (cc_type.value) {
      case "visa":
        e.pattern = "4[0-9]{15}"; // Visa pattern
        if (!e.checkValidity()) {
          e.setCustomValidity("Visa card numbers should have 16 digits and the first digit should be 4");
        }
        break;
      case "mastercard":
        e.pattern = "5[0-9]{15}"; // MasterCard pattern
        if (!e.checkValidity()) {
          e.setCustomValidity("MasterCard numbers should have 16 digits and the first digit should be 5");
        }
        break;
      default:
        e.pattern = "a^";  // Unmatchable pattern if no card type has been selected
        e.setCustomValidity("Please select a card type");
    }
  }
