/**
 * Aqua SVG Sprite Meta Box
 */
jQuery( function($) {
	// on click of the upload button
	$('#aqua-svg-button').on('click', function() {
		// backup original send function
		var send_attachment_bkp = wp.media.editor.send.attachment;
		// send the new request
		wp.media.editor.send.attachment = function( props, attachment ) {
			// set hidden field's value to save attachment ID in database
			$( '#aqua-svg')
				.val( attachment.id );
			// update the image preview without refresh
			$( '#aqua-svg-preview' )
				.each(function(){
					var $this = $(this);
					// if there's already an image just update the src
					if ( $this.is( 'img' ) ) {
						$this.attr( 'src', attachment.url );
						console.log('no...');
					// if there wasn't an image yet then create it
					} else {
						var imageHTML = '<img ';
							imageHTML += 'src="';
							imageHTML += attachment.url;
							imageHTML += '" id="aqua-svg-preview"';
							imageHTML += ' style="max-width:200px;height:auto;"';
							imageHTML += ' alt="SVG preview image"';
							imageHTML += ' />';
						console.log(imageHTML);
						$this.replaceWith( imageHTML );
					}
				} );
			// now revert back
			wp.media.editor.send.attachment = send_attachment_bkp;
		}
		// open the popup
		wp.media.editor.open();
		// yes
		return false;
	});
} );