<?php

class ValideraText_Ajax {

	function __construct() {

		add_action( 'wp_ajax_valideratext', array( &$this, 'ajax' ) );

	}


	function ajax(){

		$options = get_option( 'valideratext_general' );

		if( !$options ){
			wp_die( 'Modulen för Valideratext behöver ställas in. Det görs i WordPress av administratören under "Inställningar" och "Valideratext".', 'Inga inställningar för Valideratext' );
		}

		$apiurl = $options['apiurl'];
		$username = $options['username'];
		$userpassword = $options['userpassword'];
		$applicationname = "WordPress";
		$applicationversion = "3.5.1";
		$addinpublisher = "Flowcom AB";
		$addinname = "valideratext";
		$addinversion = "1.0";

		$user = wp_get_current_user();

		$valideratext_username = get_user_meta( $user->ID, 'valideratext_username', true);
		$valideratext_password = get_user_meta( $user->ID, 'valideratext_password', true);
		if( !empty( $valideratext_username ) ) $username = $valideratext_username;
		if( !empty( $valideratext_password ) ) $userpassword = $valideratext_password;

		?>

		<html>
			<body>
				<img src="<?php echo WP_PLUGIN_URL; ?>/valideratext/images/ajaxloader.gif" />
				<form style="display:none;" method="POST" name="valideraform" id="valideraform" action="<?php echo $apiurl; ?>">
					<input type="hidden" name="AddInName" value="<?php echo $addinname; ?>" />
					<input type="hidden" name="AddInPublisher" value="<?php echo $addinpublisher; ?>" />
					<input type="hidden" name="AddInVersion" value="<?php echo $addinversion; ?>" />
					<input type="hidden" name="ApplicationName" value="<?php echo $applicationname; ?>" />
					<input type="hidden" name="ApplicationVersion" value="<?php echo $applicationversion; ?>" />
					<textarea name="RawText" id="rawtext"></textarea>
					<input type="hidden" name="UserName" value="<?php echo $username; ?>" />
					<input type="hidden" name="UserPassword" value="<?php echo $userpassword; ?>" />
				</form>
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

					var txt = opener.tinyMCE.activeEditor.getContent();
					txt = txt.replaceAll('</p>','\n\r');
					txt = txt.replaceAll('<br/>','\r');
					txt = txt.replaceAll('<br>','\r');
					txt = txt.replaceAll('<br />','\r');
					txt = txt.replace(/<\/?[^>]+>/gi,'');
					document.getElementById('rawtext').value = txt;
					setTimeout('document.valideraform.submit();',100);

				</script>
			</body>
		</html>

		<?php

		exit;

	}


}

new ValideraText_Ajax();