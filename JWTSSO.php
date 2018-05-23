<?php
/**
 * JSON Web Token (JWT) Auth plugin
 *
 * https://tools.ietf.org/html/rfc7519
 *
 */

/**
 * give a look to:
 * https://getcomposer.org/doc/01-basic-usage.md#introduction
 * https://getcomposer.org/doc/01-basic-usage.md#autoloading 
 */
require __DIR__ . '/vendor/autoload.php';

class JWTSSOPlugin extends MantisPlugin  {

    const FOR_REDIRECT = true;
    const ALGORITHM = 'HS256'; 
    const TYP= 'JWT';

    /**
     * A method that populates the plugin information and minimum requirements.
     * @return void
     */
    function register() {
        $this->name = 'JWTSSO';
        $this->description = 'JSON Web Token Authentication';
        $this->page = 'config.php';

        $this->version = '1.0.20180420';
        $this->requires = array(
            'MantisCore' => '2.10.0',
        );

        $this->author = 'Francisco Mancardi';
        $this->contact = 'francisco.mancardi@tesisquare.com';
        $this->url = 'http://www.tesisquare.com';
    }

    /**
     * plugin init
     * @return void
     */
    function init() {
        plugin_require_api( 'core/JWTUtils.class.php' );
    }

    /**
     *
     */
    static function getSecretKey() {
        $cfg = self::getConfigOptions();
        $default = $cfg['input']['text']['secret_key']['default'];
        $val = plugin_config_get( 'secret_key', $default,false, NO_USER, ALL_PROJECTS );

        return $val; 
    } 
    /**
     * plugin hooks
     * @return array
     */
    function hooks() {
        $t_hooks = array(
            'EVENT_AUTH_USER_FLAGS' => 'auth_user_flags',
        );

        return $t_hooks;
    }

    /**
     *  
     */
    static function getConfigOptions( ) {
        $cfg = array();
        $cfg['input'] = array('text' => array());

        $cfg['input']['text']['secret_key'] = array(
            'default' => 'put-your-secret-key-here',
            'opt' => array('lbl' => 'secret_key', 'maxlenght' => 50, 'size' => '50')
            );

        $cfg['forms'] = array('config_edit' => 'plugin_JWTSSO_config_edit');
        return $cfg;
    }    
    /**
     *  
     */
    function auth_user_flags( $p_event_name, $p_args ) {
        $t_username = $p_args['username'];
        $t_user_id = $p_args['user_id'];

        $t_flags = new AuthFlags();

        # To Allow standard MantisBT login, I will check the caller
        # if it was passed to me.
        if( isset($p_args['request_uri']) ) {

            // https://en.wikipedia.org/wiki/The_Birds_(film)
            // cut on '?'
            $t_birds = explode('?',$p_args['request_uri']);

            // do we have been called by plugin.php?
            $t_TippiHedren = explode('/',$t_birds[0]);
            if( end( $t_TippiHedren ) == 'plugin.php' ) {
               // need to get effective page
               $t_Daphne = explode('&',$t_birds[1]);
               $t_qstring = $t_Daphne[1];

               $t_Daphne = explode('/',$t_Daphne[0]);
               $t_page = $t_Daphne[1];
            } else {
                $t_dummy = substr(strrchr($p_args['request_uri'], '/'), 1);
                $t_dummy = explode('?',$t_dummy);
                $t_page = $t_dummy[0];
                $t_qstring = $t_dummy[1];
            }

           
            switch( $t_page ) {
                case 'index.php':
                   // Go to standard page to ask for username
                   $t_flags->setLoginPage('login_page.php');
                break;

                case 'bug_report_page.php':
                case 'bug_report_page':
                case 'view_all_bug_page.php':
                case 'view_all_bug_page':
                   // Add here more pages that can be called with token
                   $t_url = plugin_page( 'login_page_sso', self::FOR_REDIRECT );
                   $t_flags->setLoginPage( $t_url );
                break;              

                case 'login_page_sso.php':
                case 'login_page_sso':
                   $t_url = plugin_page( 'sso', self::FOR_REDIRECT );
                   $t_url = helper_url_combine($t_url,$t_qstring);
                   $t_flags->setCredentialsPage($t_url);
                break;              
            }
        }
        return $t_flags;
    }
}