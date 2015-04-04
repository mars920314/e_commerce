<?php

// connect to mysql
//$link = mysql_connect('localhost', 'root', '526840');
//connect to the database
$link = @mysql_connect('localhost', 'root', '526840') 
	OR die ('Could not connect to MySQL: '. mysql_error());

//select the database
// select database
//mysql_select_database('write_here_mysql_database_name',$link);
@mysql_select_db('e_store', $link) OR die ('Could not select the database: '.mysql_error());	

// cookie details here
//$counted = $_COOKIE["counter"];
$cart_string = "";

// if this is first visitor then
if(!isset($_COOKIE["counter"])){
	$cart_string = "This Tracks You";
// we create cookie for visitor
    setcookie("counter",$cart_string,time()+60*60*60);

// add counter to database
    mysql_query("UPDATE stats SET counter=1");
}
else{
	 mysql_query("UPDATE stats SET counter= counter+1");
	 if(isset($_COOKIE["Cart"]))
	{
		$_SESSION['cart'] = readXML($_COOKIE["Cart"]);
		print $_COOKIE["Cart"];
	}
	
}


$query=mysql_query("SELECT * FROM stats");

while($row=mysql_fetch_assoc($query)) {
$stats=$row['counter']; }

?>