<?php
//createnew.php
//------------------------------------------------------------------------------------------
// This page is used to create a new account when user visit e_store first time
// When create new account, user's information will be written into database and set two cookies for user at the same time
//------------------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_footer() - close the page and include my footer text
//------------------------------------------------------------------------------------------
require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');
session_start();
include_header('e_Store.com', true);
?>

<?php
	if(isset($_POST[last_name])&&isset($_POST[password]))
	{
		$PHPSESSID=session_id();
		// find user in database by using lastname and email 
		$query = "INSERT INTO customer_login (last_name, cust_session_id, counter, password)VALUES ('$_POST[last_name]', '$PHPSESSID', \"1\", '$_POST[password]')";
		// Run this query
		$query = stripslashes($query);
		$result = mysql_query($query) or die(mysql_error());
			setcookie("lastname","$_POST[last_name]",time()+60*60);
			setcookie("usercounter","1",time()+60*60);
			print '<center><form action="">';
			print '<font size="6"><p>Welcome to e_store!</p></font>';
			print '<font size="6">Now, <a href="./index.php">go for your first shopping</a></font>';
			print '</form>';
	}
	else
	{
		echo"<center><form action=\"$_SERVER[PHP_SELF]\" method=\"POST\" onsubmit=\"return verifyForm()\">";
		echo"<font size=\"6\"><p>Create your account.</p></font>";
		echo"<font size=\"6\"><p>Lastname: <input type=\"text\" style=\"font-size:30px\" size=\"15\" name=\"last_name\" id=\"last\" onblur='lastNameValidator()'></p></font>";
		echo"<font size=\"6\"><p>password: <input type=\"password\" style=\"font-size:30px\" size=\"15\" name=\"password\"></p></font>";
		echo"<font size=\"6\"><p><input type=\"submit\" style=\"font-size:30px\" value=\"Create New\"></p></font>";
		echo"</form>";
	}

include_footer();
?>
