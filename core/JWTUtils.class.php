<?php

use \Firebase\JWT\JWT;

class JWTUtils {


	/**
	 *
	 */
	static function loginCheckWithRelogin($jwt = null) {

	  if( null == $jwt ) {
    	$jwt = gpc_get_string('token',null);
	  }	

	  $uname = user_get_username(auth_get_current_user_id()); 
	  $unameOnJWT = self::getLoginName($jwt);
	  if( strcasecmp($uname, $unameOnJWT)  != 0 ){
	    auth_logout();
	    $t_user_id = user_get_id_by_name( $unameOnJWT );
	    auth_login_user( $t_user_id );
	  } 
	} 

	/**
	 *
	 */
	static function getLoginName($jwt) {
	  $username = '';
	  list($header, $payload, $signature) = explode (".", $jwt);
	  $plain = json_decode(base64_decode($payload));
	  $prop = 'username';
	  if( property_exists($plain, $prop) ) {
	     $username = $plain->$prop; 
	  }
	  return $username;

    /*
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
	    */
	    
	}

  /**
   *
   */
  static function getCustomFields($jwt) {
      $cfSet = '';

      //echo __METHOD__;
      //die();

      /*
      Useful for debug without signature verification
        https://github.com/firebase/php-jwt/issues/68
        */
      list($header, $payload, $signature) = explode (".", $jwt);
      print base64_decode($payload);
      $plain = json_decode(base64_decode($payload));
      
      //echo __FUNCTION__;
      //var_dump($plain);
      //die();
      if( property_exists($plain, 'additionalData') ) {
        $cfSet = $plain->additionalData; 
      }
        
        /*
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

          if( property_exists($plain, 'additionalData') ) {
            $cfSet = $plain->additionalData; 
          }
      }
      */

      return $cfSet;
  }
}