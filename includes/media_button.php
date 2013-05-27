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
		$cache_stamp = date('His');
		$image_btn = WP_PLUGIN_URL . '/valideratext/images/valideratext.png';
		$out       = '<a href="#TB_inline?width=900&inlineId=popup_valideratext&cache=' . $cache_stamp . '" class="thickbox button valideratext_button" title=""><img src="' . $image_btn . '" alt="' . __( 'Validera din text', 'valideratext' ) . '" /> Valideratext&nbsp;&nbsp;</a>';
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

				<table class="widefat">
					<tr>
						<td>
							AddInName:
						</td>
						<td>
							<input type="text" name="AddInName" value="<?php echo $addinname; ?>" />
						</td>
						<td>
							AddInPublisher:
						</td>
						<td>
							<input type="text" name="AddInPublisher" value="<?php echo $addinpublisher; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							AddInVersion:
						</td>
						<td>
							<input type="text" name="AddInVersion" value="<?php echo $addinversion; ?>" />
						</td>
						<td>
							ApplicationName:
						</td>
						<td>
							<input type="text" name="ApplicationName" value="<?php echo $applicationname; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							ApplicationVersion:
						</td>
						<td>
							<input type="text" name="ApplicationVersion" value="<?php echo $applicationversion; ?>" />
						</td>
						<td>
							RawText:
						</td>
						<td>
							<textarea name="RawText" id="rawtext" rows="10" cols="60"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							UserName:
						</td>
						<td>
							<input type="text" name="UserName" value="<?php echo $username; ?>" />
						</td>
						<td>
							UserPassword:
						</td>
						<td>
							<input type="password" name="UserPassword" value="<?php echo $userpassword; ?>" />
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
							<input type="submit" class="button-primary" value="Skicka" />
						</td>
						<td>
							Cache:
						</td>
						<td>
							<input type="text" name="cache" value="<?php echo $cache_stamp; ?>" />
						</td>
					</tr>
				</table>

			</form>

			<?php if( $debug ) { ?>
				<iframe name="valideratext_iframe" width="800px" height="700px" style="margin-left: -15px; margin-top: -2px;"></iframe>
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
						var H = $(window).height()-20;
						var W = ( 940 < width ) ? 940 : width;

						if ( tbWindow.size() ) {
							tbWindow.width( W - 80 ).height( H - 100 );
							$('#TB_ajaxContent').width( W - 80 ).height( H-140 );
							tbWindow.css({'margin-left': '-' + parseInt((( W - 50 ) / 2),10) + 'px'});
							if ( typeof document.body.style.maxWidth != 'undefined' )
								tbWindow.css({'top':'40px','margin-top':'10','background-color':'#c7c7c7'});
							$('#TB_ajaxContent').css({'margin-top':'10px','overflow':'hidden'});
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