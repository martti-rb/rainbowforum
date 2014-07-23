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
class acp_map_info
{
    function module()
    {
		return array(
			'filename'    => 'acp_map',
			'title'        => 'ACP_MAP',
			'version'    => '1.0.0',
			'modes'        => array(
				'archive'        => array('title' => 'ACP_MAP', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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