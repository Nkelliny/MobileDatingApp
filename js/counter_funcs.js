/**
 * Update counter widget with number of characters remaining
 * @param string counter id of counter element
 * @param string text_elem id of textarea or input element
 * @param int    max_len maximum allowed character length
 * @return void
 */
function update_counter(counter, text_elem, max_len)
{
    var counterElem = document.getElementById(counter);
    var textElem = document.getElementById(text_elem);
    var len = textElem.value.length;
    var val = max_len - textElem.value.length;
    counterElem.innerHTML = val; 
	if(check_content_length(text_elem,max_len))
	{
		return true;
	}
	else
	{
		alert("Maximum input length is " + max_len + " characters");
		return false;
	}
}

/**
 * Verify that elem_id doesn't contain more than max_len characters
 * @param string elem_id id of elem to count characters in
 * @param int    max_len maxmimum input string (including spaces and returns)
 * @return boolean
 */
function check_content_length(elem_id, max_len)
{
    var elem = document.getElementById(elem_id);
   // alert(elem.value.length);
	if(elem.value.length > max_len) 
	{
        return false;
    }
	else
	{
    	return true;
	}
}