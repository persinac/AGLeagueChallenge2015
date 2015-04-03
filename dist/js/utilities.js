
function generateTimeDropDowns(h,m,s) {
	var retVal = '';
	if(h == 1) {
		retVal += '<select id="rft_hr_selector">';
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
		retVal += '<select id="rft_min_selector">';
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
		retVal += '<select id="rft_sec_selector">';
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