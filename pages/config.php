<?php
# Mantis Plugin "JWTSSO"
# Copyright (C) 2018 TESISQUARE 
#
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

layout_page_header( plugin_lang_get( 'title' ) );

layout_page_begin( 'manage_overview_page.php' );

print_manage_menu( 'manage_plugin_page.php' );

$cfg = JWTSSOPlugin::getConfigOptions();
$t_page_key = basename(__FILE__, '.php') . '_edit';
$f_security = $cfg['forms'][$t_page_key];

?>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" >

<form id="formatting-config-form" action="<?php echo plugin_page( 'config_edit' )?>" method="post">
    <?php echo form_security_field( $f_security ) ?>

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
    <h4 class="widget-title lighter">
        <i class="ace-icon fa fa-medkit"></i>
        <?php echo plugin_lang_get( 'title' ) . ': ' . plugin_lang_get( 'config' )?>
    </h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">

<table class="table table-bordered table-condensed table-striped" >

<?php
foreach( $cfg['input']['text'] as $op => $elem ) {

    $val = plugin_config_get( $op, $elem['default'],false, NO_USER, ALL_PROJECTS );
    helperMethodsPlugin::drawInputTextRow( $op, $val, $elem['opt']); 
}
?>
</table>
</div>
</div>
    <div class="widget-toolbox padding-8 clearfix">
        <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'btn_save' )?>" />
        <a class="btn btn-primary btn-white btn-round btn-sm" 
        href="<?php echo plugin_page( 'generate_sample_token' );?>"><?php echo plugin_lang_get('config_test')?></a>
    </div>
</div>
</div>
</form>
</div>
</div>

<?php
layout_page_end();
