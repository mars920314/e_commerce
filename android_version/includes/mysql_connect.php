<?php
//mysql_connect.php
//-----------------------------------------------------------------
// This page connects to the database when included in another page.
//	
// This page also provides an escapt function used for preparing
//	data for the database by ensuring that it is properly escaped
//
// The concept of this page was taken from PHP and MySQL for 
//	Dynamic Web Sites by Larry Ullman
//-----------------------------------------------------------------
//Functions:
//
// escape_data($data)
//		- $data is data that the user wants to ensure is escaped
//		- returns properly escaped data
//-----------------------------------------------------------------
//Functions called:
// mysql_connect(dbhost, dbuser, dbpassword) - connect to the database
// mysql_select_db(db) - select the given database
// mysql_error() - print the error
// trim(data) - remove whitespace from head and tail
// ini_get(str) - get the property from the PHP INI file
// stripslashes(data) - remove slashed from escaped data
// function_exists(funct) - true/false - does the funct exist
// mysql_real_escape_string(str) - escape string for mysql
// mysql_escape_string(str) - depricated function for what the previous does
//------------------------------------------------------------------


//set database access infomation as constants
define ('DB_USER', 'root');
define ('DB_PASSWORD', '526840');
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'e_store');

//connect to the database
$dbc = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) 
	OR die ('Could not connect to MySQL: '. mysql_error());

//select the database
@mysql_select_db(DB_NAME) OR die ('Could not select the database: '.mysql_error());		


//function for escaping data
//taken from PHP and MySQL for Dynamic Websites - by Larry Ullman
function escape_data($data)
{
	$data = trim($data);
	
	//address magic quotes
	if(ini_get('magic_quotes_gpc'))
	{
		$data = stripslashes($data);
	}
	
	//check for mysql_real_escape_string() support
	if(function_exists('mysql_real_escape_string'))
	{
		global $dbc;
		$data = mysql_real_escape_string(trim($data),$dbc);
	}
	else
	{
		$data = mysql_escape_string(trim($data));
	}
	
	//return escaped value
	return $data;
}//end escape_data function		

?>