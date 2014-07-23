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
class acp_topic_color_info
{
    function module()
    {
    return array(
        'filename'    => 'acp_topic_color',
        'title'        => 'ACP_TOPIC_COLOR_SETTINGS',
        'version'    => '1.0.0',
        'modes'        => array(
            'topic_color'        => array('title' => 'ACP_TOPIC_COLOR_SETTINGS', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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