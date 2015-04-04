<?php
//browse.php
//------------------------------------------------------------------------------------------
// This page allows the user to sort the items by a property --> artist, genre, etc
// If $_GET[list] is set, all of that column in db is displayed
//------------------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_browse_menu() - include the browse options
// include_footer() - close the page and include my footer text
// ucfirst(int str) - capitalize first letter of str
// get_all_product_infos(int start, int end, string type, string value) - return all 
//				products where the type has the given value. Unlimited length
// display_item_list(obj products) - display formatted table output for the products
// get_all_options(string type) - return all values in db for the given type
//------------------------------------------------------------------------------------------

require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');

include_header('Browse E-Commerce.com');
include_browse_menu();

if(isset($_REQUEST['type']) and isset($_REQUEST['value']))
{
	$type = $_REQUEST['type'];
	$value = $_REQUEST['value'];
	
	print '<h3>'.ucfirst($type).' - '.$value.'</h3>';
	
	//no limit on length of display list
	$products = get_all_product_infos(-1, -1, $type, $value);
	display_item_list($products);
	//disp_r($products);
}
elseif(isset($_REQUEST['list']))//used
{
	$type = $_REQUEST['list'];
	$list = get_all_options($type);
	//disp_r($list);
	
	print '<h3>'.ucfirst($type).'</h3>';
	
	foreach($list as $value)
	{
		print '<a href="'.$_SERVER['PHP_SELF'].'?type='.$type.'&value='.$value.'">'.$value.'</a><br />';
	}
}
else
{
	print '<h3>Page Accessed in Error.</h3>';
}


include_footer();

?>