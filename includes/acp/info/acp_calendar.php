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
class acp_calendar_info
{
    function module()
    {
    return array(
        'filename'    => 'acp_calendar',
        'title'        => 'ACP_CALENDAR_SETTINGS',
        'version'    => '1.0.0',
        'modes'        => array(
            'calendar'        => array('title' => 'ACP_CALENDAR_SETTINGS', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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