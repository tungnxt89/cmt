( function( $ ) {
	$( function() {
		const elSearch = $( '#search' );
		const elSearchFields = $( '.field-search' );
		const params = {
			page: 1,
		};

		elSearch.on( 'click', function() {
			$.each( elSearchFields, function() {
				const fieldName = $( this ).attr( 'name' );
				params[ fieldName ] = $( this ).val().trim();
			} );

			getUsers( params );
		} );

		function getUsers( params ) {
			const url = '/cmt/v1/users';

			wp.apiFetch( {
				path: url,
				method: 'POST',
				data: params,
			} ).then( ( res ) => {
				const { status, message, data } = res;
				if ( status === 'success' ) {
					const el_wrapper_list_courses = $( '#wrapper-list-users' );
					const el_tablenav = $( '.tablenav' );

					el_wrapper_list_courses.html( '' );
					el_tablenav.html( '' );

					el_wrapper_list_courses.append( data.content );
					el_tablenav.append( data.paginate );
				}
			} ).catch( ( err ) => {

			} ).then( () => {

			} );
		}
	} );
}( jQuery ) );
