//creditCard.js MODIFIED
//---------------------------------------------------------------------------------
// This JavaScript file provides the functions used for validating the credit
//  card fields of the billing form. These functions were modified from
//  those presented in ELEC 5220 Lab 5, to meet my needs.
//---------------------------------------------------------------------------------
// the boolean variable in this file are declared and used in checkout.js to verify
// that the entire form has been filled in correctly
//---------------------------------------------------------------------------------
//functions:
// verifyMonth() - ensure month is a valid month number, and update form with results
// isValidMonth(month) - return boolean whether num is valid as a month
// verifyExpiration() - update form with results of expiration verification 
// verifyCard() - ensure that card number is valid and update form accordingly
// expirationValid(c_month, c_year, month, year) - check card month and year against
//          today's month and year to make sure card not expired
// wellFormedNumber(number, type) - algorithm to see if card number is valid for its type
//---------------------------------------------------------------------------------
// Verify the month
function verifyMonth()
{
    var message = "You must enter a numeric month.";
    var month = document.getElementById("month").value;
    
	
    if(isValidMonth(month))
    {
        document.getElementById("month_result").innerHTML= '<img src="./images/green.gif" />';
		if(verifyExpiration())
		{
			exp_month = new Boolean(true);
			return true;
		}
		else
		{
			exp_month = new Boolean(false);
			return false;
		}

    }
    else
    {
		
		document.getElementById("month_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        exp_month = new Boolean(false);
		return false;
    }
	
}
// See if month is valid
function isValidMonth(month)
{
    if(isNumber(month)){
        if(Number(month)>0 && Number(month)<13){
            return true;
        }else{
            return false;
        }    
    }else{
        return false;
    }
}
// Verify the expiration date
function verifyExpiration()
{
    var message = "Your card has expired.";
    var curdate = new Date();
    var c_year = curdate.getFullYear();
    var c_month = curdate.getMonth();

    //getMonth() returns January as 0, so increment by 1
    //http://www.w3schools.com/jsref/jsref_getMonth.asp
    c_month++;

    var month = document.getElementById("month").value;
    var year = document.getElementById("year").value;
    
    if(expirationValid( c_month, c_year, month, year ))
    {
        //alert("valid");
        document.getElementById("date_result").innerHTML= '<img src="./images/green.gif" />';
        expiration = new Boolean(true);
		return true;//IT TOOK ME HOURS TO DIG THIS OUT. The return statement was omited form proivded code.
    }
    else
    {
        document.getElementById("date_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        expiration = new Boolean(false);
		return false;
    }
}
// verify credit card
function verifyCard()
{
    var message = "Card number is not valid.";
    var number = document.getElementById("number").value;
    var type = document.getElementById("type").value;
    	
	if(verifyExpiration())
	{}	
		
    //then see if number is valid
    if(wellFormedNumber(number, type))
    {
			document.getElementById("card_result").innerHTML= '<img src="./images/green.gif" />';
			card = new Boolean(true);
			return true;
    }
    else
    {
        document.getElementById("card_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        card = new Boolean(false);
        return false;
    }
	
	verifyExpiration();
}

//function to check expiration date
function expirationValid(c_month, c_year, month, year)
{
    //if a previous year, invalid
    if(year < c_year)
    {
        return false;
    }
    else
    {
        //if this year, but previous month, invalid
        if(month < c_month && c_year == year)
        {
            return false;
        }
        //otherwise valid
        else
        {
            return true;
        }
    }
}

//check to see if number is valid or not
function wellFormedNumber(number, type)
{
    // turn the number into a string
	var n = ""+number+"";
	// find its length
    var c = number.length;
        
    //switch syntax from http://www.w3schools.com/js/js_switch.asp
    switch(type)
    {
        case "Visa":
            if (number[0] == 4 && (c == 16 || c == 13)) break;
            else { //alert("Your card is not a valid Visa card. Please try again."); 
                return false;}
            break;
        case "MasterCard":
            if (number[0] == 5 && number[1] < 6 && number[1] > 0 && c == 16) break;
            else { //alert("Your card is not a valid MasterCard. Please try again."); 
                return false;}
            break;
        case "Discover":
            if (number[0] == 6 && number[1] == 0 && number[2] == 1 && number[3] == 1 && c == 16) break;
            else { //alert("Your card is not a valid Discover card. Please try again."); 
                return false;}
            break;
        case "AmericanExpress":
            if (number[0] == 3 && (number[1] == 4 || number[1] == 7) && c == 15) break;
            else { //alert("Your card is not a valid American Express card. Please try again."); 
                return false;}
            break;
        default:
            return false;
    }
    
   	// dummy
	var t = 1;
	// loop from the right to left
	var i=0;
	var value = "";
	for(i = (c - 1); i >= 0; i--)
	{
		// mult. by 1 or 2
		var temp = parseInt(n[i]) * t;
		
		// toggle between 1 and 2
		if(t == 1) t = 2;
		else t = 1;
		if(temp > 9)
			temp = temp/10 + temp%10
			
		value = ""+value+temp+"";
	}
	
	// loop and sum up each idiv. digit
	var l = value.length;
	
	var checksum = 0;
	for(i = 0; i < l; i++)
	{
		checksum = checksum + parseInt(value[i]);
	}
	
	// mod the checksum by 10
	var verified = checksum % 10;
	
	// if not 0 return 0 
	if (verified != 0){
	    //alert("Your card number is not valid. Please try again.");
	    return false;
	}
    return true;
}