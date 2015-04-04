//checkout.js
//---------------------------------------------------------------------------------
// This JavaScript file provides the functions used for validating the personal
//  information fields of the billing form. These functions were modified from
//  those presented in ELEC 5220 Lab 4, to meet my needs.
//---------------------------------------------------------------------------------
//Functions HELP, please
// firstNameValidator() - checks that the first name meets all restrictions
// lastNameValidator() - checks that the last name meets all restrictions
// address1Validator() - checks that the address meets all restrictions
// addressValidator() - checks a general address to see if it meets address restrictions
// cityNameValidator() - checks that the city name meets all restrictions
// nameValidator(elem) - checks that a name meets general name restrictions
// zipNumberValidator() - checks that the zip code meets all restrictions
// phoneNumberValidator() - checks that the phone number meets all restrictions
// emailValidator() - checks that the email address meets all restrictions
// isAlphabet(elem, string helperMsg) - checks to see if a string is all letters
// isEmpty(field) - determines if a field is empty or not
// numberValidator(elem, int min, int max) - checks to see if a number falls in
//			the specified range
// isNumeric(elem) - checks to see if elem contains only digits
// isNumber(int num) - checks to see if num is a number
// lengthRestriction(elem, min, max) - checks if elem is a legitimate length
// formValid() - see if all of the personal info fields are not blank
//	verifyForm() - see if all of the personal info fields meet restrictions
//---------------------------------------------------------------------------------


var first_name = new Boolean(false);
var last_name = new Boolean(false);
var phone = new Boolean(false);
var address1 = new Boolean(false);
var city = new Boolean(false);
var state = new Boolean(false);
var zip = new Boolean(false);
var email = new Boolean(false);

var exp_month = new Boolean(false);
var expiration = new Boolean(false);
var card = new Boolean(false);


//check email address for proper form
function emailValidator()
{
    var email_field = document.getElementById('email');
    var message = "Please enter a valid email address.";
    
    //regex describing a valid email address
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if(email_field.value.match(emailExp))
    {
        //valid email address
        document.getElementById("email_result").innerHTML= '<img src="./images/green.gif" />';
        email = new Boolean(true);
    }
    else
    {
        //invalid address
        document.getElementById("email_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        email = new Boolean(false);
    }
}

//function to check first name validity
function firstNameValidator()
{
    if(nameValidator("first")){
        first_name = new Boolean(true);
        return true;
    }else{
        first_name = new Boolean(false);
        return false;
    }  
}

//function to check last name validity
function lastNameValidator()
{
    if(nameValidator("last")){
        last_name = new Boolean(true);
        return true;
    }else{
        last_name = new Boolean(false);
        return false;
    }
}

//function to check city name validator
function cityNameValidator()
{
    if(nameValidator("city")){
        city = new Boolean(true);
        return true;
    }else{
        city = new Boolean(false);
        return false;
    }
}

//function that does the actualy processing for the xxxNameValidator() functions
function nameValidator(elem)
{
    var field = document.getElementById(elem);
    if(!isEmpty(field))
    {
        var message = "This field can only contain letters.";
        if(isAlphabet(field, message))
        {
            //alphabetical field is valid, so display check and return true
            document.getElementById(elem+"_result").innerHTML= '<img src="./images/green.gif" />';
            return true;
        }
        else
        {
            //alphabetical field contains other than letters and spaces, so display warning & return false
            document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
            return false;
        }
    }
    else
    {
        //field is empty, and cannot be. return false and display error
        var message = "This field cannot be empty";
        document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        return false;
    }
}

//ensures that names only contain alphabetical characters and spaces
function isAlphabet(elem, helperMsg)
{
    var alphaExp = /^[a-z A-Z]+$/;
    if(elem.value.match(alphaExp))
    {
        return true;
    }
    else
    {
        return false;
    }
}

//returns bool whether field is empty or not
function isEmpty(field)
{
    var uInput = field.value;
    if(uInput.length == 0){
        return true;
    }else{
        return false;
    }
}
//---------------------------------------------------------------------------------------

//calls number validator to check phone input
function phoneNumberValidator()
{
    if(numberValidator("phone", 10, 10)){
        phone = new Boolean(true);
        return true;
    }else{
        phone = new Boolean(false);
        return false;
    }
}

//number validator for zip code input
function zipNumberValidator()
{
    if(numberValidator("zip", 5, 5)){
        zip = new Boolean(true);
        return true;
    }else{
        zip = new Boolean(false);
        return false;
    }
}

//the number validator called by phone and zip
function numberValidator(elem, min, max)
{
    //get the field for this element
    var field = document.getElementById(elem);
    if(!isEmpty(field))
    {
        var message = "Please enter a numeric value.";
        //make sure its a number
        if(isNumeric(field))
        {
            //make sure its 5 digits
            if(lengthRestriction(field, min, max))
            {
                //success - display check and return true
    	        document.getElementById(elem).value = field.value;
                document.getElementById(elem+"_result").innerHTML= '<img src="./images/green.gif" />';
                return true;
            }
            else
            {   
                //numeric, but wrong length - display error and return false
				if(min == max)
					message = "The field needs to be "+min+" characters.";
				else
					message = "The field needs to be "+min+"-"+max+" characters.";
                document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
            }
        }
        //not numeric - error and return false
        document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        return false;
    }
    else
    {
        //cannot be empty. return false and display error
        var message = "This field cannot be empty";
        document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        return false;
    }
}

//check that element's value is numeric
function isNumeric(elem)
{
    //regex for numeric
    var numericExpression = /^[0-9]+$/;
    if(elem.value.match(numericExpression))
    {
        return true;
    }
    else
    {
        return false;
    }
}

//ensure that str (num) is in fact a number
function isNumber(num)
{
    //regex for numeric
    var numericExpression = /^[0-9]+$/;
    if(num.match(numericExpression)){
        return true;
    }else{
        return false;
    }
}

//--------------------------------------------------------------------------------

//check address1 field
function address1Validator()
{
    if(addressValidator("address1")){
        address1 = new Boolean(true);
        return true;
    }else{
        address1 = new Boolean(false);
        return false;
    }
}

//ensure that address field is filled with some length
function addressValidator(elem)
{
    var field = document.getElementById(elem);
    var min = 1;
    var max = 100;
    var message = "This field must be between "+min+" and "+max+" characters";
    if(lengthRestriction(field, min, max)){
        //address is between min and max lengths - return true and display check
        document.getElementById(elem).value= field.value;
        document.getElementById(elem+"_result").innerHTML= '<img src="./images/green.gif" />';
        return true;
    }else{
        //address is too short or too long - error and false
        document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
        return false;
    }
}

//check element for length of its value to be between min and max
function lengthRestriction(elem, min, max)
{
    var uInput = elem.value;
    if(uInput.length >= min && uInput.length <= max){
        return true;
    }else{
        return false;
    }
}


//------------------------------------------------------------------------------------

//check that a state is selected
function stateValidator()
{
    elem = "state";
    var field = document.getElementById(elem);
    var message = "A state must be selected.";
    if(field.value != '')
    {
        //a state was selected - return true and display check mark
	    document.getElementById(elem+"_result").innerHTML= '<img src="./images/green.gif" />';
	    state = new Boolean(true);
        return true;
    }
    //state not selected - return false and error
    document.getElementById(elem+"_result").innerHTML= '<img src="./images/warning.gif" /> <small>'+message+'</small>';
    state = new Boolean(false);
    return false;
}


//check that the bool values for each field are true
function formValid()
{
    //if all form bools are TRUE, entire form is valid
    if(first_name.valueOf() && last_name.valueOf() && phone.valueOf() && address1.valueOf() && city.valueOf() && state.valueOf() && zip.valueOf() && exp_month.valueOf() && expiration.valueOf() && card.valueOf() && email.valueOf() )
    {
        return true;
    }
    else
    {
        return false;
    }
}

//see if all fields are validly complete - if so, return true
//if not, return false and error message
function verifyForm()
{
    //call all field validators
    firstNameValidator()
    lastNameValidator();
    phoneNumberValidator();
    address1Validator();
    cityNameValidator();
    stateValidator();
    zipNumberValidator();
    emailValidator();
    
    verifyMonth();    
    verifyExpiration();
    verifyCard();
    
    //if all are valid, return true
	// alert(first_name.valueOf());
	// alert(last_name.valueOf()) ;
	// alert(phone.valueOf()) ;
	// alert(address1.valueOf());
	// alert(city.valueOf());
	// alert(state.valueOf());
	// alert(zip.valueOf());
	// alert(email.valueOf());
	// alert(exp_month.vlaueOf());
	// alert(expiration.vlaueOf());
	// alert(card.valueOf());
    if(formValid()){
        return true;
    }else{
        //if errors, display message and return false
        alert("There are errors in your form. Please try again.");
        return false;
    }
}