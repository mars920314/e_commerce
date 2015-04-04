<?php
//add_to_cart.php
//-----------------------------------------------------------------
// This page is called via AJAX to update the shopping cart when
//	a new item is added.
//
// It checks to make sure the provided quantity is valid, then
//	updates the cart with the new value
//
// Also prints (returns) the total number of items now in the 
//	cart to be updated in the pages header shopping cart
//-----------------------------------------------------------------
//functions called
//  get_number_cart_items() - returns the number of items in the cart
//-----------------------------------------------------------------

session_start();
include_once('./functions.php');

$id = $_GET['id'];
$qty = $_GET['qty'];

if ($id.length>0 and $qty.length>0)
{
	if(isset($_SESSION['cart'][$id]))
		$_SESSION['cart'][$id] += $qty;
	else
		$_SESSION['cart'][$id] = $qty;
}

cart_to_cookie();

print get_number_cart_items();

?>