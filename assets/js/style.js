( function( $ ) {
	$( function() {
		console.log(12312321);

		const elFieldName = $('input[name="display[]"]');

		elFieldName.on('click', function(){
			const val = $(this).val();
			const elField = $('.' + val);

			if ($(this).is(':checked')) {
				elField.show();
			} else {
				elField.hide();
			}
		});
	} );
}( jQuery ) );
