<?php
# Mantis Plugin "JWTSSO"
# Copyright (C) 2018 TESISQUARE
#
$cfg = JWTSSOPlugin::getConfigOptions();

$t_page_key = basename(__FILE__,'.php');
$t_fname = $cfg['forms'][$t_page_key];
form_security_validate( $t_fname );

auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_field = array();

$cfg = $cfg['input']['text'];
foreach( $cfg as $key => $elem ) {
	$f_field[$key] = gpc_get_string( $key, $elem['default']);
	plugin_config_set($key, $f_field[$key], NO_USER, ALL_PROJECTS );	
}
form_security_purge( $t_fname );


print_successful_redirect( plugin_page( 'config', true ) );
