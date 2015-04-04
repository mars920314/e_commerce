<?php
//place_order.php
//--------------------------------------------------------------------------------
// This page places the order by updating the database and displaying a receipt
//		for the user to print.
//--------------------------------------------------------------------------------
//functions: 
// place_order(data)
//		- coordinate placing the order
//
// display_receipt(data)
//		- display receipt to the screen for the user to print
//
// JAVASCRIPT PrintThisPage()
//		- opens a printer friendly version of the page and prompts the printer
//				dialog box to open as well
//		
//--------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_browse_menu() - include the browse options
// include_footer() - close the page and include my footer text
// create_xml_from(array) - return xml from array of shopping cart
// update_database(array data, string xml_of_items) - this is where the work of placing the order is donw
//			- see xml_functions.php for full details
// empty_cart() - set all cart quantities to 0
// getdate() - get today's date
// number_format(num, digits) - return the given number formatted to 'digits' decimal places
// get_product_info(int id) - return array of all the product info associated with the id
// calculate_item_total(float price, int quantity) - return the float price*quantity for the given
// session_unregister(SESSION var) - destroy session variables 
//--------------------------------------------------------------------------------

require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');
require_once('./includes/xml_functions.php');

//include header, and preserve data in the session[data] variable
//because it contains our user's information
include_header('e_store.com - Checkout', true);

if(isset($_POST['place_order'])){
	if(isset($_SESSION['data'])){
		//save the users info to $data
		$data = $_SESSION['data'];
		//now that we have saved the data, destroy the session variable
		unset($_SESSION['data']);
		
		display_receipt($data);
		place_order($data);
	}
}

include_footer();

?>


<?php

function place_order($data)
{
	//create an xml version of the shopping cart for the db
	$order = create_xml_from($_SESSION['cart']);
	//place the actual order in the database here
	update_database($data, $order);
	
	//empty the cart and display the cart as empty, now that the
	//order has been placed
	empty_cart();
	?>
	<script type="text/javascript">
		document.getElementById("cart_num_items").innerHTML = 0;
	</script>
	<?php
	
}

// show receipt page
function display_receipt($data)
{
	$date = getdate();
	?>

<table cellpadding="5" width="500px">
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td align="left">Thank you! Your order has been placed.<br />Please print this page for your records.</td>
					<td align="right"><a href="javascript:PrintThisPage()"><img border="0" src="./images/printer.png" /></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

	<div id="print_content">
	
<table cellpadding="5">
	<tr><td colspan="3" align="right"><?php print $date['month'].' '.$date['mday'].', '.$date['year']; ?></td></tr>
	<tr>
		<td>
			<table>
				<tr><td><b>To:</b></td></tr>
				<tr><td><?php print $data['first_name'].' '.$data['last_name']; ?></td></tr>
				<tr><td><?php print $data['address1']; ?></td></tr>
				<?php if($data['address2']!="") print '<tr><td>'.$data['address2'].'</td></tr>'; ?>	
				<tr><td><?php print $data['city'].', '.$data['state'].' '.$data['zip']; ?></td></tr>
				<tr><td><?php print $data['email']; ?></td></tr>
				<tr><td><?php print $data['phone']; ?></td></tr>
			</table>
		</td>
		<td width="40"></td>
		<td>
			<table>
				<tr><td><b>From:</b></td></tr>
				<tr><td>Last Chance Laptop Store</td></tr>
				<tr><td>125 Magnolia Ave.</td></tr>
				<tr><td>Auburn, AL 36849</td></tr>
				<tr><td>support@e_store.com</td></tr>
			</table>	
		</td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td colspan="3">
			<!--<table width="100%" border="1" frame="box" rules="cols" cellpadding="3">-->
				<table width="100%" cellpadding="7">
				<tr><td>Item</td><td>Quantity</td><td>Item Price</td><td>Item Total</td></tr>
				<tr><td colspan="4"><hr size="1" /></td></tr>
				<?php
				foreach($_SESSION['cart'] as $item_number => $quantity)
				{
					if($quantity > 0){
						$product = get_product_info($item_number);
						$item_total = calculate_item_total($product['price'], $quantity);
						$subtotal += $item_total;
						
						print '<tr><td>'.$product['brand'].' - '.$product['name'].'</td>
								<td>'.$quantity.'</td>
								<td>$'.number_format($product['price'], 2).'</td>
								<td>$'.number_format($item_total, 2).'</td></tr>';
					}
				}
				
				global $shipping_prices, $taxes;
				$shipping = $shipping_prices[$data['shipping']]*$subtotal;
				if($data['tax']=='in_state')
					$tax_rate = $taxes['in_state'];
				else
					$tax_rate = $taxes['out_state'];			
				$tax = $tax_rate*$subtotal;
				$total = $subtotal + $shipping + $tax;
				
				$subtotal = number_format($subtotal, 2);
				$shipping = number_format($shipping, 2);
				$tax = number_format($tax, 2);
				$total = number_format($total, 2);
				?>
				<tr><td colspan="4"><hr size="1" /></td></tr>
				<tr><td></td><td colspan="2">Subtotal:</td><td>$<?php print $subtotal;?></td></tr>
				<tr><td></td><td colspan="2">Shipping:</td><td>$<?php print $shipping;?></td></tr>
				<tr><td></td><td colspan="2">Tax:</td><td>$<?php print $tax;?></td></tr>
				<tr><td></td><td colspan="3"><hr size="1" /></td></tr>
				<tr><td></td><td colspan="2"><b>Total:</b></td><td>$<?php print $total;?></td></tr>
				
			</table>
		</td>
	</tr>	
</table>

</div>
	<?php
}

?>

<script language="javascript">
//********************************************************************
//resource for setting up how to print using javascript
//http://www.codeproject.com/KB/scripting/javascript_print_page.aspx
//********************************************************************
// print this page
function PrintThisPage()
{ 
  var disp_setting="toolbar=no,location=no,directories=no,menubar=no,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Purchase Receipt</title>'); 
   docprint.document.write('</head><body onLoad="self.print()"><center>');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
