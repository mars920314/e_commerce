<?php
//logout.php
//------------------------------------------------------------------------------------------
// This page is used when user logout and kill the cookie that include the infomation of the
// user
//------------------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_footer() - close the page and include my footer text
//------------------------------------------------------------------------------------------
require_once('./includes/functions.php');
//destroy cookies
setcookie("lastname","",time()-3600);
include_header('e_Store.com', true);
?>

<?php
echo "<font size=\"6\"><center>You are now logged out</font>";
echo "<font size=\"6\"><br>Return to the <a href=\"index.php\"><b>index page</b></a></font>";
echo "<font size=\"6\"><br>Your page would direct to index page in 10 second</font>";
include_footer();
?>