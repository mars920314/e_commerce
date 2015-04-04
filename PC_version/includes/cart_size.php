<?php
//cart_size.php
//-----------------------------------------------------------------
// This page is called via AJAX to return the total number of 
//	items in the shopping cart
//-----------------------------------------------------------------
//functions called
//  session_start() - registers the session variables
//  get_number_cart_items() - returns the number of cart items
//-----------------------------------------------------------------

session_start();
include_once('./functions.php');

print get_number_cart_items();

?>