/*
 * This is a starting point only -- not yet complete!
 */

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
   * e: object from DOM tree (item_quantity that made )
   * item_id: string (id of item)
   */
  function updateSubTotal() {
    let t = 0;
    let lc = document.getElementsByTagName("line_cost");
    for (i = 1; i < lc.length; i++) {
      t += Number(lc[i].innerHTML);
    }
    t = t.toFixed(2); // 2 decimal places always.
    let s = document.getElementById("sub_total");
    s.innerHTML = t;
  }

  function updateDeliveryCharge() {
    let s = document.getElementById("sub_total");
    let c = s.innerHTML * 0.1;
    c = c.toFixed(2);
    let d = document.getElementById("delivery_charge");
    d.innerHTML = c;
  }

  function updateVAT() {
    let s = document.getElementById("sub_total");
    let d = document.getElementById("delivery_charge");
    let v = (Number(s.innerHTML) + Number(d.innerHTML)) * 0.2;
    v = v.toFixed(2);
    let vt = document.getElementById("vat");
    vt.innerHTML = v;
  }

  function updateTotal() {
    let s = document.getElementById("sub_total");
    let d = document.getElementById("delivery_charge");
    let v = document.getElementById("vat");
    let t = Number(s.innerHTML) + Number(d.innerHTML) + Number(v.innerHTML);
    t = t.toFixed(2);
    let tl = document.getElementById("total");
    tl.innerHTML = t;
  }

  function check() {
    let cc_number = document.getElementsByName("cc_number")[0].value;
    let cc_name = document.getElementsByName("cc_name")[0].value;
    let cc_code = document.getElementsByName("cc_code")[0].value;
    let delivery_address = document.getElementsByName("delivery_address")[0].value;
    let delivery_postcode = document.getElementsByName("delivery_postcode")[0].value;
    let delivery_country = document.getElementsByName("delivery_country")[0].value;
    let email = document.getElementsByName("email")[0].value;

    let c = "<p>Credit Card Number: " + cc_number + "</p>\n" +
            "<p>Name on Credit Card: " + cc_name + "</p>\n" +
            "<p>Credit Card sercurity code: " + cc_code + "</p>\n" +
            "<p>Delivery street address: " + delivery_address + "</p>\n" +
            "<p>Delivery postcode: " + delivery_postcode + "</p>\n" +
            "<p>Delivery country: " + delivery_country + "</p>\n" +
            "<p>Email: " + email + "</p>\n" +
            "<hr />\n";
    let confirm = document.getElementById("confirm");
    confirm.innerHTML = c;
    confirm.style.display = "block";

    let s = document.getElementById("stock");
    s.style.display = "none";

    c = '<p>Is this correct? \
        &nbsp; <input class="yes" type="submit" value="Yes" onclick="no()" /> \
        &nbsp; <input class="no" type="button" value="No" onclick="no()" /> \
        </p>';
   let info = document.getElementById("info");
   info.innerHTML = c;
   info.style.display = "block";
  }

  function no() {
    let confirm = document.getElementById("confirm");
    confirm.style.display = "none";
    let info = document.getElementById("info");
    info.style.display = "none";
    let s = document.getElementById("stock");
    s.style.display = "block";
  }
