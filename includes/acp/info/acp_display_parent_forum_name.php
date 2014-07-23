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
class acp_display_parent_forum_name_info
{
    function module()
    {
		return array(
			'filename'    => 'acp_display_parent_forum_name',
			'title'        => 'ACP_DISPLAY_PARENT_FORUM_NAME',
			'version'    => '1.0.0',
			'modes'        => array(
				'display_parent_forum_name'        => array('title' => 'ACP_DISPLAY_PARENT_FORUM_NAME', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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