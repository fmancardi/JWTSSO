<?php
# JWTSSO Plugin - login_page_sso.php
#
require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'current_user_api.php' );
require_api( 'database_api.php' );
require_api( 'gpc_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'string_api.php' );
require_api( 'user_api.php' );
require_api( 'utility_api.php' );

$f_return = string_sanitize_url( gpc_get_string( 'return', '' ) );

# Need to understand if this have any sense.
# Code has been copied from login_password_page.php
#
# If user is already authenticated 
if( auth_is_user_authenticated() ) {
	# If return URL is specified redirect to it; otherwise use default page
	if( !is_blank( $f_return ) ) {
		print_header_redirect( $f_return, false, false, true );
	} else {
		print_header_redirect( config_get_global( 'default_home_page' ) );
	}
}

$t_query_text = $f_return;
$t_redirect_url = auth_credential_page( $t_query_text, null, null );
print_header_redirect( $t_redirect_url );
