var $j = jQuery.noConflict();

$j( document ).ready( function() { 
	/* get template path */
	var template_path = $j( '#template_path' ).val();
	
	/* detect low resolutions & mobile version */
    var isLoRes = window.matchMedia( "only screen and ( max-width: 800px )" );
    var isMobile = checkIsMobile();
	
	/* fix video dimensions */
	resizeIframes();
	
	if ( ! isMobile ) {
		
		/* fade on hover */
    	$j( 'a:not(.menu-item > a, .release-bloc > a)' ).hoverFade();
    	$j( 'li.release-bloc' ).hoverFade();
    	$j( '#wpfront-scroll-top-container' ).hoverFade();
    	$j( '.nl-subscribe-widget input.submit' ).hoverFade();
		
		/* top menu display */
		$j( '#ac-main-menu' ).css( { 'backgroundColor':'ffffff' } );
		$j( "#ac-main-menu" ).hover( function() { 
			$j( this ).stop( true, true ).animate( { 'backgroundColor':'transparent' }, 'slow' );
		}, function () { 
			$j( this ).stop( true, true ).animate( { 'backgroundColor':'#ffffff' }, 'slow' );
		} );
		
	} else {
		
		/* add 'mobile' class to body */
    	$j( document.body ).addClass( 'mobile' );
    	
    	/* reorganize Releases & Shop grid */
	    responsiveGrid( 3, 'shop', 'records' );
	    responsiveGrid( 3, 'shop', 'digital' );
    	
	}
	
    if ( ! isLoRes.matches ) { /* high resolution */
    
    	/* remove 'lores' class to body */
    	$j( document.body ).removeClass( 'lores' );
	    
     } else {  /* low resolution */
	 	
	 	/* add 'lores' class to body */
    	$j( document.body ).addClass( 'lores' );
    	
	 	/* handle main menu links */
	    $j( '#ac-main-menu li' ).click( function() { 
		    window.location.href = $j( this ).find( 'a' ).attr( 'href' );
	    } );
	    
	    /* reorganize Releases & Shop grid */
	    responsiveGrid( 2, 'release', '' );
	    responsiveGrid( 2, 'shop', 'records' );
	    responsiveGrid( 2, 'shop', 'digital' );
	    
	    /* swap sidebar & content */
	    $j( '#secondary' ).after( $j( '#primary' ) );
	    
	    /* move elements of release sidebar */
	    $j( '.release-sidebar .info' ).clone().appendTo( '.mobile-details .left' );
	    $j( '.mobile-details .info' ).after( '<hr class="small-line special" />' );
	    $j( '.release-sidebar .credits' ).clone().appendTo( '.mobile-details .left' );
	    $j( '.mobile-details .credits' ).after( '<hr class="small-line special" />' );
	    $j( '.release-sidebar .links' ).clone().appendTo( '.mobile-details .left' );
	    $j( '.release-sidebar .tracklist' ).clone().appendTo( '.mobile-details .right' );
	    $j( '.mobile-details .tracklist' ).after( '<hr class="small-line special" />' );
	    $j( '.release-sidebar .buy' ).clone().appendTo( '.mobile-details .right' );
    }
    
    /* on resize */
    $j( window ).resize( function() {
	    
	    /* resize all iframes */
		resizeIframes();
		
		if ( ! isMobile ) {
			
			$j( document.body ).removeClass( 'mobile' );
			
		} else {
			
			$j( document.body ).addClass( 'mobile' );
			
		}
		
		if ( ! isLoRes.matches ) {
			
			if ( $j( document.body ).hasClass( 'lores' ) ) {
				$j( document.body ).removeClass( 'lores' );
				responsiveGrid( 3, 'release', '' );
				responsiveGrid( 3, 'shop', 'records' );
				responsiveGrid( 3, 'shop', 'digital' );
			}
			
		} else {
			
			if ( ! $j( document.body ).hasClass( 'lores' ) ) {
				$j( document.body ).addClass( 'lores' );
				responsiveGrid( 2, 'release', '' );
				responsiveGrid( 2, 'shop', 'records' );
				responsiveGrid( 2, 'shop', 'digital' );
			}
			
		}
		
	} ); 
	 
    /* create lightboxes */
    $j( '.band-pics a, .record-pics a' ).fancybox( { 
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	} );
	
	/* read more */
	$j( '#read-more' ).click( function () { 
		$j( this ).prev().fadeOut( 200 );
		$j( this ).remove();
    	$j( '#description-more' ).slideToggle( 800 );
    } );
    
    /* detect changes in quantities fields on shopping cart page */
    if ( $j( '#ac-miic' ).length ) {
	    var cart_total_qty = $j( '.woocommerce-cart table.cart input.qty' ).getCartTotalQty();
	    var cart_max_qty = atob( $j( '#ac-miic' ).val() );
	    $j( '.woocommerce-cart table.cart input.qty' ).each( function() { 
			var elem = $j( this );
			elem.data( 'oldVal', elem.val() );
			elem.bind( "propertychange change click keyup input paste", function( event ) { 
				var updated_cart_total_qty = $j( '.woocommerce-cart table.cart input.qty' ).getCartTotalQty();
				if ( updated_cart_total_qty > cart_max_qty ) {	 
					$j( '.woocommerce' ).prepend( '<div class="woocommerce-error">Sorry, you can\'t add more than ' + cart_max_qty + ' records to your cart.</div>' );
					$j( '.woocommerce-error' ).delay( 2000 ).fadeOut( 400 );
					elem.val( elem.data( 'oldVal' ) );
				} else if ( elem.val() != '' ) {
					elem.data( 'oldVal', elem.val() );
				}
			} );
		} );
	}
 } );

jQuery.fn.extend( { 
	hoverFade: function() {  /* fade on hover */
		var elt = $j( this );
		elt.mouseenter( function() { 
	        $j( this ).fadeTo( 200, 0.5 );
	    } ).mouseleave( function() { 
	        $j( this ).fadeTo( 200, 1 );
	    } );
	},
	hoverFadeCancel: function() {  /* cancel fade on hover */
		var elt = $j( this );
		elt.mouseenter( function() { 
	        $j( this ).stop();
	    } ).mouseleave( function() { 
	        $j( this ).stop();
	    } );
	},
	getCartTotalQty: function() {  /* fade on hover */
		var elt = $j( this );
		var total_qty = 0;  
		elt.each( function() { total_qty += parseInt( $j( this ).val() ); } );
		return total_qty;
	}
} ); 

function checkIsMobile() { /* detect mobile (ie touchscreen) device */
	try	{
		document.createEvent( "TouchEvent" );
		return true;
	}
	catch( e ) {
		return false;
	}
}

function responsiveGrid( itemPerLine, target, subtype ) { /* reorganize Releases & Shop grid */
	var ct = 1;
	var first = true;
	if ( subtype == '' ) {
		var container = '.' + target + '-grid';
	} else {
		var container = '.' + target + '-grid.' + subtype;
	}
    $j( container + ' li.' + target + '-gap' ).each( function() {
	    $j( this ).remove();
    } );
    $j( container + ' li.' + target + '-bloc' ).each( function() {
		$j( this ).removeClass( 'first' );
		if ( ct % itemPerLine != 0 ) {
			if ( first ) { 
				$j( this ).addClass( 'first' );
			}
			first = false;
			$j( this ).after( "<li class=\"" + target + "-gap\">&nbsp;</li>" );
		} else {
			first = true;
		}
		ct++;
    } );
}
	
function resizeIframes() { /* resize all iframes */
	var allVids = $j( "iframe" );
	allVids.each( function() { 
		var elt = $j( this );
		var width = elt.width();
		var height = Number( width ) * 0.5625;
		elt.height( height );
	} );
}

function nlFormSubmit() { /* newsletter subscription form submit */
	if ( $j( '#ac-nl-subscribe' ).valid() ) {
		$j( '#ac-nl-submit' ).addClass( 'loading' );
		var input_obj = $j( '#ac-nl-email' );
		var email = input_obj.val();
		var url = $j( '#ac-nl-subscribe' ).attr( 'action' ).replace( '[email]', email );
		input_obj.prop( 'disabled', true );
		$j.get( url, function( data ) { 
			input_obj.prop( 'disabled', false );
			$j( '#ac-nl-submit' ).removeClass( 'loading' );
 			if ( data.success ) {
				$j( '#ac-nl-submit' ).html( '' );
				$j( '#ac-nl-submit' ).addClass( 'done' );
				$j( '#ac-nl-submit' ).hoverFadeCancel();
				$j( '#ac-nl-submit' ).attr( 'onclick', 'javascript:void(0)' );
				$j( '.ac-nl-messages' ).fadeIn( 400 );
				$j( '.ac-nl-messages' ).html( 'Thanks!' );
			} else {
				$j( '#ac-nl-submit' ).html( '<span>Go</span>' );
				$j( '.ac-nl-messages' ).addClass( 'error' );
				$j( '.ac-nl-messages' ).fadeIn( 400 );
				$j( '.ac-nl-messages' ).html( 'That doesn\'t seem to work, please try again later' );
			}
			$j( '.ac-nl-messages' ).delay( 2000 ).fadeOut( 400 );
			input_obj.prop( 'disabled', false );
		 } );
	}
	return false;
}

function dlCheckFormSubmit() { /* download check form submit */
	if ( $j( '#ac-download-check-form' ).valid() ) {
		if ( $j( '#ac-download-form-container' ).is(":visible") ) {
			$j( '#ac-download-form-container' ).slideToggle( 400 );
		}
		if ( $j( '.ac-dl-error-message' ).is(":visible") ) {
			$j( '#ac-download-code' ).removeClass( 'error' ).addClass( 'valid' );
			$j( '.ac-dl-error-message' ).slideToggle( 400 );
		}
		$j( '#ac-download-check-submit' ).addClass( 'loading' );
		var url = $j( '#ac-download-check-form' ).attr( 'action' );
		var code = $j( '#ac-download-code' ).val();
		$j.post( url, { code: code } ).done(function( data ) {
			$j( '#ac-download-check-submit' ).removeClass( 'loading' );	
			var obj = jQuery.parseJSON( data );
			if ( obj.error_message != '' ) {
				$j( '#ac-download-code' ).removeClass( 'valid' ).addClass( 'error' );
				$j( '.ac-dl-error-message' ).html( obj.error_message );
				$j( '.ac-dl-error-message' ).slideToggle( 800 );
			} else {
				$j( '#ac-download-code' ).val( '' );
				$j( '#ac-download-form-container' ).html( obj.download_form );
				$j( '#ac-download-form-container' ).slideToggle( 800 );
			}
		});	
	}
	return false;
}

function dlFormSubmit() { /* download form submit */
	$j( '#ac-download-form' ).submit();
}
