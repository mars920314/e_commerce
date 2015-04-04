<?php
//update_view_cart.php
//-----------------------------------------------------------------
// This page is called via AJAX to update the shopping cart when
//	a quantity is adjusted from view_cart.php
//
// It checks to make sure the provided quantity is valid, then
//	updates the cart with the new value via
//		-->set_cart_quantity(product_id, new_quantity)
//
// Is also computes the new total price for that item and returns
//  that total to the javascript
//-----------------------------------------------------------------
//functions called
// get_product_info(product_id) - return an array of all the product info
//			associated with the given id
// set_cart_quantity(product_id, quantity) - update the quantity in the 
//			session cart of the product to the new quantity
//-----------------------------------------------------------------


session_start();
require_once('./mysql_connect.php');
include_once('./functions.php');
include_once('./mysql_functions.php');

$id = $_GET['id'];
$qty = $_GET['qty'];

if ($id.length>0 and $qty.length>0)
{
	if((int)$qty > 0){
		$product = get_product_info($id);
		$price = $product['price'];
		$total = (float)$price*(int)$qty;
		
		set_cart_quantity($id, $qty);
	}
	
}

$total = number_format($total, 2, '.', '');
print $total;

?>