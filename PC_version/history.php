<?PHP
//history.php
//------------------------------------------------------------------------------------------
// This page is used for show the history of the user that with the right information in the 
// cookie.
//------------------------------------------------------------------------------------------
//functions called:
// include_header(string title) - include the page header and shopping cart
// include_footer() - close the page and include my footer text
// readXML() - read xml from order in table table orders
//------------------------------------------------------------------------------------------
require_once('./includes/mysql_connect.php');
require_once('./includes/mysql_functions.php');
require_once('./includes/functions.php');
require_once('./includes/xml_functions.php');
session_start();
include_header('e_Store.com', true);
?>
<h3 style="font-family:Arial, Helvetica, sans-serif">Your history<h3>
<?PHP
$last_name = $_COOKIE["lastname"];
//Find user's id in database;
$sql1="SELECT cust_id FROM customer_information WHERE last_name=\"$last_name\" ";
$rs1=mysql_query($sql1);
$number_cols = mysql_num_rows($rs1);

//display the result on webpage
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">
<tr><td>Order ID</td><td>Customer ID</td><td>Order</td><td>Shipping</td><td>Date ordered</td></tr>";
while ($row = mysql_fetch_array($rs1))
{
  for ($i=0;$i<$number_cols;$i++)
  {
    //find this user's orders
    $query="SELECT * FROM customer_order WHERE cust_id=\"$row[$i]\" ";
    $result=mysql_query($query);
    $number = mysql_num_fields($result);

    while ($line = mysql_fetch_row($result))
   {
      /*display the order's information*/
      echo "<tr>";
      for ($j=0;$j<2;$j++)
      {
        echo "<td>$line[$j]</td>";
      }
      /*Dispaly Order, Parse the XML and display detailed information include item_id and quantity.*/
      echo "<td><ul>";
      $tdb = readXML($line[2]);
      for($counttdb=0;$counttdb<count($tdb);$counttdb++)
      {

        echo "<li>Item_ID:";
        echo $tdb[$counttdb]["item_id"];
        echo ";          ";
        echo "Quntity:";
        echo $tdb[$counttdb]["quantity"];
        echo "</li>";

      }
      echo "</ul></td>";
      /*display order's information*/
      for ($j=3;$j<5;$j++)
      {
         echo "<td>$line[$j]</td>";
      }
      echo "</tr>";
    }
  }
}
include_footer();
?>
