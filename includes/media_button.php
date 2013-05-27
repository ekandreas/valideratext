<?php

/**
 * For shortcode functionality
 * Autoload, instance at the bottom of this page!
 */
class ValideraText_Media_Button {

	function __construct() {

		//Add buttons to tinymce
		add_action( 'media_buttons_context', array( $this, 'media_buttons_context' ) );

		//add some content to the bottom of the page
		//This will be shown in the inline modal
		add_action( 'admin_footer', array( &$this, 'add_inline_popup_content' ) );

	}

	function media_buttons_context( $context ) {
		$image_btn = WP_PLUGIN_URL . '/valideratext/images/valideratext.png';
		$out       = '<a href="#TB_inline?width=400&inlineId=popup_valideratext" class="thickbox button valideratext_button" title=""><img src="' . $image_btn . '" alt="' . __( 'Validera din text', 'valideratext' ) . '" /> Valideratext&nbsp;&nbsp;</a>';
		return $context . $out;
	}


	function add_inline_popup_content() {

		?>

		<?php add_thickbox(); ?>

		<!--suppress ALL -->
		<div id="popup_valideratext" style="display:none;">

			<?php

			$options = get_option( 'valideratext_general' );

			if( !$options ){
				echo 'Modulen för Valideratext behöver ställas in. Det görs i WordPress av administratören under "Inställningar" och "Valideratext".';
				echo '</div>';
				return;
			}

			$apiurl = $options['apiurl'];
			$username = $options['username'];
			$userpassword = $options['userpassword'];
			$applicationname = "WordPress";
			$applicationversion = "3.5.1";
			$addinpublisher = "Flowcom AB";
			$addinname = "valideratext";
			$addinversion = "2.0";
			$debug = ( isset($options['debug']) && $options['debug']!='off' ) ? true : false;

			$user = wp_get_current_user();

			$valideratext_username = get_user_meta( $user->ID, 'valideratext_username', true);
			$valideratext_password = get_user_meta( $user->ID, 'valideratext_password', true);
			if( !empty( $valideratext_username ) ) $username = $valideratext_username;
			if( !empty( $valideratext_password ) ) $userpassword = $valideratext_password;

			?>

			<?php if( !$debug ) { ?>
				<iframe name="valideratext_iframe" width="100%" height="100%" style="margin-left: -15px; margin-top: -2px;"></iframe>
			<?php } ?>

			<form style="display:none;" target="valideratext_iframe" method="POST" name="valideraform" id="valideraform" action="<?php echo $apiurl; ?>">

				<p>
					AddInName:<br />
					<input type="text" name="AddInName" value="<?php echo $addinname; ?>" />
				</p>

				<p>
					AddInPublisher:<br />
					<input type="text" name="AddInPublisher" value="<?php echo $addinpublisher; ?>" />
				</p>

				<p>
					AddInVersion:<br />
					<input type="text" name="AddInVersion" value="<?php echo $addinversion; ?>" />
				</p>

				<p>
					ApplicationName:<br />
					<input type="text" name="ApplicationName" value="<?php echo $applicationname; ?>" />
				</p>

				<p>
					ApplicationVersion:<br />
					<input type="text" name="ApplicationVersion" value="<?php echo $applicationversion; ?>" />
				</p>

				<p>
					RawText:<br />
					<textarea name="RawText" id="rawtext"></textarea>
				</p>

				<p>
					UserName:<br />
					<input type="text" name="UserName" value="<?php echo $username; ?>" />
				</p>

				<p>
					UserPassword:<br />
					<input type="password" name="UserPassword" value="<?php echo $userpassword; ?>" />
				</p>

				<p>
					<input type="submit" class="button-primary" value="Skicka" />
				</p>

			</form>

			<?php if( $debug ) { ?>
				<iframe name="valideratext_iframe" width="100%" height="100%" style="margin-left: -15px; margin-top: -2px;"></iframe>
			<?php } ?>

			<script>

				String.prototype.replaceAll = function(
						strTarget, // The substring you want to replace
						strSubString // The string you want to replace in.
						){
					var strText = this;
					var intIndexOfMatch = strText.indexOf( strTarget );
					while (intIndexOfMatch != -1){
						strText = strText.replace( strTarget, strSubString )
						intIndexOfMatch = strText.indexOf( strTarget );
					}
					return( strText );
				}

				jQuery(function($) {
					tb_position = function() {
						var tbWindow = $('#TB_window');
						var width = $(window).width();
						var H = $(window).height()-50;
						var W = ( 1000 < width ) ? 1000 : width;

						if ( tbWindow.size() ) {
							tbWindow.width( W - 50 ).height( H - 45 );
							$('#TB_ajaxContent').width( W - 50 ).height( H - 75 );
							tbWindow.css({'margin-left': '-' + parseInt((( W - 50 ) / 2),10) + 'px'});
							if ( typeof document.body.style.maxWidth != 'undefined' )
								tbWindow.css({'top':'20px','margin-top':'0'});
							//$('#TB_title').css({'background-color':'#fff','color':'#cfcfcf'});
						};

						return $('a.valideratext_button').each( function() {
							var href = $(this).attr('href');
							if ( ! href ) return;
							href = href.replace(/&width=[0-9]+/g, '');
							href = href.replace(/&height=[0-9]+/g, '');
							$(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
						});
					};

					jQuery('a.valideratext_button').click(function(){
						if ( typeof tinyMCE != 'undefined' &&  tinyMCE.activeEditor ) {
							tinyMCE.get('content').focus();
							tinyMCE.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark('simple');
							var txt = tinyMCE.activeEditor.getContent();
							txt = txt.replaceAll('</p>','\n\r');
							txt = txt.replaceAll('<br/>','\r');
							txt = txt.replaceAll('<br>','\r');
							txt = txt.replaceAll('<br />','\r');
							txt = txt.replace(/<\/?[^>]+>/gi,'');
							document.getElementById('rawtext').value = txt;
							<?php if( !$debug ) { ?>
								setTimeout('document.valideraform.submit();',100);
								jQuery('#valideraform').hide();
								<?php
								}
							 	else{
							 		?>
									jQuery('#valideraform').show();
									<?php
							 	}?>
						}
					});

					$(window).resize( function() { tb_position() } );
				});

			</script>


		</div>


		<?php
	}


}

new ValideraText_Media_Button();

?>