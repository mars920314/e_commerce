<?php
//calculate_total.php
//-----------------------------------------------------------------
// This page is called via AJAX to calculate the total price when
//	a quantity, shipping, or tax is adjusted from view_cart.php
//
// It computes the total by adding the subtotal, shipping, and tax
// and returns that total to the javascript
//-----------------------------------------------------------------
//functions called
// get_product_info(product_id) - return an array of all the product info
//			associated with the given id
//-----------------------------------------------------------------


session_start();
require_once('./mysql_connect.php');
include_once('./functions.php');
include_once('./mysql_functions.php');
include_once('./constants.php');

$shipping_method = $_GET['shipping'];
$tax_rate = $_GET['tax'];

global $shipping_prices, $taxes;
$subtotal = 0;

foreach($_SESSION['cart'] as $item_number => $quantity)
{
	$product = get_product_info($item_number);
	$price = $product['price'];
	$item_total = (int)$quantity * (float)$price;
	
	$subtotal = $subtotal + $item_total;
}

$shipping = $shipping_prices[$shipping_method] * $subtotal;
$tax = $taxes[$tax_rate] * $subtotal;
$total = (float)$subtotal + (float)$shipping + (float)$tax;

$subtotal = number_format($subtotal, 2, '.', '');
$shipping = number_format($shipping, 2, '.', '');
$tax = number_format($tax, 2, '.', '');
$total = number_format($total, 2, '.', '');

print "$subtotal?$shipping?$tax?$total";

?>