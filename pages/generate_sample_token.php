<?php
# JWTSSO Plugin - generate_sample_token.php
#
use \Firebase\JWT\JWT;

$jwtIn = new stdClass();
$jwtIn->secretKey = JWTSSOPlugin::SECRETKEY;
$jwtIn->payload = array( "username" => "Hitchcock" );
$jwtIn->algorithm = array(JWTSSOPlugin::ALGORITHM);
$jwtIn->header = array( "typ" => JWTSSOPlugin::TYP );

echo "<br>\Firebase\JWT\JWT Poor's man Test<br>";
echo "<br>Going To Create JSON Web Token using following setup";
echo '<pre>'; var_dump($jwtIn); echo '</pre>';

// encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null)
$jwt = JWT::encode($jwtIn->payload, $jwtIn->secretKey, 
	               $jwtIn->algorithm[0], null, $jwtIn->header);

echo '<br>Check trying to decode this:<br>'; 
echo $jwt .'<br><br>';
echo 'using: <a href="https://jwt.io/#debugger-io">JWT.io site</a><br>';
echo '<br>';
echo '<br>';

echo 'Now decode what we have generated<br>';
$decoded = JWT::decode($jwt, $jwtIn->secretKey, $jwtIn->algorithm);

echo '<pre>';
print_r($decoded);
echo '</pre>';
