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
class acp_location_tags_info
{
    function module()
    {
		return array(
			'filename'    => 'acp_location_tags',
			'title'        => 'ACP_LOCATION_TAGS',
			'version'    => '1.0.0',
			'modes'        => array(
				'location_tags'        => array('title' => 'ACP_LOCATION_TAGS', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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