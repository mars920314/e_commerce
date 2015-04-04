<?php
// functions.php
// -------------------------------------------------------------------------
// This page provides several functions which fit into five categories
//		1) header/footer functions
//		2) display functions
//		3) shopping cart functions
//		4) dropdown menu functions
//		5) debug functions
// These functions are included in all pages, and are called as necessary
// -------------------------------------------------------------------------
// Functions:
//
//HEADER/FOOTER FUNCTIONS
// include_header($page_title, $keep_data_in_session=false)
//		- creates the header for each page
//		- page title is passed in as a variable
//		- $keep_data_in_session determines whether or not $_SESSION['data'] is kept
//			or destroyed when the page is loaded. Most of the pages want to destory it.
//			The feature is added so that order confirmation pages can be refreshed without
//			destroying the data. It is destroyed on the rest so that a user could not reaccess
//			an order page when it was not intended.
//		- this function also adds the shopping cart icon to the header
// 
// include_footer()
//		- includes the footer for every page
//		- the footer includes a link to the admin page
//
//function display_customer()
//		- includes the login header
//		- login header link to page for login or create new account
//		- detect whether user has cookies saved in browser, so that user can avoid login
// 
// include_browse_menu()
//		- includes the browse menu in a page
//		- this menu includes options to browse by Artist and Genre and provides a search bar
// 
//
//DISPLAY FUNCTIONS 
// display_item_list($products)
//		- takes input of an array of products. Each product in the array is an array of all
//			of the data associated with that particular item
//		- this function prints the table which displays the provided products
//
// get_availability_text($num)
//		- $num is the quantity available for an item
//		- $cutoff is the limit for when the item becomes "Low Availability"
//		- $num higher than the cutoff returns "In Stock"
//		- if quantity is 0, return is "Out of Stock"
//
// display_featured_item($product)
//		- when an item is displayed on it own page, this function is called to print the item's display
//		- $product is an array of all the data relevant to the product from the database
//
// add_remove_for($product)
//		- function returns the HTML for the status div with each product listed by display_item_list
//		- uses <div> with id=status{id#} where id# is the item_number of the product associated with this <div>
//
//
//SHOPPING CART FUNCTIONS
// add_to_cart($product_id)
//		- sets shopping cart in current session to include the given product id
//		- if already in cart, increments the number of copies
// 
// remove_from_cart($product_id)
//		- sets the quantity to 0 for the given product in the shopping cart
// 
// empty_cart()
//		- sets all cart quantities to 0
//
// set_cart_quantity($product_id, $quantity)
//		- set the quantity of a given product to the specified qantity
// 
// get_number_cart_items()
//		- return the total number of items in the cart
// 
// item_in_cart($product_id)
//		- return a boolean of whether the given item is in the cart or not
// 
// get_cart_quantity_for($product_id)
//		- return the cart quantity for the given product
// 
// calculate_item_total($price, $quantity)
//		- return the total price for a given price and quantity
//		- multiply price * quantity and return float
//
//
//DROPDOWN MENU FUNCTIONS
// get_shipping_dropdown($type="")
//		- function returns a dropdown menu for a shipping selection with preselect option
//		- onchange event calls javascript function updateShipping() from clientSide.js
//
// getMonthDropDown($m='')
//		- returns a simple 12 digit month selection dropdown menu with preselect option
//		- onchange event calls javascript function verifyMonth() from creditCard.js
//
// getYearDropDown($y='')
//		- returns a year selection dropdown menu with preselect option
//		- $length variable sets the number of years in the list
//		- first year displayed is the current year and displays the next $length-1 years
//
// getStateDropDown($s='', $enabled=true)
//		- returns a state selection dropdown menu with a preselect option
//		- $enabled adds the option to print the menu, but disabled. This is not used for this website
//		- Alabama is not on the state list. This is because of the in-state/out-of-state tax requirements.
//			The user chooses his tax status on the view_cart.php page. If he selects Alabama, his state is
//			set and cannot be changed on the billing page. If he chooses out of state, he can choose to
//			bill and ship his purchase to any state other than Alabama.
// 
//
//DEBUG FUNCTIONS
// disp_r($text)
//		- this function is essentially the same as PHPs print_r.
//		- it adds the <pre> tags so that data send to this function is
//			printed to the screen in a formatted display
//*****************************************************************************

//----------------------------header/footer-----------------------------------
include('xml_functions.php');
error_reporting(E_ERROR);
function include_header($page_title, $keep_data_in_session=false)//used
{
	session_start();
//	session_register(cart);
	if(isset($_SESSION['cart'])==null);
//	{$_SESSION['cart'] = null;}
	
	if(!$keep_data_in_session){
//		session_unregister(data);
		$_SESSION['data'] = null;
	}
	
	require_once('./includes/constants.php');
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	    <title><?php print $page_title; ?></title>
		<script type="text/javascript" src="./includes/clientSide.js"></script> 
		<script type="text/javascript" src="./includes/checkout.js"></script> 
		<script type="text/javascript" src="./includes/creditCard.js"></script> 
	</head>

	<body onload="calculateInitialTotals()">

	<table cellspacing="5" width="100%">
	<tr>
		<td align="left">
			<div id="banner">
			<a href="./index.php">
				<!--Welcome to my<br />E-Commerce Site-->
				<h1>e_Store</h1>
			</a>
			</div>
		</td>
		
		<td><?php print display_customer() ?></td>
		
		<td align="right">
			<a href="./view_cart.php">
			<div id="cart">
				<image border="0" src="./images/cart2.jpg"></image><span id="cart_num_items"><?php print get_number_cart_items().'</span> items'; ?>
			</div>
			</a>
		</td>
	</tr>
	</table>
	

<?php
}
// include the footer
function include_footer()
{
?>
<hr size="1" />
	<table width="500"><tr><td>
	<table align="center" cellpadding="10"><tr><td><small><b>e_Store contains all you want.</b></small></td></tr></table>
	</td></tr></table>
	</body>
	</html>
<?php
}

//include the login header
function display_customer()
{
	if (isset($_COOKIE['lastname']))
	{
		$last_name=$_COOKIE["lastname"];
		$sql1="SELECT * FROM customer_login WHERE last_name=\"$last_name\"";
		$rs1=mysql_query($sql1);
		$num1= mysql_num_rows($rs1);
		// Fectch the row of the table
		$row1 = mysql_fetch_row($rs1);
		//if we have not found the user, we allow the user to login
		if($num1>0){
			$visit_times=$row1[2];
			$cust_session_id=$row1[1];
			$PHPSESSID=session_id();
			if($cust_session_id != $PHPSESSID)
			{
				$visit_times=$visit_times+1;
				$sql2="UPDATE customer_login SET cust_session_id=\"$PHPSESSID\", counter=\"$visit_times\" WHERE last_name=\"$last_name\"";
				$rs2=mysql_query($sql2);
			}
			setcookie("usercounter","$visit_times",time()+60*60);
			print 'Welcome back! '.$_COOKIE['lastname'].'<br>';
			print '<p>this is your '.$_COOKIE['usercounter'].' time visit e_store</p>';
			print '<a href="./logout.php">Log Out</a> | <a href="./history.php">View History</a>';
		}
		else
		{
			print 'Hi! My Friend! <a href="./login.php">Log in</a> Please!';
		}
	}
	else
	{
		print 'Hi! My Friend! <a href="./login.php">Log in</a> Please!';
	}
}

// include the browse menu
function include_browse_menu()//used
{
	?>
	<table cellpadding="5" width="100%">
		<tr>
			<td>Browse by:</td>
			<td><a href="./browse.php?list=brand">Brand</a></td>
			<td><a href="./browse.php?list=series">Series</a></td>
			<td><a href="./browse.php?list=OS">Operating System</a></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="right">
				<form action="./search.php" method="get">
					<select name="field">
						<option value="name">Name</option>
						<option value="OS">Operating System</option>
						<option value="processor">Processor</option>
						<option value="graphics">Graphics</option>
					</select>
					<input type="text" size="15" name="search_text" />
					<input type="submit" value="Search" />
				</form>
			</td>
		</tr>
	</table>
	<?php
}

//------------------------dipslay functions----------------------------------------

// display the item list
function display_item_list($products)
{
	//*****************************************************************
	//change image border color [6]
	//http://www.boutell.com/newfaq/creating/imagebordercolor.html
	//*****************************************************************
	
	print '<table cellpadding="5" width="100%">
			<tr><td><b>Computer</b></td><td></td><td><b>Availability</b></td><td><b>Price</b></td></tr>';

	foreach($products as $id => $product)
	{
		print '
		<tr>
				<td><a href="./item.php?item='.$id.'"><img border="1" style="border-color: black;" width="80" src="./display_image.php?item_id='.$id.'" /></a></td>
				<td>
					<p><a href="./browse.php?type=brand&value='.$product['brand'].'">'.$product['brand'].'</a><br>
					<a href="./browse.php?type=name&value='.$product['name'].'">'.$product['name'].'</a></p>
					<p><a href="./browse.php?type=OS&value='.$product['OS'].'">'.$product['OS'].'</a></p>
				</td>
				<td>'.get_availability_text($product['quantity']).'</td>';
				
		print   add_remove_for($product);
		print '</tr>';
	}

	print '</table>';
}

function get_availability_text($num)//used
{
	$cutoff = 5;
	if($num > $cutoff){
		return "In Stock";
	}elseif($num > 0){
		return "only '.$num.' left";
	}else{
		return "Out of Stock";
	}
}
// display the featured item
function display_featured_item($product)
{
	$id = $product['item_id'];
	print '<table cellpadding="15" width="100%">
			<tr>
				<td><img width="128" src="./display_image.php?item_id='.$id.'" /></td>
				<td>
					<p><a href="./browse.php?type=brand&value='.$product['brand'].'">'.$product['brand'].'</a><br />
					<b>'.$product['name'].'</b></p>
				</td>';
	print '<td>'.get_availability_text($product['quantity']).'</td>';
	print   add_remove_for($product);
	print 	'</tr>
			<tr>
				<td></td>
				<td>
				Processor: '.$product['processor'].'<br>
				Memory: '.$product['memory'].'<br>
				Storage: '.$product['storage'].'<br>
				Graphics: '.$product['graphics'].'<br>
				Operating System: '.$product['OS'].'</a>
				</td></tr>
			</table>';
}
// add or remove
function add_remove_for($product)//used
{
	$id = $product['item_id'];
	$return = '<td>$'.number_format($product['price'],2).'</td><td>';
	if(item_in_cart($id))
	{
		$return .= '<div id="status'.$id.'">';
		$return .= get_cart_quantity_for($id).' item(s) in <a href="./view_cart.php">Shopping Cart</a><br />';
		$return .= '<form name="form'.$id.'" method="post" action="'.$_SERVER['PHP_SELF'].'" onsubmit="return removeFromCart('.$id.')"><input type="hidden" name="num_available" id="num_available" value="'.$product['quantity'].'" /><input type="submit" value="Remove from Cart" /></form>';
		$return .= '</div>';
	}
	elseif($product['quantity']==0)
	{
		$return .= '<div id="status'.$id.'"></div>';
	}
	else
	{
		$return .= '<div id="status'.$id.'">';
		$return .= '<form name="form'.$id.'" method="post" action="'.$_SERVER['PHP_SELF'].'" onsubmit="return addToCart('.$id.', '.$product['quantity'].')">		
				<input type="text" id="qty'.$id.'" size="4" value="1" />				
				<input type="submit" value="Add to Cart" /></form>';
		$return .= '</div>';
	}
	$return .= '</td>';
	
	return $return;
}


//-------------------------shopping cart functions---------------------------
// add product to cart
function add_to_cart($product_id)
{
	if(isset($_SESSION['cart'][$product_id]))
		$_SESSION['cart'][$product_id]++;
	else
		$_SESSION['cart'][$product_id] = 1;
}
// remove product from cart
function remove_from_cart($product_id)
{
	if(isset($_SESSION['cart'][$product_id]))
		$_SESSION['cart'][$product_id] = 0;
}
// empty entire cart
function empty_cart()
{
	foreach($_SESSION['cart'] as $item_number => $quantity)
	{
		remove_from_cart($item_number);
	}
}
//set cart quantity
function set_cart_quantity($product_id, $quantity)
{
	$_SESSION['cart'][$product_id] = $quantity;
}
// get number of items in cart
function get_number_cart_items()
{
	$items = 0;
	if($_SESSION['cart'] != null)
	{
		foreach($_SESSION['cart'] as $product)
		{
			$items += $product;
		}
	}	
	return $items;
}
// id of item in cart
function item_in_cart($product_id)//used
{
	if(isset($_SESSION['cart'][$product_id]) and $_SESSION['cart'][$product_id] != 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
// get cart quantity for product
function get_cart_quantity_for($product_id)
{
	if(isset($_SESSION['cart'][$product_id]) and $_SESSION['cart'][$product_id] != 0)
	{
		return $_SESSION['cart'][$product_id];
	}
	else{
		return 0;
	}
}
// calculate total price
function calculate_item_total($price, $quantity)
{
	return $price*$quantity;
}
// save cart to cookie
function cart_to_cookie()
{
	$cart_string = create_xml_from($_SESSION['cart']);
	setcookie("cart",$cart_string,time()+60*60*60);	
}

//-----------------------drop down menus--------------------------------------
// shipping services menu
function get_shipping_dropdown($type="")
{
	$return = '<select name="shipping" id="shipping_type" onchange="updateTotals()" >
			<option value="ground" '; if($type=='ground') $return .= 'selected="selected" '; $return .= '>Ground</option>
			<option value="two_day" '; if($type=='two_day') $return .= 'selected="selected" '; $return .= '>2 Days</option>
			<option value="overnight" '; if($type=='overnight') $return .= 'selected="selected" '; $return .= '>Overnight</option>
				</select>';
	return $return;
}
// Month drop down
function getMonthDropDown($m='')
{
	$return = '<select name="month" id="month" onchange="verifyMonth()" >
			<option value="01" '; if($m=='01') $return .= 'selected="selected"'; $return .= '>01</option>
			<option value="02" '; if($m=='02') $return .= 'selected="selected"'; $return .= '>02</option>
			<option value="03" '; if($m=='03') $return .= 'selected="selected"'; $return .= '>03</option>
			<option value="04" '; if($m=='04') $return .= 'selected="selected"'; $return .= '>04</option>
			<option value="05" '; if($m=='05') $return .= 'selected="selected"'; $return .= '>05</option>
			<option value="06" '; if($m=='06') $return .= 'selected="selected"'; $return .= '>06</option>
			<option value="07" '; if($m=='07') $return .= 'selected="selected"'; $return .= '>07</option>
			<option value="08" '; if($m=='08') $return .= 'selected="selected"'; $return .= '>08</option>
			<option value="09" '; if($m=='09') $return .= 'selected="selected"'; $return .= '>09</option>
			<option value="10" '; if($m=='10') $return .= 'selected="selected"'; $return .= '>10</option>
			<option value="11" '; if($m=='11') $return .= 'selected="selected"'; $return .= '>11</option>
			<option value="12" '; if($m=='12') $return .= 'selected="selected"'; $return .= '>12</option>
			</select>';
	return $return;
}
// year drop down
function getYearDropDown($y='')
{
	$length = 10;
	$this_year = date('Y');
	
	$return = '<select name="year" id="year" onchange="verifyExpiration()" >';
	for($year=$this_year; $year<$this_year+$length; $year++){
		$return .= '<option value="'.$year.'" '; if($y==$year) $return .= 'selected="selected"'; $return .= '>'.$year.'</option>';
	}
	$return .= '</select>';
	return $return;
}
// state drop down
function getStateDropDown($s='', $enabled=true)
{
	//excludes Alabama, because of state tax requirement
	
	/*<option value="" '; if($s=='') $return .= 'selected="selected"'; $return .= '>N/A</option>
	<option value="AL" '; if($s=='AL') $return .= 'selected="selected"'; $return .= '>Alabama</option>*/
			
	$return = '<select name="state" id="state" onchange=\'stateValidator()\' size="1" '; if(!$enabled) $return .= 'disabled="disabled"'; $return.= '>
			<option value="AK" '; if($s=='AK') $return .= 'selected="selected"'; $return .= '>Alaska</option>
			<option value="AZ" '; if($s=='AZ') $return .= 'selected="selected"'; $return .= '>Arizona</option>
			<option value="AR" '; if($s=='AR') $return .= 'selected="selected"'; $return .= '>Arkansas</option>
			<option value="CA" '; if($s=='CA') $return .= 'selected="selected"'; $return .= '>California</option>
			<option value="CO" '; if($s=='CO') $return .= 'selected="selected"'; $return .= '>Colorado</option>
			<option value="CT" '; if($s=='CT') $return .= 'selected="selected"'; $return .= '>Connecticut</option>
			<option value="DE" '; if($s=='DE') $return .= 'selected="selected"'; $return .= '>Delaware</option>
			<option value="DC" '; if($s=='DC') $return .= 'selected="selected"'; $return .= '>Dist of Columbia</option>
			<option value="FL" '; if($s=='FL') $return .= 'selected="selected"'; $return .= '>Florida</option>
			<option value="GA" '; if($s=='GA') $return .= 'selected="selected"'; $return .= '>Georgia</option>
			<option value="HI" '; if($s=='HI') $return .= 'selected="selected"'; $return .= '>Hawaii</option>
			<option value="ID" '; if($s=='ID') $return .= 'selected="selected"'; $return .= '>Idaho</option>
			<option value="IL" '; if($s=='IL') $return .= 'selected="selected"'; $return .= '>Illinois</option>
			<option value="IN" '; if($s=='IN') $return .= 'selected="selected"'; $return .= '>Indiana</option>
			<option value="IA" '; if($s=='IA') $return .= 'selected="selected"'; $return .= '>Iowa</option>
			<option value="KS" '; if($s=='KS') $return .= 'selected="selected"'; $return .= '>Kansas</option>
			<option value="KY" '; if($s=='KY') $return .= 'selected="selected"'; $return .= '>Kentucky</option>
			<option value="LA" '; if($s=='LA') $return .= 'selected="selected"'; $return .= '>Louisiana</option>
			<option value="ME" '; if($s=='ME') $return .= 'selected="selected"'; $return .= '>Maine</option>
			<option value="MD" '; if($s=='MD') $return .= 'selected="selected"'; $return .= '>Maryland</option>
			<option value="MA" '; if($s=='MA') $return .= 'selected="selected"'; $return .= '>Massachusetts</option>
			<option value="MI" '; if($s=='MI') $return .= 'selected="selected"'; $return .= '>Michigan</option>
			<option value="MN" '; if($s=='MN') $return .= 'selected="selected"'; $return .= '>Minnesota</option>
			<option value="MS" '; if($s=='MS') $return .= 'selected="selected"'; $return .= '>Mississippi</option>
			<option value="MO" '; if($s=='MO') $return .= 'selected="selected"'; $return .= '>Missouri</option>
			<option value="MT" '; if($s=='MT') $return .= 'selected="selected"'; $return .= '>Montana</option>
			<option value="NE" '; if($s=='NE') $return .= 'selected="selected"'; $return .= '>Nebraska</option>
			<option value="NV" '; if($s=='NV') $return .= 'selected="selected"'; $return .= '>Nevada</option>
			<option value="NH" '; if($s=='NH') $return .= 'selected="selected"'; $return .= '>New Hampshire</option>
			<option value="NJ" '; if($s=='NJ') $return .= 'selected="selected"'; $return .= '>New Jersey</option>
			<option value="NM" '; if($s=='NM') $return .= 'selected="selected"'; $return .= '>New Mexico</option>
			<option value="NY" '; if($s=='NY') $return .= 'selected="selected"'; $return .= '>New York</option>
			<option value="NC" '; if($s=='NC') $return .= 'selected="selected"'; $return .= '>North Carolina</option>
			<option value="ND" '; if($s=='ND') $return .= 'selected="selected"'; $return .= '>North Dakota</option>
			<option value="OH" '; if($s=='OH') $return .= 'selected="selected"'; $return .= '>Ohio</option>
			<option value="OK" '; if($s=='OK') $return .= 'selected="selected"'; $return .= '>Oklahoma</option>
			<option value="OR" '; if($s=='OR') $return .= 'selected="selected"'; $return .= '>Oregon</option>
			<option value="PA" '; if($s=='PA') $return .= 'selected="selected"'; $return .= '>Pennsylvania</option>
			<option value="RI" '; if($s=='RI') $return .= 'selected="selected"'; $return .= '>Rhode Island</option>
			<option value="SC" '; if($s=='SC') $return .= 'selected="selected"'; $return .= '>South Carolina</option>
			<option value="SD" '; if($s=='SD') $return .= 'selected="selected"'; $return .= '>South Dakota</option>
			<option value="TN" '; if($s=='IN') $return .= 'selected="selected"'; $return .= '>Tennessee</option>
			<option value="TX" '; if($s=='TX') $return .= 'selected="selected"'; $return .= '>Texas</option>
			<option value="UT" '; if($s=='UT') $return .= 'selected="selected"'; $return .= '>Utah</option>
			<option value="VT" '; if($s=='VT') $return .= 'selected="selected"'; $return .= '>Vermont</option>
			<option value="VA" '; if($s=='VA') $return .= 'selected="selected"'; $return .= '>Virginia</option>
			<option value="WA" '; if($s=='WA') $return .= 'selected="selected"'; $return .= '>Washington</option>
			<option value="WV" '; if($s=='WV') $return .= 'selected="selected"'; $return .= '>West Virginia</option>
			<option value="WI" '; if($s=='WI') $return .= 'selected="selected"'; $return .= '>Wisconsin</option>
			<option value="WY" '; if($s=='WY') $return .= 'selected="selected"'; $return .= '>Wyoming</option>
			</select>';	
	return $return;
}

//------------------------debug functions----------------------------------------
function disp_r($text)
{
	print '<pre>';
	print_r($text);
	print '</pre>';
}

?>