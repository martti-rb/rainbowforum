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
class acp_botscout_and_stop_forum_spam_info
{
    function module()
    {
    return array(
        'filename'    => 'acp_botscout_and_stop_forum_spam',
        'title'        => 'ACP_BOTSCOUT_AND_STOP_FORUM_SPAM',
        'version'    => '1.0.0',
        'modes'        => array(
            'botscout_and_stop_forum_spam'        => array('title' => 'ACP_BOTSCOUT_AND_STOP_FORUM_SPAM', 'auth' => 'acl_a_board', 'cat' => array('ACP_CAT_DOT_MODS')),
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