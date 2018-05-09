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
          echo 'Caught exception: ',  $e->getMessage(), "\n";
          var_dump($e);
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

    $f_username = getUserName($jwt);
  
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