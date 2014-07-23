<?php 
/*

phpbb3 botscout.com and stopforumspam.com API query testing on registration
for ip, email addres and username

*/

/***********************************/

$log_spam_path = $phpbb_root_path . '/store/spammers.log';

/************************************/

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

/*
$botscout_api_key = 'ypxDS3md27Q6ejg';

$botscout_ip_max = 0;
$botscout_email_max = 0;
$botscout_username_max = 20;

$stopforumspam_ip_max = 0;
$stopforumspam_email_max = 0;
$stopforumspam_username_max = 20;
*/

/************************************/

$x_mail = urlencode($data['email']);
$x_user = urlencode($data['username']);

/************************************/

// botscout.com API query testing for an email address , IP and name

$bs_no_test = true;
$bs_fail = false;
$bs_reg_denied = false;

if ($bs_sfs['bs']['enable'])
{
	$bs_no_test = false;

	$botscout_api_query = 'http://botscout.com/test/?multi&mail=' . $x_mail . '&ip=' . $user->ip . '&name=' . $x_user;

	if ($bs_sfs['bs']['api_key'] != '')
	{
		$botscout_api_query .= '&key=' . $bs_sfs['bs']['api_key'];
	}

	$bs_data = file_get_contents($botscout_api_query);

	//check for overall success of the query.

	if(substr($bs_data, 0, 1) == '!')
	{		
		$bs_fail = true; 
		$bs_no_test = true;
	}
	else
	{
		$bs_data = explode('|', $bs_data);

		$bs_ip = $bs_data[3];
		$bs_email = $bs_data[5];
		$bs_username = $bs_data[7];
		
		if (($bs_ip > $bs_sfs['bs']['ip']) ||
			($bs_email > $bs_sfs['bs']['email']) ||
			($bs_username > $bs_sfs['bs']['username']))
		{
			$bs_reg_denied = true;
		}
	}
}

if ($bs_no_test)
{
		$bs_ip = 'nt';
		$bs_email = 'nt';
		$bs_username = 'nt';
}

/******************************************************/

/* 

stopforumspam.com API query testing for an email address , IP and name

*/

$sfs_no_test = true;
$sfs_fail = false;
$sfs_reg_denied = false;

if ($bs_sfs['sfs']['enable'])
{
	$sfs_no_test = false;

	$stopforumspam_api_query = 'http://www.stopforumspam.com/api?f=json&email=' . $x_mail . '&ip=' . $user->ip . '&username=' . $x_user;		

	$sfs_returned_data = file_get_contents($stopforumspam_api_query);
		
	$sfs_data = json_decode($sfs_returned_data, true );

	//check for overall success of the query.
	   
	if (!(is_array($sfs_data) && isset($sfs_data['success']) && $sfs_data['success']))
	{
		$sfs_no_test = true;
		$sfs_fail = true; 
	}
	else
	{
		$sfs_ip = $sfs_data['ip']['frequency'];
		$sfs_email = $sfs_data['email']['frequency'];
		$sfs_username = $sfs_data['username']['frequency'];
		
		if (($sfs_ip > $bs_sfs['sfs']['ip']) ||
			($sfs_email > $bs_sfs['sfs']['email']) ||
			($sfs_username > $bs_sfs['sfs']['username']))
		{
			$sfs_reg_denied = true;
		}
	}

}

if ($sfs_no_test)
{
		$sfs_ip = 'nt';
		$sfs_email = 'nt';
		$sfs_username = 'nt';
}

/****************************************************************************/

// write a logline if needed

if ($bs_fail || $sfs_fail || $bs_reg_denied || $sfs_reg_denied)
{
	$logline = time() . ' ' . gmdate("M d Y H:i:s");

	// log API query fails 
	
	$logline .= ($bs_fail) ? ' bs error: ' . $bs_data : '';
	$logline .= ($sfs_fail) ? ' sfs error: no valid return' : '';
	
	if ($bs_reg_denied || $sfs_reg_denied)
	{
		// deny acces and log spammer
		
		$logline .= ' ip: ' . $user->ip . ' bs : ' . $bs_ip . ' sfs: ' . $sfs_ip;
		$logline .= ' email: ' . $x_mail . ' bs: ' . $bs_email . ' sfs: ' . $sfs_email;
		$logline .= ' username: ' . $x_user . ' bs: ' . $bs_username . ' sfs: ' . $sfs_username;
		$logline .= "\n";	
		
		$error_string = 'registration denied. reason : ';
		$error_string .= ($bs_reg_denied) ? ' bs anti-spam' : '';
		$error_string .= ($sfs_reg_denied) ? ' sfs anti-spam' : '';

		$error[] = $error_string;	
	}	

	if ($fp = fopen($log_spam_path, 'a'))
	{
		fwrite($fp, $logline);
		fclose($fp);
	}	
}

/*****************************************************************************/


?>