<?php
//checkout.php
//--------------------------------------------------------------------------------
// This page is used for the user to input his billing information and then to
//  verify it. HELLO WORLD
//--------------------------------------------------------------------------------
//functions: 
// display_billing_form(data)    data = bool or array
//		- display the form for the user to input the data
//		- if 'data' is an array, the form is being reached with the intent of editing
//			the data that was previously entered and received from the session
//		- if 'data' is false, the form should be dipsplayed empty with and nothing from session
//
// display_data_confirm()
//		- display the table which displays all personal info entered by the user
//		- from here can go back to edit the info or forward to place the order
//
// hide_card_number(int num)
//		- reutrn a string representation with same length of num, but with only
//			the last four digits displayed as digits. The rest are 'x's
//
// getCardTypeDropDown(string cardtype)
//		- return a dropdown menu of credit card types, with cardtype preselected
//--------------------------------------------------------------------------------
//functions called:
// include_header(title) - include the page header and shopping cart
// include_browse_menu() - include the browse options
// include_footer() - close the page and include my footer text
// firstNameValidator() - checks that the first name meets all restrictions
// lastNameValidator() - checks that the last name meets all restrictions
// address1Validator() - checks that the address meets all restrictions
// cityNameValidator() - checks that the city name meets all restrictions
// zipNumberValidator() - checks that the zip code meets all restrictions
// phoneNumberValidator() - checks that the phone number meets all restrictions
// emailValidator() - checks that the email address meets all restrictions
// getStateDropDown(str state) - return state dropdown menu with state selected
// getMonthDropDown(int month) - return month dropdown menu with month selected
// getYearDropDown(int year) - return year dropdown menu with year selected
// strlen(str) - return length of str in number of characters
// substr(str, length) - return a substring of str with given length
// verifyCard() - ensure that card number is valid and update form accordingly
//--------------------------------------------------------------------------------

require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');

print "this file is being reloaded";
//so data is kept in session
include_header('e_store.com - Checkout', true);

if(isset($_POST['submit']))
{	
	display_billing_form($_SESSION['data']);
}
elseif(isset($_POST['submit_billing']))
{
	display_data_confirm();
}

include_footer();

?>


<?php
// display billing form
function display_billing_form($data=false)
{
	if(!$data){
		$data['shipping'] = $_POST['shipping'];
		$data['total_price'] = $_POST['total_price'];
		$data['tax'] = $_POST['tax'];
	}
	print $data['first_name'];
	print "<p>*Please note, we only ship to your credit card's billing address.</p>";
	?>
	<!--create the form and the table to collect the data -->
	<form action="" method="post" onsubmit="return verifyForm()">
	
	<table>
	<tr><td colspan="2">Please enter your billing/shipping information.</td></tr>
	<tr><td colspan="2">Your last name is bounded with order, Please entry the last name you used when you login</td></tr>
	<!--each field calls a JavaScript function when the onblur event is activated-->
	<tr><td width="150"><b><font size="6">First name:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="first_name" id="first" onblur='firstNameValidator()' value="<?php print $data['first_name']; ?>" /></td><td><span id="first_result"></span></td></tr>
	<tr><td><b><font size="6">Last name:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="last_name" id="last" onblur='lastNameValidator()' value="<?php print $data['last_name']; ?>" /></td><td><span id="last_result"></span></td></tr>
	<tr><td><b><font size="6">Address 1:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="address1" id="address1" onblur='address1Validator()' value="<?php print $data['address1']; ?>" /></td><td><span id="address1_result"></span></td></tr>
	<tr><td><b><font size="6">Address 2:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="address2" id="address2" value="<?php print $data['address2']; ?>" /></td><td><span id="address2_result"></span></td></tr>
	<tr><td><b><font size="6">City:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="city" id="city" onblur='cityNameValidator()' value="<?php print $data['city']; ?>" /></td><td><span id="city_result"></span></td></tr>
	<tr><td><b><font size="6">State:</b></font></td><td align="left">
	<?php 
	if($data['tax']=='in_state'){
		//print getStateDropDown($data['tax'], false);
		print 'Alabama <input type="hidden" name="state" id="state" value="AL" />';
	}else{
		print getStateDropDown($data['state']);
	}
			 ?>
		</td><td><span id="state_result"></span></td></tr>
	<tr><td><b><font size="6">Zip:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="zip" id="zip" onblur='zipNumberValidator()' value="<?php print $data['zip']; ?>" /></td><td><span id="zip_result"></span></td></tr>
	<tr><td><b><font size="6">Phone number:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="phone" id="phone" onblur='phoneNumberValidator()' value="<?php print $data['phone']; ?>" /></td><td><span id="phone_result"></span></td></tr>
	<tr><td><b><font size="6">Email address:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="email" id="email" onblur='emailValidator()' value="<?php print $data['email']; ?>" /></td><td><span id="email_result"></span></td></tr>
	
	<tr><td colspan="3"><hr /></td></tr>
	<tr><td colspan="3">Please enter your credit card information.</td></tr>
	<!--<tr><td><b><font size="6">Exp. Month:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="month" id="month" size="2" maxlength="2" onblur="verifyMonth()" value="<?php print $data['month']; ?>" /></td><td><span id="month_result"></td></tr>-->
	<tr><td><b><font size="6">Exp. Month:</b></font></td><td align="left"><font size="6"><?php print getMonthDropDown($data['month']); ?></font></td><td><span id="month_result"></td></tr>
	<!--<tr><td><b><font size="6">Exp. Year:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="year" id="year" size ="4" maxlength="4" onblur="verifyExpiration()" value="<?php print $data['year']; ?>" /></td><td><span id="date_result"></span></td></tr>-->
	<tr><td><b><font size="6">Exp. Year:</b></font></td><td align="left"><font size="6"><?php print getYearDropDown($data['year']); ?></font></td><td><span id="date_result"></span></td></tr>
	<tr><td><b><font size="6">Type:</b></font></td><td align="left"><font size="6"><?php print getCardTypeDropDown($data['type']); ?></font></td><td></td></tr>
	<!--turn off autocomplete for credit card number field http://blogs.atlassian.com/developer/2008/08/form_autocomplete.html -->
	<tr><td><b><font size="6">Card Number:</b></font></td><td align="left"><input type="text" style="font-size:20px"  name="number" id="number" size="16" maxlength="16" autocomplete="off" onblur="verifyCard()" value="<?php print $data['number']; ?>" /></td><td><span id="card_result"></span></td></tr>

	<tr><td></td><td align="left"><input type="submit" name="submit_billing" value="Proceed --&gt;" /></td><td></td></tr>
	<input type="hidden" name="total_price" value="<?php print $data['total_price']; ?>" />
	<input type="hidden" name="shipping" value="<?php print $data['shipping']; ?>" />
	<input type="hidden" name="tax" value="<?php print $data['tax']; ?>" />
	</table>
	</form>
	<?php
}
// display confirmation of data
function display_data_confirm()
{
	print "<p>Please review the following information to ensure that it is correct</p>";
	
//	session_register(data);
	$_SESSION['data'] = $_POST;
	
	$data = $_POST;
	?>
	
	<table><font size="6">
	<tr><td colspan="2">Please verify your billing/shipping information.</td></tr>
	<tr><td colspan="3"><hr /></td></tr>
	
	<tr><td width="150"><b><font size="6">First name:</b></font></td><td align="left"><?php print $data['first_name']; ?></td><td><span id="first_result"></span></td></tr>
	<tr><td><b><font size="6">Last name:</b></font></td><td align="left"><?php print $data['last_name']; ?></td><td><span id="last_result"></span></td></tr>
	<tr><td><b><font size="6">Address 1:</b></font></td><td align="left"><?php print $data['address1']; ?></td><td><span id="address1_result"></span></td></tr>
	<tr><td><b><font size="6">Address 2:</b></font></td><td align="left"><?php print $data['address2']; ?></td><td><span id="address2_result"></span></td></tr>
	<tr><td><b><font size="6">City:</b></font></td><td align="left"><?php print $data['city']; ?></td><td><span id="city_result"></span></td></tr>
	<tr><td><b><font size="6">State:</b></font></td><td align="left"><?php print $data['state']; ?></td><td><span id="state_result"></span></td></tr>
	<tr><td><b><font size="6">Zip:</b></font></td><td align="left"><?php print $data['zip']; ?></td><td><span id="zip_result"></span></td></tr>
	<tr><td><b><font size="6">Phone number:</b></font></td><td align="left"><?php print $data['phone']; ?></td><td><span id="phone_result"></span></td></tr>
	<tr><td><b><font size="6">Email address:</b></font></td><td align="left"><?php print $data['email']; ?></td><td><span id="email_result"></span></td></tr>
	
	<tr><td colspan="3"><hr /></td></tr>
	<tr><td colspan="3">Please verify your credit card information.</td></tr>
	<tr><td><b><font size="6">Exp. Month:</b></font></td><td align="left"><?php print $data['month']; ?></td><td><span id="month_result"></td></tr>
	<tr><td><b><font size="6">Exp. Year:</b></font></td><td align="left"><?php print $data['year']; ?></td><td><span id="date_result"></span></td></tr>
	<tr><td><b><font size="6">Type:</b></font></td><td align="left"><?php print $data['type']; ?></td><td></td></tr>
	<tr><td><b><font size="6">Card Number:</b></font></td><td align="left"><?php print hide_card_number($data['number']); ?></td><td><span id="card_result"></span></td></tr>
	
	<tr><td colspan="3"><hr /></td></tr>
	<tr><td><b><font size="6">Total Price:</b></font></td><td>$<?php print $data['total_price']; ?></td><td></td></tr>
	<tr><td><b><font size="6">Shiping Type:</b></font></td><td><?php if($data['shipping']=="ground") print 'Ground'; elseif($data['shipping']=="two_day") print 'Two Day'; elseif($data['shipping']=="overnight") print 'Overnight';?></td><td></td></tr>	
	
	<tr><td colspan="3"><hr /></td></tr>
	<tr><td colspan="3">*Please note that when you click "Place Order" your card will be billed. </td></tr>
		
	<tr>
		<td align="left"><form action="" method="post"><input type="submit" name="submit" value="&lt;-- Return to Previous" /></form></td>
		<td><form action="./place_order.php" method="post"><input type="submit" name="place_order" value="Place Order --&gt;" /></form></td>
		<td></td>
	</tr>
	</font>
	</table>

	<?php
}
// Drop down menu to select card type
function getCardTypeDropDown($type)
{
	$return = '<select name="type" id="type">
			<option value="Visa" '; if($type=='Visa') $return .= 'selected="selected"'; $return .= '>Visa</option>
			<option value="MasterCard" '; if($type=='MasterCard') $return .= 'selected="selected"'; $return .= '>Master Card</option>
			<option value="Discover" '; if($type=='Discover') $return .= 'selected="selected"'; $return .= '>Discover</option>
			<option value="AmericanExpress" '; if($type=='AmericanExpress') $return .= 'selected="selected"'; $return .= '>American Express</option>
			</select>';

	return $return;	
}
// Don't show card number
function hide_card_number($num)
{
	for($i=0; $i<(strlen($num)-4); $i++){
		$return .= 'x';
	}
	$return .= substr($num, -4);
	return $return;
}

?>