( function( $ ) {
	$.fn.getUsers = function ( params ) {
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

	$.fn.searchUsers = function () {
		const elSearchFields = $( '.field-search' );

		let params = {};

		$.each( elSearchFields, function() {
			const fieldName = $( this ).attr( 'name' );
			params[ fieldName ] = $( this ).val().trim();
		} );

		$.fn.getUsers( params );
	}

	$( function() {
		const elSearch = $( '#search' );
		
		const params = {
			page: 1,
		};

		elSearch.on( 'click', function() {
			$.fn.searchUsers();
		} );

		// Events
        $(document).on('keyup', '#current-page-selector', function(e){
            const self = $(this);

            if(e.keyCode == 13){
                $.fn.searchUsers();
            }
        });

        $(document).on('click', '.next-page', function(e) {
        	const elPageCurrent = $('input[name=page]');
            const total_pages = parseInt($('.total-pages').text());
            const pageCurrent = parseInt(elPageCurrent.val());

            if( pageCurrent < total_pages ) {
                const pageNext = pageCurrent + 1;
				elPageCurrent.val(pageNext);

				$.fn.searchUsers();
            }
        });

        $(document).on('click', '.prev-page', function(e) {
            const pageCurrent = parseInt($('#current-page-selector').val());


            if(pageCurrent > 1) {
                const pagePrev = pageCurrent - 1;

                const params = {
                    page: pagePrev
                };
                getUsers(params);
            }
        });

        $(document).on('click', '.first-page', function(e) {
            const params = {
                page: 1
            };
            getUsers(params);
        });

		 // Call first
        $.fn.getUsers(params);
	} );
}( jQuery ) );
