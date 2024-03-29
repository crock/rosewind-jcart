<?php

// jCart v1.3
// http://conceptlogic.com/jcart/

error_reporting(E_ALL);

// Cart logic based on Webforce Cart: http://www.webforcecart.com/
class Jcart {

   public $config     = array();
   private $items     = array();
   private $names     = array();
   private $prices    = array();
   private $qtys      = array();
   private $urls      = array();
   private $subtotal  = 0;
   /*
    * I don't care what the developer says, I need this property.
    */
   //private $itemCount = 0;
   public $itemCount = 0;

   function __construct() {

      // Get $config array
      include_once('config-loader.php');
      $this->config = $config;
   }

   /**
   * Get cart contents
   *
   * @return array
   */
   public function get_contents() {
      $items = array();
      foreach($this->items as $tmpItem) {
         $item = null;
         $item['id']       = $tmpItem;
         $item['name']     = $this->names[$tmpItem];
         $item['price']    = $this->prices[$tmpItem];
         $item['qty']      = $this->qtys[$tmpItem];
         $item['url']      = $this->urls[$tmpItem];
         $item['subtotal'] = $item['price'] * $item['qty'];
         $items[]          = $item;
      }
      return $items;
   }

   /**
   * Add an item to the cart
   *
   * @param string $id
   * @param string $name
   * @param float $price
   * @param mixed $qty
   * @param string $url
   *
   * @return mixed
   */
   private function add_item($id, $name, $price, $qty = 1, $url) {

      $validPrice = false;
      $validQty = false;

      // Verify the price is numeric
      if (is_numeric($price)) {
         $validPrice = true;
      }

      // If decimal quantities are enabled, verify the quantity is a positive float
      if ($this->config['decimalQtys'] === true && filter_var($qty, FILTER_VALIDATE_FLOAT) && $qty > 0) {
         $validQty = true;
      }
      // By default, verify the quantity is a positive integer
      elseif (filter_var($qty, FILTER_VALIDATE_INT) && $qty > 0) {
         $validQty = true;
      }

      // Add the item
      if ($validPrice !== false && $validQty !== false) {

         // If the item is already in the cart, increase its quantity
         if(isset($this->qtys[$id]) &&  $this->qtys[$id] > 0) {
            $this->qtys[$id] += $qty;
            $this->update_subtotal();
         }
         // This is a new item
         else {
            $this->items[]     = $id;
            $this->names[$id]  = $name;
            $this->prices[$id] = $price;
            $this->qtys[$id]   = $qty;
            $this->urls[$id]   = $url;
         }
         $this->update_subtotal();
         return true;
      }
      elseif ($validPrice !== true) {
         $errorType = 'price';
         return $errorType;
      }
      elseif ($validQty !== true) {
         $errorType = 'qty';
         return $errorType;
      }
   }

   /**
   * Update an item in the cart
   *
   * @param string $id
   * @param mixed $qty
   *
   * @return boolean
   */
   private function update_item($id, $qty) {

      // If the quantity is zero, no futher validation is required
      if ((int) $qty === 0) {
         $validQty = true;
      }
      // If decimal quantities are enabled, verify it's a float
      elseif ($this->config['decimalQtys'] === true && filter_var($qty, FILTER_VALIDATE_FLOAT)) {
         $validQty = true;
      }
      // By default, verify the quantity is an integer
      elseif (filter_var($qty, FILTER_VALIDATE_INT))   {
         $validQty = true;
      }

      // If it's a valid quantity, remove or update as necessary
      if ($validQty === true) {
         if($qty < 1) {
            $this->remove_item($id);
         }
         else {
            $this->qtys[$id] = $qty;
         }
         $this->update_subtotal();
         return true;
      }
   }


   /* Using post vars to remove items doesn't work because we have to pass the
   id of the item to be removed as the value of the button. If using an input
   with type submit, all browsers display the item id, instead of allowing for
   user-friendly text. If using an input with type image, IE does not submit
   the   value, only x and y coordinates where button was clicked. Can't use a
   hidden input either since the cart form has to encompass all items to
   recalculate   subtotal when a quantity is changed, which means there are
   multiple remove   buttons and no way to associate them with the correct
   hidden input. */

   /**
   * Reamove an item from the cart
   *
   * @param string $id   *
   */
   private function remove_item($id) {
      $tmpItems = array();

      unset($this->names[$id]);
      unset($this->prices[$id]);
      unset($this->qtys[$id]);
      unset($this->urls[$id]);

      // Rebuild the items array, excluding the id we just removed
      foreach($this->items as $item) {
         if($item != $id) {
            $tmpItems[] = $item;
         }
      }
      $this->items = $tmpItems;
      $this->update_subtotal();
   }

   /**
   * Empty the cart
   */
   public function empty_cart() {
      $this->items     = array();
      $this->names     = array();
      $this->prices    = array();
      $this->qtys      = array();
      $this->urls      = array();
      $this->subtotal  = 0;
      $this->itemCount = 0;
   }

   /**
   * Update the entire cart
   */
   public function update_cart() {

      // Post value is an array of all item quantities in the cart
      // Treat array as a string for validation
      if (isset($_POST['jcartItemQty']) && is_array($_POST['jcartItemQty'])) {
         $qtys = implode($_POST['jcartItemQty']);
      }

      // If no item ids, the cart is empty
      if (isset($_POST['jcartItemId'])) {

         $validQtys = false;

         // If decimal quantities are enabled, verify the combined string only contain digits and decimal points
         if ($this->config['decimalQtys'] === true && preg_match("/^[0-9.]+$/i", $qtys)) {
            $validQtys = true;
         }
         // By default, verify the string only contains integers
         elseif (filter_var($qtys, FILTER_VALIDATE_INT) || $qtys == '') {
            $validQtys = true;
         }

         if ($validQtys === true) {

            // The item index
            $count = 0;

            // For each item in the cart, remove or update as necessary
            foreach ($_POST['jcartItemId'] as $id) {

               $qty = $_POST['jcartItemQty'][$count];

               if($qty < 1) {
                  $this->remove_item($id);
               }
               else {
                  $this->update_item($id, $qty);
               }

               // Increment index for the next item
               $count++;
            }
            return true;
         }
      }
      // If no items in the cart, return true to prevent unnecssary error message
      elseif (!isset($_POST['jcartItemId'])) {
         return true;
      }
   }

   /**
   * Recalculate subtotal
   */
   private function update_subtotal() {
      $this->itemCount = 0;
      $this->subtotal  = 0;

      if(sizeof($this->items > 0)) {
         foreach($this->items as $item) {
            $this->subtotal += ($this->qtys[$item] * $this->prices[$item]);

            // Total number of items
            $this->itemCount += $this->qtys[$item];
         }
      }
   }

   /**
   * Process and display cart
   */
   public function display_cart() {

      $config = $this->config;
      $errorMessage = null;

      // Simplify some config variables
      $checkout = $config['checkoutPath'];
      $priceFormat = $config['priceFormat'];

      $id    = $config['item']['id'];
      $name  = $config['item']['name'];
      $price = $config['item']['price'];
      $qty   = $config['item']['qty'];
      $url   = $config['item']['url'];
      $add   = $config['item']['add'];

      // Use config values as literal indices for incoming POST values
      // Values are the HTML name attributes set in config.json
      if(isset($_POST[$id])){
         $id    = $_POST[$id];
         $name  = $_POST[$name];
         $price = $_POST[$price];
         $qty   = $_POST[$qty];
         $url   = $_POST[$url];

         // Optional CSRF protection, see: http://conceptlogic.com/jcart/security.php
         $jcartToken = $_POST['jcartToken'];
      }

      // Only generate unique token once per session
      if(!$_SESSION['jcartToken']){
         $_SESSION['jcartToken'] = md5(session_id() . time() . $_SERVER['HTTP_USER_AGENT']);
      }
      // If enabled, check submitted token against session token for POST requests
      if ($config['csrfToken'] === 'true' && $_POST && $jcartToken != $_SESSION['jcartToken']) {
         $errorMessage = 'Invalid token!' . $jcartToken . ' / ' . $_SESSION['jcartToken'];
      }

      // Sanitize values for output in the browser
      $id    = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
      $name  = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
      $url   = filter_var($url, FILTER_SANITIZE_URL);

      // Round the quantity if necessary
      if($config['decimalPlaces'] === true) {
         $qty = round($qty, $config['decimalPlaces']);
      }

      // Add an item
      if (isset($_POST[$add])) {
         $itemAdded = $this->add_item($id, $name, $price, $qty, $url);
         // If not true the add item function returns the error type
         if ($itemAdded !== true) {
            $errorType = $itemAdded;
            switch($errorType) {
               case 'qty':
                  $errorMessage = $config['text']['quantityError'];
                  break;
               case 'price':
                  $errorMessage = $config['text']['priceError'];
                  break;
            }
         }
      }

      // Update a single item
      if (isset($_POST['jcartUpdate'])) {
         $itemUpdated = $this->update_item($_POST['itemId'], $_POST['itemQty']);
         if ($itemUpdated !== true)   {
            $errorMessage = $config['text']['quantityError'];
         }
      }

      // Update all items in the cart
      if(isset($_POST['jcartUpdateCart']) || isset($_POST['jcartCheckout']))   {
         $cartUpdated = $this->update_cart();
         if ($cartUpdated !== true)   {
            $errorMessage = $config['text']['quantityError'];
         }
      }

      // Remove an item
      /* After an item is removed, its id stays set in the query string,
      preventing the same item from being added back to the cart in
      subsequent POST requests.  As result, it's not enough to check for
      GET before deleting the item, must also check that this isn't a POST
      request. */
      if(isset($_GET['jcartRemove']) && !$_POST) {
         $this->remove_item($_GET['jcartRemove']);
      }

      // Empty the cart
      if(isset($_POST['jcartEmpty'])) {
         $this->empty_cart();
      }

      // Determine which text to use for the number of items in the cart
      $itemsText = $config['text']['multipleItems'];
      if ($this->itemCount == 1) {
         $itemsText = $config['text']['singleItem'];
      }

      // Determine if this is the checkout page
      /* First we check the request uri against the config checkout (set when
      the visitor first clicks checkout), then check for the hidden input
      sent with Ajax request (set when visitor has javascript enabled and
      updates an item quantity). */
      $isCheckout = strpos(request_uri(), $checkout);
      if ($isCheckout !== false || (isset($_REQUEST['jcartIsCheckout']) && $_REQUEST['jcartIsCheckout'] == 'true')) {
         $isCheckout = true;
      }
      else {
         $isCheckout = false;
      }

      // Overwrite the form action to post to gateway.php instead of posting back to checkout page
      if ($isCheckout === true) {

         // Sanititze config path
         $path = filter_var($config['jcartPath'], FILTER_SANITIZE_URL);

         // Trim trailing slash if necessary
         $path = rtrim($path, '/');

         $checkout = $path . '/gateway.php';
      }

      // Default input type
      // Overridden if using button images in config.php
      $inputType = 'submit';

      // If this error is true the visitor updated the cart from the checkout page using an invalid price format
      // Passed as a session var since the checkout page uses a header redirect
      // If passed via GET the query string stays set even after subsequent POST requests
      if ((isset($_SESSION['quantityError']) && $_SESSION['quantityError'] === true)) {
         $errorMessage = $config['text']['quantityError'];
         unset($_SESSION['quantityError']);
      }

      // Set currency symbol based on config currency code
      $currencyCode = trim(strtoupper($config['currencyCode']));
      switch($currencyCode) {
         case 'EUR':
            $currencySymbol = '&#128;';
            break;
         case 'GBP':
            $currencySymbol = '&#163;';
            break;
         case 'JPY':
            $currencySymbol = '&#165;';
            break;
         case 'CHF':
            $currencySymbol = 'CHF&nbsp;';
            break;
         case 'SEK':
         case 'DKK':
         case 'NOK':
            $currencySymbol = 'Kr&nbsp;';
            break;
         case 'PLN':
            $currencySymbol = 'z&#322;&nbsp;';
            break;
         case 'HUF':
            $currencySymbol = 'Ft&nbsp;';
            break;
         case 'CZK':
            $currencySymbol = 'K&#269;&nbsp;';
            break;
         case 'ILS':
            $currencySymbol = '&#8362;&nbsp;';
            break;
         case 'TWD':
            $currencySymbol = 'NT$';
            break;
         case 'THB':
            $currencySymbol = '&#3647;';
            break;
         case 'MYR':
            $currencySymbol = 'RM';
            break;
         case 'PHP':
            $currencySymbol = 'Php';
            break;
         case 'BRL':
            $currencySymbol = 'R$';
            break;
         case 'USD':
         default:
            $currencySymbol = '$';
            break;
      }

      ////////////////////////////////////////////////////////////////////////
      // Output the cart

      // Return specified number of tabs to improve readability of HTML output
      function tab($n) {
         $tabs = null;
         while ($n > 0) {
            $tabs .= "\t";
            --$n;
         }
         return $tabs;
      }

      $SUBTOTAL = number_format($this->subtotal, $priceFormat['decimals'], $priceFormat['dec_point'], $priceFormat['thousands_sep']);
      $TOTAL_TAX = number_format($SUBTOTAL * 0.06 + 5, $priceFormat['decimals'], $priceFormat['dec_point'], $priceFormat['thousands_sep']);
      $_SESSION['TOTAL_PRICE'] = number_format($SUBTOTAL + $TOTAL_TAX, $priceFormat['decimals'], $priceFormat['dec_point'], $priceFormat['thousands_sep']);$SUBTOTAL + $TOTAL_TAX;

      ?>
      <div id="jcart">
          <form method='post' action="checkout.php">
              <fieldset>
                  <input type="hidden" name="jcartToken" value="<?php echo $_SESSION['jcartToken']; ?>">
                  <div class="panel panel-default">
                      <div class="panel-body">
                          <div class="row">
                              <div class="col-xs-5 text-center">Product</div>
                              <div class="col-xs-1">Quantity</div>
                              <div class="col-xs-2 text-right">Price</div>
                              <div class="col-xs-2 text-right">Total</div>
                              <div class="col-xs-2"></div>
                          </div>
                          <?php foreach ($this->get_contents() as $item) { ?>
                          <div class="row">
                              <div class="col-xs-4 col-md-5">
                                  <div class="col-xs-12 col-md-3">
                                      <img class="thumbnail" src="<?php echo product_image($item['id']); ?>" alt="<?php echo $item['name']; ?>" width="75" height="75">
                                  </div>
                                  <div class="col-xs-12 col-md-9">
                                      <h4 class="media-heading"><a href="product.php?product=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h4>
                                      <span>Status: </span><span class="text-success"><strong>In Stock</strong></span>
                                  </div>
                              </div>
                              <div class="col-xs-2 col-md-1 jcart-item-qty">
                                  <input name="jcartItemId[]" type="hidden" value="<?php echo $item['id']; ?>">
                                  <input class="form-control" id="jcartItemQty-<?php echo $item['id']; ?>" name="jcartItemQty[]" size="2" type="text" value="<?php echo $item['qty']; ?>">
                              </div>
                              <div class="col-xs-2 text-right"><h4>$<?php echo $item['price']; ?></h4></div>
                              <div class="col-xs-2 text-right"><h4>$<?php //echo $product['multprice']; ?></h4></div>
                              <div class="col-xs-2 text-right">
                                  <a class="btn btn-danger jcart-remove" href="?jcartRemove=<?php echo $item['id']; ?>"><span class="glyphicon glyphicon-remove pull-left"></span><span class="hidden-xs hidden-sm pull-left"> Remove</span></a>
                              </div>
                          </div>
                          <?php } ?>
                          <div class="row">
                              <div class="col-xs-6 col-sm-7 col-md-8 text-right">
                                  <h4>Subtotal:</h4>
                                  <h4>Shipping + Tax:</h4>
                                  <h3>Total:</h3>
                              </div>
                              <div class="col-xs-4 col-sm-3 col-md-2 text-right">
                                  <h4>$<?php echo $SUBTOTAL; ?></h4>
                                  <h4>$<?php echo $TOTAL_TAX; ?></h4>
                                  <h3><b>$<?php echo $_SESSION['TOTAL_PRICE']; ?></b></h3>
                              </div>
                              <div class="col-xs-2 col-md-2">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-4 col-sm-6 text-right">
                          <a href="catalog.php" class="btn btn-default btn-lg" role="button"><span class="glyphicon glyphicon-th-list"></span> Back to Catalog </a>
                      </div>
                      <div class="col-xs-4 col-sm-3 text-right">
                          <input type="button" class="btn btn-primary btn-lg" role="button" id="jcart-paypal-checkout" name="jcartPaypalCheckout" value="<?php echo $config['text']['checkoutPaypal']; ?>">
                          <?php if (isset($_SESSION['cart_contents']) && !empty($_SESSION['cart_contents'])) { ?>
                          <a href="checkout.php" class="btn btn-success btn-lg" role="button"><span class="glyphicon glyphicon-ok"></span> Proceed to Checkout</a>
                          <?php } ?>
                      </div>
                      <div class="col-xs-4 col-sm-3 text-right">
                          <a href="checkout.php" class="btn btn-success btn-lg" role="button"><span class="glyphicon glyphicon-ok"></span> Checkout with Card</a>
                      </div>
                  </div>
                  <input type='hidden' id='jcart-is-checkout' name='jcartIsCheckout' value='true' />
              </fieldset>
          </form>
      </div>
      <?php
   }
}

// Start a new session in case it hasn't already been started on the including page
if (!isset($_SESSION)) { //new
 @session_start();
} //new

// Initialize jcart after session start
$jcart = $_SESSION['jcart'];
if(!is_object($jcart)) {
   $jcart = $_SESSION['jcart'] = new Jcart();
}

// Enable request_uri for non-Apache environments
// See: http://api.drupal.org/api/function/request_uri/7
if (!function_exists('request_uri')) {
   function request_uri() {
      if (isset($_SERVER['REQUEST_URI'])) {
         $uri = $_SERVER['REQUEST_URI'];
      }
      else {
         if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
         }
         elseif (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
         }
         else {
            $uri = $_SERVER['SCRIPT_NAME'];
         }
      }
      $uri = '/' . ltrim($uri, '/');
      return $uri;
   }
}
?>
