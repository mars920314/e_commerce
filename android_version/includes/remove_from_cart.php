<?php
//remove_from_cart.php
//-----------------------------------------------------------------
// This page is called via AJAX to update the shopping cart when
//	an item is removed.
//
// It checks to make sure an id is provided, then removes it
//	from the the cart.
//
// Also prints (returns) the total number of items now in the 
//	cart to be updated in the pages header shopping cart
//-----------------------------------------------------------------
//functions called
// get_number_cart_items() - return the total number of items in the cart
//-----------------------------------------------------------------

session_start();
include_once('./functions.php');

$id=$_GET["id"];

if ($id.length>0)
{
	if(isset($_SESSION['cart'][$id]))
		$_SESSION['cart'][$id] = 0;
}

print get_number_cart_items();

?>