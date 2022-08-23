// Older "accept" file extension method. Old docs: http://docs.jquery.com/Plugins/Validation/Methods/accept
$.validator.addMethod( "extension", function( value, element, param ) {
	param = typeof param === "string" ? param.replace( /,/g, "|" ) : "png|jpe?g|gif";
	return this.optional( element ) || value.match( new RegExp( "\\.(" + param + ")$", "i" ) );
}, $.validator.format( "Please enter a value with a valid extension." ) );

// Accept a value from a file input based on a required mimetype
$.validator.addMethod( "accept", function( value, element, param ) {

	// Split mime on commas in case we have multiple types we can accept
	var typeParam = typeof param === "string" ? param.replace( /\s/g, "" ) : "image/*",
		optionalValue = this.optional( element ),
		i, file, regex;

	// Element is optional
	if ( optionalValue ) {
		return optionalValue;
	}

	if ( $( element ).attr( "type" ) === "file" ) {

		// Escape string to be used in the regex
		// see: https://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex
		// Escape also "/*" as "/.*" as a wildcard
		typeParam = typeParam
				.replace( /[\-\[\]\/\{\}\(\)\+\?\.\\\^\$\|]/g, "\\$&" )
				.replace( /,/g, "|" )
				.replace( /\/\*/g, "/.*" );

		// Check if the element has a FileList before checking each file
		if ( element.files && element.files.length ) {
			regex = new RegExp( ".?(" + typeParam + ")$", "i" );
			for ( i = 0; i < element.files.length; i++ ) {
				file = element.files[ i ];

				// Grab the mimetype from the loaded file, verify it matches
				if ( !file.type.match( regex ) ) {
					return false;
				}
			}
		}
	}

	// Either return true because we've validated each file, or because the
	// browser does not support element.files and the FileList feature
	return true;
}, $.validator.format( "Please enter a value with a valid mimetype." ) );

// validate the file size KB
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size/1000 <= param)
}, 'File size must be less than {0}');

// add regex validation of name
$.validator.addMethod('validName', function(value, element, param) {
	if (value != '') {
		if (value.match(/^[а-яёґєіїА-ЯЁҐЄІЇa-zA-Z0-9\s]*$/u) == null) {
			return false;
		}
	}
	return true;
},'Invalid user name');

// add regex validation of password
$.validator.addMethod('validPassword', function(value, element, param) {
	if (value != '') {
		if (value.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/) == null) {
			return false;
		}
	}
	return true;
},'Invalid password');

$.validator.addMethod("atLeastOneType", function(value, elem, param) {
    return $(".type-cb input:checked").length > 0;
},"At least one type required");

$.validator.addMethod("atLeastOneCondition", function(value, elem, param) {
    return $(".condition-cb input:checked").length > 0;
},"At least one condition required");

$.validator.addMethod("atLeastOneRole", function(value, elem, param) {
    return $(".role-cb input:checked").length > 0;
},"At least one role required");

$.validator.addMethod("atLeastOneThread", function(value, elem, param) {
    return $(".thread-cb input:checked").length > 0;
},"At least one thread required");