<?php
//login.php
//------------------------------------------------------------------------------------------
// This page is used to login, user is encouraged to login before shopping
// When user logged in, this will set two cookies for user
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
	if(isset($_POST[last_name]))
	{
		// find user in database by using lastname and email 
		$query = "Select * from customer_login WHERE last_name=\"$_POST[last_name]\" AND password=\"$_POST[password]\" ";
		// Run this query
		//$query = stripslashes($query);
		$result = mysql_query($query) or die(mysql_error());
		// Fectch the row of the table
		$num_cols = mysql_num_rows($result);
		$row = mysql_fetch_row($result);
		if (($num_cols>0))
		{
			$visit_times=$row[2];
			$cust_session_id=$row1[1];
			$PHPSESSID=session_id();
			if($cust_session_id != $PHPSESSID)
			{
				$visit_times=$visit_times+1;
				$sql2="UPDATE customer_login SET cust_session_id=\"$PHPSESSID\", counter=\"$visit_times\" WHERE last_name=\"$last_name\"";
				$rs2=mysql_query($sql2);
			}
			setcookie("lastname","$_POST[last_name]",time()+60*60);
			setcookie("usercounter","$visit_times",time()+60*60);
			print '<center><form action="">';
			print '<p>Log In Successful!</p>';
			print 'Now, <a href="./index.php">go for shopping</a>';
			print '</form>';
		}
		else
		{
			echo"<center><form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
			echo"<p>There was an error with your Lastname/Password combination.</p>";
			echo"<p>Lastname: <input type=\"text\" name=\"last_name\"></p>";
			echo"<p>password: <input type=\"password\" name=\"password\"></p>";
			echo"<p><input type=\"submit\" value=\"LOG IN\"></p>";
			echo"<a href=\"createnew.php\">Create a New Account</a>";
			echo"</form>";
		}
	}
	else
	{
		echo"<center><form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
		echo"<p>Lastname: <input type=\"text\" name=\"last_name\"></p>";
		echo"<p>password: <input type=\"password\" name=\"password\"></p>";
		echo"<p><input type=\"submit\" value=\"LOG IN\"></p>";
		echo"<a href=\"createnew.php\">Create a New Account</a>";
		echo"</form>";
	}

include_footer();
?>
