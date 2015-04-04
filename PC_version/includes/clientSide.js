//clientSide.js
//---------------------------------------------------------------------------------
// This JavaScript file provides the functions for the AJAX shopping cart
//---------------------------------------------------------------------------------
//Functions		HELLO WORLD!
//   saveTotal() - saves the total in a permanent location when the user proceeds to checkout
//   calculateInitialTotals() - calculates subtotal, tax, shipping, and total the
//		first time the shopping cart is loaded
//   getNumberOfItemsInCart() - returns the number of items in the cart
//   update_totals() - parse and save the response from a calculate_total.php page call
//   updateCartQuantity(int id, int vailable) - add items to cart, checking them against
//      availability in the database
//   quantity_updated() - parses and saves a response from an update_view_cart.php page call
//   cart_size() - parses and saves response from a cart.php page call
//   updateTotals() - parses and saves response from a calculate_total.php page call
//   getNumCanAdd(int available, int num_requested) - checks how many items are
//      available in the database
//   addToCart(int id, int available) - add some items to the cart
//   added() - parses and saves response from an add_to_cart.php page call
//   removeFromCart(int id) - remove an item from the cart
//   removed() - parses and saves a response from a removed_from_cart.php page call
//   GetXmlHttpObject() - get an XML HTTP object for PHP page calls
//---------------------------------------------------------------------------------

//for the ajax connection
var xmlHttp;

// global variables for keeping up with which item is being
//    adjusted, and by how much
var current_id;
var num_added;
var num_available;


//when the user proceeds to checkout from the shopping cart, this function
//  retrives the total value from the display, and saves it as the value for
//  a hidden field in the form. Now $_POST['total'] will = the total in 
//  checkout.php
function saveTotal()
{
    var temp = document.getElementById("total").innerHTML;
    document.getElementById("total_price").value = temp;
}

function calculateInitialTotals()
{
	xmlHttp=GetXmlHttpObject();
    var url="./includes/calculate_total.php";
			url=url+"?shipping=ground";
			url=url+"&tax=in_state";
            url=url+"&sid="+Math.random();
            xmlHttp.onreadystatechange=update_totals;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
}

//function to update the number of items displayed in the shopping cart icon
function getNumberOfItemsInCart()
{
    var url="./includes/cart_size.php";
            url=url+"?sid="+Math.random();
            xmlHttp.onreadystatechange=cart_size;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
}

function update_totals() 
{ 

    if (xmlHttp.readyState==4)
    { 
        var string = xmlHttp.responseText;
		var temp = string.split ( '?' );
		
		document.getElementById("subtotal").innerHTML = temp[0];
		document.getElementById("shipping").innerHTML = temp[1];
		document.getElementById("tax").innerHTML = temp[2];
		document.getElementById("total").innerHTML = temp[3];	
    }
}


//when a new value is entered for a cart item quanity, this sets the
//  new quantity.
//id = item_number being updated
//available = quantity of items available
//the function ensures that the user does not add more items to the
//cart than the number available
function updateCartQuantity(id, available)//used
{
    //set the global num_available INT variable
    num_available = available;
    //set the global current_id varaible, for the product being adjusted
    current_id = id;
    //get the number requested to add from the HTML field
    var num_requested = document.getElementById("qty"+id).value;
    
    //make sure that no one requests a negative number of items
    if(Number(num_requested) > 0)
    {
        //ensure that the product id has length
        if(id.length!=0)
        {
            //get the number that will be added
            num_added = getNumCanAdd(available, num_requested);
            //ajax connection
            xmlHttp=GetXmlHttpObject();
            if (xmlHttp==null)
              {
              alert ("Your browser does not support AJAX!");
              return;
              } 
            //url to update the cart in the session variable
            var url="./includes/update_view_cart.php";
            url=url+"?id="+id;
            url=url+"&qty="+num_added;
            url=url+"&sid="+Math.random();
            //update the session
            xmlHttp.onreadystatechange=quantity_updated;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
        }
    }
    return false;
}

//called when the session has updated
//the php returns the new total for the item
//function updates display for item totals and sets new values in the form values
function quantity_updated() 
{ 
    if (xmlHttp.readyState==4)
    { 
        //var previous = document.getElementById("item_"+current_id+"_total").innerHTML;
        var updated = xmlHttp.responseText;
        
        document.getElementById("item_"+current_id+"_total").innerHTML = updated;
        document.getElementById("qty"+current_id).value = num_added;
        
        //update the shopping cart icon total number
        getNumberOfItemsInCart();
        //update the subtotal, tax, shipping and total
        updateTotals();
    }
}

//when the php returns, update the shopping cart icon total #
function cart_size()
{
    if (xmlHttp.readyState==4)
    {
        document.getElementById("cart_num_items").innerHTML = xmlHttp.responseText;
    }
}

//function called whenever an item's quantity, shipping, or tax is changed
//updates the subtotal, shipping, tax, and total
function updateTotals()
{
	var url="./includes/calculate_total.php";
		url=url+"?shipping="+document.getElementById("shipping_type").value;
		url=url+"&tax="+document.getElementById("tax_type").value;
        url=url+"&sid="+Math.random();
        xmlHttp.onreadystatechange=update_totals;
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
}

//-----------------------------------------------------------------------------

//return how many items of the request can actually be added
function getNumCanAdd(available, num_requested)//used
{   
    if(num_requested <= available){
		//can fulfil request
		return num_requested;
	}else{
	    //cannot return all the were requested, only those available
	    alert("Due to low availability, only a portion of your order can be filled.\nWe apologize for the inconvenience.\n\n"+available+" copy(s) added to your cart.");
	    return available;
	}
}

//function to add an item to the shopping cart based on the item_id and number available to add
//executed from an item page, not from the shopping cart
function addToCart(id, available)
{
    //set global current item id
    current_id = id;
    //set global number available
    num_available = available;
    //set global number requested from HTML field
    num_requested = document.getElementById("qty"+id).value;
    //ensure a positive number is requested
    if(Number(num_requested) > 0)
    {   
        if(id.length!=0)
        {
            //get the number that will be added
            num_added = getNumCanAdd(available, num_requested);
            //ajax connection
            xmlHttp=GetXmlHttpObject();
            if (xmlHttp==null)
              {
              alert ("Your browser does not support AJAX!");
              return;
              } 
            //php url for session variable update
            var url="./includes/add_to_cart.php";
            url=url+"?id="+id;
            url=url+"&qty="+num_added;
            url=url+"&sid="+Math.random();
            //get the results back from the php
            xmlHttp.onreadystatechange=added;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
        }
    }
    return false;
}

//when php returns from adding an item, update the shopping cart header count
//  and upate the number of items displayed by the item
function added() 
{ 
    if (xmlHttp.readyState==4)
    { 
        document.getElementById("cart_num_items").innerHTML = xmlHttp.responseText;
        document.getElementById("status"+current_id).innerHTML = num_added+" copy(s) in <a href=\"./view_cart.php\">Shopping Cart</a><br />  <form name=\"form"+current_id+"\" method=\"post\" action=\"\" onsubmit=\"return removeFromCart("+current_id+")\"> <input type=\"hidden\" name=\"num_available\" id=\"num_available\" value=\""+num_available+"\" /><input type=\"submit\" value=\"Remove from Cart\" /> </form>";
    }
}

//--------------------------------------------------------------------------

//function to remove an item to the shopping cart based on the item_id
//executed from an item page, not from the shopping cart
function removeFromCart(id)
{
    //set global current id
    current_id = id;
    //set global number available from HTML value - so this can be added to the HTML
    //in the span for the button that is put back on the page for adding the item
    num_available = document.getElementById("num_available").value;
    if(id.length!=0)
    {
        //ajax connection
        xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null)
          {
          alert ("Your browser does not support AJAX!");
          return;
          } 
        //url to remove item from session shopping cart
        var url="./includes/remove_from_cart.php";
        url=url+"?id="+id;
        url=url+"&sid="+Math.random();
        //when php returns
        xmlHttp.onreadystatechange=removed;
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
    }
    return false;
}

//function called when php returns, to update the cart header and to
//  replace the remove button with an add button next to the item
function removed() 
{ 
    if (xmlHttp.readyState==4)
    { 
        document.getElementById("cart_num_items").innerHTML = xmlHttp.responseText;
        document.getElementById("status"+current_id).innerHTML = "<form name=\"form"+current_id+"\" method=\"post\" action=\"\" onsubmit=\"return addToCart("+current_id+", "+num_available+")\">  <input type=\"text\" id=\"qty"+current_id+"\" size=\"4\" value=\"1\" />  <input type=\"submit\" value=\"Add to Cart\" />   </form>";
    }
}


//--------------------------------------------------------------------------

//standard function for AJAX connections
//taken directly from ELEC 5220 lab manual
function GetXmlHttpObject()
{
    var xmlHttp=null;
    try
    {
      xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
      try
      {
         xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
      catch (e)
      {
         xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    }
    return xmlHttp;
} 
