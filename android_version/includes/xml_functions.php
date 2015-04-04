<?php 
// xml_functions.php
// -------------------------------------------------------------------------
// This page provides several functions which relate speficically to xml
// -------------------------------------------------------------------------
// Functions:
// create_xml_from($cart)
//		- $cart is the array from $_SESSION['cart'] that is the shopping cart
//		- This function takes the cart and creates an XML style organization of
//			the data to be stored in the orders table in the following format:
//					<order>
//						<item>
//							<item_number>###</item_number>
//							<quantity>###</quantity>
//						</item>
//					</order>
//
// readXML($data)
//		- $data is the XML-style that the order is stored in in the orders table
//		- this function parses the $data and returns an array of the item_numbers
//			and their quantities
//
// parseItem($ivalues)
//		- $ivalues are the values from the parsing of the XML in readXML()
//		- returns the parsed item to the array created in readXML()
//
// update_database($data, $order)
//		- $data = all the data collected from the user: billing, credit card
//		- $order = the XML order created from the shopping cart via create_xml_from($cart)
//		- function takes the provided data and inesrts it into the database
//				--> INSERT customer into customers table
//				--> INSERT order into orders table
//				--> UPDATE cd_store to reflect decreased quantities available
//
// -------------------------------------------------------------------------
//functions called:
// xml_parser_create() - create xml parser
// xml_parser_set_option(varied) - configure parser
// xml_parse_into_struct(parser, data, values, tags) - parse xml
// xml_parser_free(parser) - release parser
// array_slice(array, start, length) - return a portion of an array
// count(array) - return number of items in an array
// date(mixed) - returns todays formatted date
// localtime(timestamp) - returns formatted timestamp
// mysql_query(query) - execute query
// mysql_insert_id() - get id of last row inserted
// 
// -------------------------------------------------------------------------
// create xml from cart
function create_xml_from($cart)
{
	$xml = "<order>\n";
	foreach($cart as $item_id => $quantity)
	{	
		if($quantity > 0){
			$xml .= '<item>';
			$xml .= "<item_id>$item_id</item_id>";
			$xml .= "<quantity>$quantity</quantity>";
			$xml .= "</item>\n";
		}
	}
	$xml .= '</order>';
	return $xml;
}

//**************************************************************************************************
//**************************************************************************************************
// read XML
function readXML($data) 
{
	//***********************************************************************************
	//based from: http://us3.php.net/manual/en/function.xml-parse-into-struct.php
	//***********************************************************************************
    // read the XML database of orders
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

    // loop through the structures
    foreach ($tags as $key=>$val)
	{
		//disp_r($tags);
        if ($key == "item")
		{
            $itemranges = $val;
			for ($i=0; $i < count($itemranges); $i+=2)
			{
				$offset = $itemranges[$i] + 1;
				$len = $itemranges[$i + 1] - $offset;
                $tdb[] = parseItem(array_slice($values, $offset, $len));
            }
        }
		else
		{
            continue;
        }
    }
    return $tdb;
}

function parseItem($ivalues) 
{
	//***********************************************************************************
	//based from: http://us3.php.net/manual/en/function.xml-parse-into-struct.php
	//***********************************************************************************
	for ($i=0; $i < count($ivalues); $i++)
	{
        $item[$ivalues[$i]["tag"]] = $ivalues[$i]["value"];
    }
	return $item;
}


//**************************************************************************************************
//**************************************************************************************************

function update_database($data, $order)
{
	// Before we insert the data into the tables, we need to fix the CC Expiration date
	// information the user gave us.  The "00" is because the CC Expiration
	// date is usually just a month and a year... there is no day value on credit cards
	$date = $data['year'] . $data['month'] . "00";
	
	// This variable is used to create the "unique" order Id number
	// the date() function will return 4 digit year (Y)followed by
	// the two digit representation of month and day (md)
	$today_date = date(Ymd);
	
	// The localtime() function returns the current time in 24 hour time
	// format.... hourminsec (with no spaces, colons, etc)
	// Again this will be used to create our unique order id
	// The TRUE parameter that I'm passing it will return an associative array
	// meaning that $time[hour] (instead of $time[0]) will contain the hour
	$time = localtime(time(),TRUE);
	
	// Update the customer table with our new information that the user has given us
	// If they have ordered from us before and enter the exact same information, then we
	// will have duplicate copies in our database (this is because this e-commerce site
	// doesn't have login capabilities).
	$query1 = "INSERT INTO customer_information (first_name, last_name, address1, address2, city, state, zip, telephone, email, cc_num, cc_type, cc_expire)
				VALUES ('$data[first_name]','$data[last_name]','$data[address1]','$data[address2]','$data[city]','$data[state]','$data[zip]','$data[phone]', '$data[email]','$data[number]','$data[type]','$date')"; 
	$result1 = mysql_query($query1) or die(mysql_error());
			
	// This will get the customer Id # from the database so we can insert in our
	// orders table.  This way we know where to send the items
	$cust_id = mysql_insert_id();
//	$insert_id = mysql_insert_id();
//	$cust_id="$data[last_name]" . "$insert_id";
	$query12 = "UPDATE customer_information SET cust_id ='$cust_id' WHERE last_name='$data[last_name]';";
	$result12 = mysql_query($query12) or die(mysql_error());
	
	// The $order_id variable is a concatenation of the last name, today's date, and the hour,min, and sec
	$order_id = "$data[last_name]" . "$today_date" . "$time[tm_hour]" . "$time[tm_min]" . "$time[tm_sec]";
	
	// Insert the item in the orders table
	$query2 = "INSERT INTO customer_order (`order_id`, `cust_id`, `order`, `order_date`, `shipping`) VALUES ('$order_id', '$cust_id', '$order', '$today_date', '$data[shipping]')";
	$result2 = mysql_query($query2) or die(mysql_error());
		
	//decrement quantity available for each item in cart
	foreach($_SESSION['cart'] as $item_id => $quantity)
	{
		if($quantity > 0)
		{
			$query3 = "UPDATE items_laptop SET quantity=quantity-$quantity WHERE item_id=$item_id";
			$result3 = mysql_query($query3) or die(mysql_error());
		}
	}
}


?>