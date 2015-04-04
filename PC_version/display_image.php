<?php
//display_image.php
//--------------------------------------------------------------------------------
// This page returns a jpeg to the browser from the databse
//--------------------------------------------------------------------------------
// page modified from ELEC 5220 Lab Manual
//--------------------------------------------------------------------------------
//functions called
// header(info) - change the page type header to that specified
//--------------------------------------------------------------------------------
error_reporting(E_ERROR);

require_once('./includes/mysql_connect.php');

// Now let's do our mySQL query to lookup the information, but only
//   if an item_id was specified in the address
if(isset($_GET['item_id']))
{
	$query = " SELECT image FROM items_laptop WHERE item_id='{$_GET['item_id']}'";
}

// Actually run the query
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);

//image type and to screen
header('Content-type: image/jpeg');
echo "$row[0]";

?>