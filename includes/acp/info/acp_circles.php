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
class acp_circles_info
{
    function module()
    {
		return array(
			'filename'    => 'acp_circles',
			'title'        => 'ACP_CIRCLES',
			'version'    => '1.0.0',
			'modes'        => array(
				'circles'        => array('title' => 'ACP_CIRCLES', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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