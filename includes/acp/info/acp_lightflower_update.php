<?php
/** 
*
* @package acp
* @version $Id$
* @copyright (c) 2007 phpBB Group / 2011 Zmarty 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
                            
/**
* @package module_install
*/
class acp_lightflower_update_info
{
    function module()
    {
    return array(
        'filename'    => 'acp_lightflower_update',
        'title'        => 'ACP_LIGHTFLOWER_UPDATE',
        'version'    => '1.0.0',
        'modes'        => array(
            'lightflower_update'        => array('title' => 'ACP_LIGHTFLOWER_UPDATE', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
            ),
        );
        
    }
                            
    function install()
    {
    }
                                
    function uninstall()
    {
    }

}
?>