<?php
/**
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Minimum Requirement: PHP 4.3.3
*/

define('IN_PHPBB', true);

if (file_exists('./config.php')
{
	require('./config.php');
}

require('./includes/constants.php');
require('./includes/db/' . $dbms . '.php');

$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, defined('PHPBB_DB_NEW_LINK') ? PHPBB_DB_NEW_LINK : false);
unset($dbpasswd);

echo('tuuutututuuutuutuutut');


?>