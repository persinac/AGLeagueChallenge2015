
function generateTimeDropDowns(h,m,s,name) {
	var retVal = '';
	if(h == 1) {
		retVal += '<select name="rft_hr_selector_'+name+'" id="rft_hr_selector_'+name+'">';
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				retVal += '<option value="0'+i+'">0'+i+'</option>';
			} else {
				retVal += '<option value="'+i+'">'+i+'</option>';
			}
		}
		retVal += '</select> : ';
	}
	if(m == 1) {
		retVal += '<select name="rft_min_selector_'+name+'" id="rft_min_selector_'+name+'">';
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				retVal += '<option value="0'+i+'">0'+i+'</option>';
			} else {
				retVal += '<option value="'+i+'">'+i+'</option>';
			}
		}
		retVal += '</select> : ';
	}
	if(s == 1) {
		retVal += '<select name="rft_sec_selector_'+name+'" id="rft_sec_selector_'+name+'">';
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				retVal += '<option value="0'+i+'">0'+i+'</option>';
			} else {
				retVal += '<option value="'+i+'">'+i+'</option>';
			}
		}
		retVal += '</select>';
	}
	return retVal;
}