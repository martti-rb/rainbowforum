<?php
/**
*
* @package acp
* @version $Id$
* @copyright (c) 2005 phpBB Group / 2011 Zmarty
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/

class acp_botscout_and_stop_forum_spam
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_botscout_and_stop_forum_spam');
        $this->tpl_name     = 'acp_botscout_and_stop_forum_spam';
        $this->page_title   = 'ACP_BOTSCOUT_AND_STOP_FORUM_SPAM';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{
			$bs_sfs = array(
				'bs'	=> array(
					'enable'	=>	request_var('bs_enable', 0),
					'api_key'	=> 	request_var('bs_api_key', ''),
					'ip'		=>	request_var('bs_ip', 0),
					'email'		=> 	request_var('bs_email', 0),
					'username'	=>	request_var('bs_username', 50),
					),
				'sfs'	=> array(
					'enable'	=>	request_var('sfs_enable', 0),
					'api_key'	=> 	request_var('sfs_api_key', ''),
					'ip'		=>	request_var('sfs_ip', 0),
					'email'		=> 	request_var('sfs_email', 0),
					'username'	=>	request_var('sfs_username', 50),
					),
				);
			
			set_config('bs_sfs', serialize($bs_sfs));		
		}

		$bs_sfs = unserialize($config['bs_sfs']);
		
		if (!$bs_sfs)
		{
			$bs_sfs = array(
				'bs'	=> array(
					'enable'	=>	0,
					'api_key'	=> 	'',
					'ip'		=>	0,
					'email'		=> 	0,
					'username'	=>	50,
					),
				'sfs'	=> array(
					'enable'	=>	0,
					'api_key'	=> 	'',
					'ip'		=>	0,
					'email'		=> 	0,
					'username'	=>	50,
					),
				);
		}
		

		$template->assign_vars(array(		
			'S_BOTSCOUT_ENABLE'				=> $bs_sfs['bs']['enable'] ? true : false,
			
			'BS_VALUE_API_KEY'				=> $bs_sfs['bs']['api_key'],	
			'BS_VALUE_IP'					=> $bs_sfs['bs']['ip'],	
			'BS_VALUE_EMAIL'				=> $bs_sfs['bs']['email'],	
			'BS_VALUE_USERNAME'				=> $bs_sfs['bs']['username'],	

			'S_STOP_FORUM_SPAM_ENABLE'		=> $bs_sfs['sfs']['enable'] ? true : false,
			
			'SFS_VALUE_API_KEY'				=> $bs_sfs['sfs']['api_key'],	
			'SFS_VALUE_IP'					=> $bs_sfs['sfs']['ip'],	
			'SFS_VALUE_EMAIL'				=> $bs_sfs['sfs']['email'],	
			'SFS_VALUE_USERNAME'			=> $bs_sfs['sfs']['username'],				
			
			'S_UPDATED'							=> $submit,
			'S_CALENDAR_INPUT_FORUMS_OPTIONS'	=> make_forum_select($select_ids, $ignore_ids, false, false, false, false, false),
			'U_ACTION'							=> $this->u_action,
			'L_TITLE'							=> $user->lang[$this->page_title],			
			));	
    }

}

?>