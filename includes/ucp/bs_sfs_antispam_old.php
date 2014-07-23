<?php 
/*

phpbb3 botscout.com and stopforumspam.com API query testing on registration
for ip, email addres and username

*/

/***********************************/

$log_spam_path = $phpbb_root_path . '/store/spammers.log';

/************************************/

$bs_sfs = unserialize(config['bs_sfs']);

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

unset($log_line);

// botscout.com API query testing for an email address , IP and name

if ($bs_sfs['bs']['enable'])
{
	$botscout_api_query = 'http://botscout.com/test/?multi&mail=' . $x_mail . '&ip=' . $user->ip . '&name=' . $x_user;

	if ($bs_sfs['bs']['api_key'] != '')
	{
		$botscout_api_query .= '&key=' . $bs_sfs['bs']['api_key'];
	}

	$bs_data = file_get_contents($botscout_api_query);




/* 
botscout sample 'MULTI' return string (standard API, not XML)
Y|MULTI|IP|4|MAIL|26|NAME|30

$botscout_data[0] - 'Y' if found in database, 'N' if not found, '!' if an error occurred 
$botscout_data[1] - type of test (will be 'MAIL', 'IP', 'NAME', or 'MULTI') 
$botscout_data[2] - descriptor field for item (IP)
$botscout_data[3] - how many times the IP was found in the database 
$botscout_data[4] - descriptor field for item (MAIL)
$botscout_data[5] - how many times the EMAIL was found in the database 
$botscout_data[6] - descriptor field for item (NAME)
$botscout_data[7] - how many times the NAME was found in the database 
*/

//check for overall success of the query.

	if(substr($bs_returned_data, 0, 1) == '!')
	{	
		$bs_fail = 1; 
		$bs_ip_freq = 0;
		$bs_email_freq = 0;
		$bs_username_freq = 0;
		$bs_reg_denied = 0;
	}
	else
	{
		$bs_data = explode('|', $bs_returned_data);

		$bs_fail = 0; 
		$bs_ip_freq = $bs_data[3];
		$bs_email_freq = $bs_data[5];
		$bs_username_freq = $bs_data[7];
		
		if (($bs_ip_freq > $bs_sfs['bs']['ip']) ||
			($bs_email_freq > $bs_sfs['bs']['email']) ||
			($bs_username_freq > $bs_sfs['bs']['username']))
		{
			$bs_reg_denied = 1;
		}
		else
		{
			$bs_reg_denied = 0;
		}	
	}

}

/******************************************************/

/* 

stopforumspam.com API query testing for an email address , IP and name

Form an url to stopforumspam.com, set the flag for json format,
and add parameters for username= , ip= , and email=
*/

    

$stopforumspam_api_query = 'http://www.stopforumspam.com/api?f=json&email=' . $x_mail . '&ip=' . $user->ip . '&username=' . $x_user;		

$stopforumspam_returned_data = file_get_contents($stopforumspam_api_query);
    
$stopforumspam_data = json_decode($stopforumspam_returned_data, true );

/* 
The result will be an array structured like this:

[
    'success' => 1
    'username' => [ 'frequency' = x, 'appears' = 0 ]
   'email' => [ 'frequency' = x, 'appears' = 0 ]
    'ip' => [ 'frequency' = x, 'appears' = 0 ]
]

the 'appears' field will be set to 1 or true if it appears in the spam database.
*/


//check for overall success of the query.
   
if (!(is_array($stopforumspam_data) && isset($stopforumspam_data['success']) && $stopforumspam_data['success']))
{
	$sfs_fail = 1; 
	$sfs_ip_freq = 0;
	$sfs_email_freq = 0;
	$sfs_username_freq = 0;
	$sfs_reg_denied = 0;
}
else
{
	$sfs_fail = 0; 
	$sfs_ip_freq = $stopforumspam_data['ip']['frequency'];
	$sfs_email_freq = $stopforumspam_data['email']['frequency'];
	$sfs_username_freq = $stopforumspam_data['username']['frequency'];
	
	if (($sfs_ip_freq > $stopforumspam_ip_max) ||
		($sfs_email_freq > $stopforumspam_email_max) ||
		($sfs_username_freq > $stopforumspam_username_max))
	{
		$sfs_reg_denied = 1;
	}
	else
	{
		$sfs_reg_denied = 0;
	}	
}

/****************************************************************************/

// write a logline if needed

if ($bs_fail || $sfs_fail || $bs_reg_denied || $sfs_reg_denied)
{
	$logline = time() . ' ' . gmdate("M d Y H:i:s");

	// log API query fails 
	
	$logline .= ($bs_fail) ? ' bs error: ' . $botscout_returned_data : '';
	$logline .= ($sfs_fail) ? ' sfs error: no valid return' : '';
	
	if ($bs_reg_denied || $sfs_reg_denied)
	{
		// deny acces and log spammer
		
		$logline .= ' ip: ' . $user->ip . ' bs : ' . $bs_ip_freq . ' sfs: ' . $sfs_ip_freq;
		$logline .= ' email: ' . $x_mail . ' bs: ' . $bs_email_freq . ' sfs: ' . $sfs_email_freq;
		$logline .= ' username: ' . $x_user . ' bs: ' . $bs_username_freq . ' sfs: ' . $sfs_username_freq;
		$logline .= "\n";	
		
		$error_string = 'registration denied. reason : ';
		$error_string .= ($bs_reg_denied) ? 'bs ' : '';
		$error_string .= ($sfs_reg_denied) ? 'sfs ' : '';

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
