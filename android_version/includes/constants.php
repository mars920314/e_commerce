<?php
//constants.php
//-----------------------------------------------------------------------
//This page contains the values for shipping and taxes used in php.
//If these are updated, the corresponding values should also be
//updated in clientSide.js
//
// float array shipping_prices
//		- the percent of the subtotal that each type of shipping costs
//
// float array taxes
//		- the percent of the subtotal charged for taxes
//-----------------------------------------------------------------------


global $shipping_prices, $taxes;

$shipping_prices = array();
$shipping_prices['ground'] = 0.10;
$shipping_prices['two_day'] = 0.15;
$shipping_prices['overnight'] = 0.25;

$taxes = array();
$taxes['in_state'] = 0.08;
$taxes['out_state'] = 0;


?>