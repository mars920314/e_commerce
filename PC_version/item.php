<?php
//admin.php
//--------------------------------------------------------------------------------
// This page is designed to highlight a particular product selected by the user
//--------------------------------------------------------------------------------
//functions called: 
// include_header(title)
//		- includes the header for the page with the given title
// 
// include_browse_menu()
//		- includes the browse menu for sorting and searching
//
// get_product_info(product_id)
//		- returns all of the database info associated with that product_id
//
// display_featured_item(product)
//		- prints to the screen a table which displays the data for the product
//
// include_footer()
//		- adds tag to the bottom and closes the page
//--------------------------------------------------------------------------------

require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');

include_header('E-Commerce.com');
include_browse_menu();

if(isset($_REQUEST['item']))
{
	$id = $_REQUEST['item'];
	$product = get_product_info($id);
	
	display_featured_item($product);
}
else
{
	print '<h3>Page Accessed in Error.</h3>';
}

include_footer();

?>