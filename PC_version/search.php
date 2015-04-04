<?php
//search.php
//------------------------------------------------------------------------------------------
// This page allows the user to search the cd_store for a 'search_text' as a value of a 'field'
// If found, the cd will be displayed, if not, a message that none were found
//------------------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_browse_menu() - include the browse options
// include_footer() - close the page and include my footer text
// escape_data(str) - ensure that str is mysql compatible
// get_all_product_infos(start, end, type, value) - return all products where the type has the
//				given value. Unlimited length
// count(array) - return number of items in the array
// display_item_list(obj products) - display formatted table output for the products
// get_all_options(string type) - return all values in db for the given type
//------------------------------------------------------------------------------------------

require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');

include_header('Search E-Commerce.com');
include_browse_menu();

if (isset($_GET['search_text']))
{
	$type = $_GET['field'];
	$value = escape_data($_GET['search_text']);
	$products = get_all_product_infos(-1,-1,$type, $value);
	if(count($products) > 0)
	{
		display_item_list($products);
	}
	else{
		print '<p>Search returned no results. Please try again</p>';
	}
	
}
else
{
	print '<h3>Page Accessed in Error.</h3>';
}


include_footer();

?>