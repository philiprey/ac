/*global wc_add_to_cart_params */
jQuery( function( $ ) {

	// wc_add_to_cart_params is required to continue, ensure the object exists
	if ( typeof wc_add_to_cart_params === 'undefined' ) {
		return false;
	}

	// Ajax add to cart
	$( document ).on( 'click', '.add_to_cart_button', function() {

		// AJAX add to cart request
		var $thisbutton = $( this );

		if ( $thisbutton.is( '.product_type_simple' ) ) {

			if ( ! $thisbutton.attr( 'data-product_id' ) ) {
				return true;
			}
			
			var cart_qty = $( '#ac-shopping-cart-total' ).html();
			var cart_max_qty = atob( $( '#ac-miic' ).val() );
			if ( isNaN( cart_max_qty ) ) {
				cart_max_qty = 2;
			}
			if ( cart_qty >= cart_max_qty ) {
				if ( checkIsMobile() ) {
					$( '#main' ).prepend( '<div class="woocommerce-error">Sorry, you can\'t add more than ' + cart_max_qty + ' records to your cart.</div>' );
					$( 'body' ).animate({
                    	scrollTop: $( '.woocommerce-error' ).offset().top - 25
                	}, 800);
            	} else {
	            	$( '#content' ).prepend( '<div class="woocommerce-error">Sorry, you can\'t add more than ' + cart_max_qty + ' records to your cart.</div>' );
            	}
				return false;
			}

			$thisbutton.removeClass( 'added' );
			$thisbutton.addClass( 'custom-loading' );

			var data = {};

			$.each( $thisbutton.data(), function( key, value ) {
				data[key] = value;
			});

			// Trigger event
			$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

			// Ajax action
			$.post( wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ), data, function( response ) {

				if ( ! response ) {
					return;
				}

				var this_page = window.location.toString();

				this_page = this_page.replace( 'add-to-cart', 'added-to-cart' );

				// Redirect to cart option
				if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {

					window.location = wc_add_to_cart_params.cart_url;
					return;

				} else {

					$thisbutton.removeClass( 'custom-loading' );

					var fragments = response.fragments;
					var cart_hash = response.cart_hash;

					// Block fragments class
					if ( fragments ) {
						$.each( fragments, function( key ) {
							$( key ).addClass( 'updating' );
						});
					}

					// Block widgets and fragments
					$( '.shop_table.cart, .updating, .cart_totals' ).fadeTo( '400', '0.6' ).block({
						message: null,
						overlayCSS: {
							opacity: 0.6
						}
					});

					// Changes button classes
					$thisbutton.addClass( 'added' );

					// Update cart counter
					var added_qty = data.quantity;
					var total_qty = Number( cart_qty ) + Number( added_qty );
					if ( checkIsMobile() ) {
						$( 'body' ).animate({
	                    	scrollTop: $( '.shopping-cart-widget' ).offset().top - 25
	                	}, 800);
                	}
					$( '#ac-shopping-cart-link' ).fadeTo( 400, 0.5, function() {
						$( '#ac-shopping-cart-total' ).html( total_qty );
						if ( total_qty > 1 ) {
							$( '#ac-shopping-cart-items' ).html( 'items' );
						} else {
							$( '#ac-shopping-cart-items' ).html( 'item' );	
						}
						$( this ).fadeTo( 400, 1, function() {
							if ( total_qty == 1 ) {
								$( '#ac-shopping-cart-checkout-link' ).fadeTo( 0, 0 );
								$( '#ac-shopping-cart-checkout-link' ).css( 'visibility', 'visible' ).fadeTo( 400, 1 );
							}		
						});
					});

					// Replace fragments
					if ( fragments ) {
						$.each( fragments, function( key, value ) {
							$( key ).replaceWith( value );
						});
					}

					// Unblock
					$( '.widget_shopping_cart, .updating' ).stop( true ).css( 'opacity', '1' ).unblock();

					// Cart page elements
					$( '.shop_table.cart' ).load( this_page + ' .shop_table.cart:eq(0) > *', function() {

						$( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();

						$( document.body ).trigger( 'cart_page_refreshed' );
					});

					$( '.cart_totals' ).load( this_page + ' .cart_totals:eq(0) > *', function() {
						$( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();
					});

					// Trigger event so themes can refresh other areas
					$( document.body ).trigger( 'added_to_cart', [ fragments, cart_hash, $thisbutton ] );
				}
			});

			return false;

		}

		return true;
	});

});
