<?php
# JWTSSO Plugin - sso.php
#
require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'user_api.php' );

use \Firebase\JWT\JWT;

$jsonWebToken = gpc_get_string('token',null);

$f_return = gpc_get_string('return');
$tx = explode('?',$f_return);
if( count($tx) > 0  && null == $jsonWebToken ) {
  $px = explode('=',$tx[1]);
  foreach($px as $pdx => $pp ) {
    if( $pp == 'token' ) {
      $jsonWebToken = $px[$pdx+1];
      break;
    }
  }
}

login( $jsonWebToken );

/**
 *
 */
function getUserName($jwt) {
  $username = '';
  if($jwt != '') {
    $matahari = JWTSSOPlugin::getSecretKey();
    try {
      $plain = JWT::decode($jwt, $matahari,array(JWTSSOPlugin::ALGORITHM),
                               null, array('typ' => JWTSSOPlugin::TYP));
    } catch (Exception $e) {
      echo __METHOD__ . ' :: Caught exception: ',  $e->getMessage(), "\n";
      echo '<pre>';
      var_dump($e);
      echo '</pre>';
      die();        
    }

    if( property_exists($plain, 'username') ) {
      $username = $plain->username; 
    }
  }
  return $username;
}


/**
 *
 */
function login($jwt) {

    // Need to understand if session already exists and belogns 
    // to same user name I'm extracting from JWT
    // If is not the same, then I need to logout old one before
    // to login the new
    $f_username = getUserName($jwt);
    $cuid = authGetCurrentUserID();
    if( $cuid > 0 ) {
      $currentUserName = user_get_username($cuid); 
      if( strcasecmp($f_username, $currentUserName)  != 0 ){
          auth_logout();
      }
    }

    $f_reauthenticate = gpc_get_bool( 'reauthenticate', false );
    $f_return = gpc_get_string( 'return', config_get( 'default_home_page' ) );
    $t_return = string_url( string_sanitize_url( $f_return ) );
    $t_user_id = is_blank( $f_username ) ? false : user_get_id_by_name( $f_username );

    if( $t_user_id == false ) {
      $t_query_args = array(
        'error' => 1,
        'username' => $f_username,
      );

      if( !is_blank( 'return' ) ) {
        $t_query_args['return'] = $t_return;
      }

      if( $f_reauthenticate ) {
        $t_query_args['reauthenticate'] = 1;
      }

      $t_query_text = http_build_query( $t_query_args, '', '&' );
      $t_uri = auth_login_page( $t_query_text );
      print_header_redirect( $t_uri );
    } 

    // Seems Good!!
    auth_login_user( $t_user_id );

    # Redirect to original page user wanted to access before authentication
    if( !is_blank( $t_return ) ) {
      print_header_redirect( 'login_cookie_test.php?return=' . $t_return );
    }

    # If no return page, redirect to default page
    print_header_redirect( config_get( 'default_home_page' ) );
}

/**
 *
 */
function authGetCurrentUserID() {
   
    $t_cookie_string = auth_get_current_user_cookie();
  if( $t_result = user_search_cache( 'cookie_string', $t_cookie_string ) ) {
    $t_user_id = (int)$t_result['id'];
    current_user_set( $t_user_id );
    return $t_user_id;
  }

  # @todo error with an error saying they aren't logged in? Or redirect to the login page maybe?
  db_param_push();
  $t_query = 'SELECT id FROM {user} WHERE cookie_string=' . db_param();
  $t_result = db_query( $t_query, array( $t_cookie_string ) );

  $t_user_id = (int)db_result( $t_result );
  return $t_user_id;
}