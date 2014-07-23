<?php
/**
*
* common [English]
*
* @package language
* @version $Id: common.php 10441 2010-01-25 15:57:10Z nickvergessen $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(

// Calendar Mod - Begin	

	'CALENDAR'					=> 'calendar',
	'CALENDAR_EXPLAIN'			=> 'calendar',
	'CALENDAR_TITLE'			=> 'calendar',  
	
	'CALENDAR_ADD_PERIOD'		=> 'add another Period',   
	'CALENDAR_PERIOD_FROM'		=> 'calendar period from',
	'CALENDAR_N_PERIOD_FROM'	=> '%s period from',
	'CALENDAR_TO'				=> 'to',

	'CALENDAR_SECOND'			=> 'nd',
	'CALENDAR_THIRD'			=> 'rd',
	'CALENDAR_FOURTH_PLUS'		=> 'th',
	
	'VIEWING_CALENDAR'			=> 'viewing calendar',
	
	'CALENDAR_MONTH'			=> 'month',
	'CALENDAR_DAY'				=> 'day',
	'CALENDAR_YEAR'				=> 'year',
	
	'calendar_suffix_format'	=> array(	
		'MONTH_DAY_YEAR'				=> '%1$s %2$s, %6$s',
		'MONTH_DAY_DAY_YEAR'			=> '%1$s %2$s - %5$s, %6$s',
		'MONTH_DAY_MONTH_DAY_YEAR'		=> '%1$s %2$s - %4$s %5$s, %6$s',
		'MONTH_DAY_YEAR_MONTH_DAY_YEAR'	=> '%1$s %2$s, %3$s - %4$s %5$s, %6$s'
		),
		
	'CALENDAR_SUFFIX_NOW'			=> 'now : ',
	'CALENDAR_SUFFIX_NEXT'			=> 'next : ',
	'CALENDAR_SUFFIX_LAST'			=> 'last : ',
	'CALENDAR_SUFFIX_SINGLE'		=> '',
	
	'CALENDAR_ALL_PERIODS'			=> 'all calendar periods',

	'CALENDAR_MAX_PERIODS_EXCEDED'	=> 'the number of maximum %s calendar periods is exceded.',
	'CALENDAR_DATES_WRONG_ORDER'	=> 'calendar dates are in wrong order.',
	'CALENDAR_MIN_DAYS_BETWEEN'		=> 'minimum %s day(s) are required between successive calendar periods',
	'CALENDAR_MAX_DAYS_BETWEEN'		=> 'maximum %s day(s) are allowed between successive calendar periods',
	'CALENDAR_TOO_LONG_PERIOD'		=> 'calendar period exceeds the maximum of %s days',
	
	'CALENDAR_NEW_MOON'				=> 'new moon',
	'CALENDAR_FIRST_QUARTER_MOON'	=> 'first quarter moon',
	'CALENDAR_FULL_MOON'			=> 'full moon',	
	'CALENDAR_THIRD_QUARTER_MOON'	=> 'third quarter moon',
	
	'CALENDAR_AT'					=> '@',
	
	'NO_CALENDAR_FILTER'			=> 'all topics',
	'NO_CALENDAR_TAG'				=> 'no calendar tag',
	'CALENDAR_TAG_ONLY'				=> 'calendar tag only',
	'WITH_CALENDAR_TAG'				=> 'with calendar tag',
	'CALENDAR_FILTER'				=> 'calendar',
	'ONE_CALENDAR_TAG'				=> '1 calendar tag',
	'MORE_CALENDAR_TAGS'			=> '%s calendar tags',
	
	'CALENDAR_FORUMS'				=> 'forums',

// Calendar Mod - End

// locations mod begin
	
	'LOCATION_TAG'					=> 'location tag',
	'LOCATION_TAGS'					=> 'location tags',
	'ADD_LOCATION'					=> 'add another location',
	
	'LOCATION_SECOND'				=> 'nd',
	'LOCATION_THIRD'				=> 'rd',
	'LOCATION_FOURTH_PLUS'			=> 'th',
	'LOCATION_TAG_N'				=> '%s location tag',

	'MAX_LOCATIONS_EXCEDED'			=> 'the number of maximum %s locations is exceded.',
	'LOCATION_MISMATCH'				=> 'Location mismatch.',

	'NO_LOCATION_FILTER'			=> 'all topics',
	'NO_LOCATION_TAG'				=> 'no location tag',
	'LOCATION_TAG_ONLY'				=> 'location tag only',
	'WITH_LOCATION_TAG'				=> 'with location tag',
	'LOCATION_FILTER'				=> 'location',
	'ONE_LOCATION_TAG'				=> '1 location tag',
	'MORE_LOCATION_TAGS'			=> '%s location tags',	
	
// locations mod end

// link mod begin

	'LINK_POST_LINK_TOPIC_B'	=> 'add link',
	'NO_LINK_TOPICS'			=> 'there are no links to be displayed.',
	'SEARCH_LINK_FORUM'			=> 'search links',
	'ALL_LINK_TOPICS'			=> 'all links',
	'LINK_POST_LINK_TOPIC'		=> 'post a link',
	
	'EDIT_LINK_POST'			=> 'edit link',
	'LINK_SUBJECT'				=> 'name',
	'EMPTY_LINK_SUBJECT'		=> 'you must specify a name when posting a link.',
	
	'LINK_TOPIC'				=> 'link',
	'LINK_FORUM'				=> 'Links',
	'LINK_TOPICS'				=> 'links',
	'LAST_LINK_POST'			=> 'posted',

	'LINK_TYPE_FILTER'			=> 'type',	
	'NO_LINK_TYPE'				=> 'no type tag',
	'WITH_LINK_TYPE'			=> 'with type tag',	
	
	'LINK_SOURCE_FILTER'		=> 'source',
	'NO_LINK_SOURCE'			=> 'no source tag',
	'WITH_LINK_SOURCE'			=> 'with source tag',

	'NO_LINK_FORUM'				=> 'no forum tag',
	'WITH_LINK_FORUM'			=> 'with forum tag',
	'LINK_FORUM_FILTER'			=> 'related forum',
	'RELATED_FORUM'				=> 'related forum',

	'NO_IMAGE_THUMBNAIL'		=> 'no image thumbnail',
	'WITH_IMAGE_THUMBNAIL'		=> 'with image thumbnail',
	'IMAGE_THUMBNAIL_FILTER'	=> 'image thumbnail',
	
	'LINK_NEW'					=> 'new',
	'LINK_BROKEN'				=> 'broken',
	'LINK_OBSOLETE'				=> 'obsolete',
	'LINK_STATUS'				=> 'status',
	'LINK_STATUS_FILTER'		=> 'status',
	'NO_LINK_STATUS'			=> 'no status tag',
	'WITH_LINK_STATUS'			=> 'with status tag',
	
	'SWITCH_TO_FORUM_VIEW'		=> 'switch to regular forum-view',
	'SWITCH_TO_LINK_VIEW'		=> 'switch to link-view with image thumbnails',


// link mod end

// circle mod begin

	'CIRCLE_POST_CIRCLE_TOPIC_B'	=> 'new circle',
	'NO_CIRCLES_TOPICS'				=> 'there are no circles to be displayed.',
	'SEARCH_CIRCLE_FORUM'			=> 'search circles',
	'ALL_CIRCLE_TOPICS'				=> 'all circles',
	'CIRCLE_POST_CIRCLE_TOPIC'		=> 'create a new circle',
	'EDIT_CIRCLE_POST'				=> 'edit circle',
	'CIRCLE_SUBJECT'				=> 'name',
	'EMPTY_CIRCLE_SUBJECT'			=> 'you must specify a name when creating a circle.',
	
	
	'CIRCLE_FORUM'			=> 'circles',
	'CIRCLE_TOPIC'			=> 'circle',
	'CIRCLE_LOCKED'			=> 'locked circle',
	'CIRCLE_HIDDEN'			=> 'hidden circle',
	'CIRCLE_LOCKED_HIDDEN'	=> 'hidden locked circle',
	'CIRCLE_TOPICS'			=> 'circles',
	'LAST_CIRCLE_POST'		=> 'created',

	
// circle mod end

// buttons mod begin

	'POST_TOPIC_B'		=> 'new topic',
	'FORUM_LOCKED_B'	=> 'locked',

// buttons mod end

// map mod begin

	'MAP'							=> 'map',
	'VIEWING_MAP'					=> 'viewing map',

// map mod end

// Archive Mod - Begin

	'FILTER_ARCHIVE'				=>	'filter Archive',
	'ARCHIVE_UNKNOWN'				=>	'unknown origin',
	'ARCHIVE_ALL'					=>	'all topics',
	'ARCHIVED_FROM'					=>	'archived from: ',
	'FILTER_ARCHIVED_FROM'			=>	'archived from: ',
	
//  Archive Mod - End

// Topic Title Prefix Mod - Begin

	'PREFIX_FILTER'			=> 'prefix',
	'FILTER_ON_PREFIX_TAG'	=> 'Filter on prefix tag',
	'PREFIX_TAG'			=> 'Prefix tag',
	'ALL_PREFIXES'			=> 'all topics',
	'NO_PREFIX'				=> 'no prefix',
	'WITH_PREFIX'			=> 'with prefix',
	'PREFIX_ONLY'			=> 'prefixes only',
	'OWN_PREFIXES'			=> 'own prefixes',
	'FOREIGN_PREFIXES'		=> 'foreign prefixes',
	'UNKNOWN_PREFIXES'		=> 'unknown prefixes',
	'UNKNOWN_PREFIX'		=> 'unknown prefix',
	
// Topic Title Prefix Mod - End 

// topic colors mod begin

	'COLOR'							=> 'color',
	'BG_COLOR'						=> 'background color',
	'COLOR_ID'						=> 'color id',
	'DARK'							=> 'dark',
	'COLOR_TEXT_EXAMPLE'			=> 'example',

	'EDIT_POST_B'					=> 'edit',
	'DELETE_POST_B'					=> 'X',
	'REPORT_POST_B'					=> 'R!',
	'WARN_USER_B'					=> 'W!',
	'INFORMATION_B'					=> 'I',
	'REPLY_WITH_QUOTE_B'			=> '"quote"',
	
	'PRIVATE_MESSAGE_B'				=> 'pm',
	'SEND_EMAIL_USER_B'				=> 'email',
	'WEBSITE_B'						=> 'www',
	'MSNM_B'						=> 'msnm',
	'ICQ_B'							=> 'icq',
	'YIM_B'							=> 'yahoo',
	'AIM_B'							=> 'aim',
	'JABBER_B'						=> 'jabber',
	
	
// topic colors mod end

// hide mod begin
	
	'ENABLE'				=> 'enable',
	'DISABLE'				=> 'disable',

// hide mod end

// move to archive mod - begin

	'MOVE_TO_ARCHIVE'		=> 'move to archive',
	
// move to archive mod - end	

// welcome mod begin
	
	'WELCOME'			=> 'Welcome to RainbowForum.Net',
	'WELCOME_SUB'       => 'This board is set up as an international online communication tool for the Rainbow Family of Living Light. <br/>
		It is required to be registered and logged in to view the forum categories.', 
	
// welcome mod end

// filters mod begin

	'FILTERS'			=> 'filters',

// filters mod end

// disclaimer begin

	'DISCLAIMER'		=> 'Disclaimer: this site does not contain any official statement of the Rainbow Family of Living Light. All texts and graphical representations do only represent personal points of view.', 

// disclaimer end

	'TRANSLATION_INFO'	=> '',
	'DIRECTION'			=> 'ltr',
	'DATE_FORMAT'		=> '|d M Y|',	// 01 Jan 2007 (with Relative days enabled)
	'USER_LANG'			=> 'en-gb',

	'1_DAY'			=> '1 day',
	'1_MONTH'		=> '1 month',
	'1_YEAR'		=> '1 year',
	'2_YEARS'		=> '2 years',
	'2_WEEKS'		=> '2 weeks',
	'3_MONTHS'		=> '3 months',
	'6_MONTHS'		=> '6 months',
	'7_DAYS'		=> '7 days',

	'ACCOUNT_ALREADY_ACTIVATED'		=> 'your account has already been activated.',
	'ACCOUNT_DEACTIVATED'			=> 'your account has been manually deactivated and is only able to be reactivated by an administrator.',
	'ACCOUNT_NOT_ACTIVATED'			=> 'your account has not been activated yet.',
	'ACP'							=> 'administration Control Panel',
	'ACTIVE'						=> 'active',
	'ACTIVE_ERROR'					=> 'the specified username is currently inactive. If you have problems activating your account, please contact a board administrator.',
	'ADMINISTRATOR'					=> 'administrator',
	'ADMINISTRATORS'				=> 'administrators',
	'AGE'							=> 'age',
	'AIM'							=> 'atm',
	'ALLOWED'						=> 'allowed',
	'ALL_FILES'						=> 'all files',
	'ALL_FORUMS'					=> 'all forums',
	'ALL_MESSAGES'					=> 'all messages',
	'ALL_POSTS'						=> 'all posts',
	'ALL_TIMES'						=> 'all times are %1$s %2$s',
	'ALL_TOPICS'					=> 'all topics',   // 
	'AND'							=> 'and',
	'ARE_WATCHING_FORUM'			=> 'you have subscribed to be notified of new posts in this forum.',
	'ARE_WATCHING_TOPIC'			=> 'you have subscribed to be notified of new posts in this topic.',
	'ASCENDING'						=> 'ascending',
	'ATTACHMENTS'					=> 'attachments',
	'ATTACHED_IMAGE_NOT_IMAGE'		=> 'the image file you tried to attach is invalid.',
	'AUTHOR'						=> 'author',
	'AUTH_NO_PROFILE_CREATED'		=> 'the creation of a user profile was unsuccessful.',
	'AVATAR_DISALLOWED_CONTENT'		=> 'the upload was rejected because the uploaded file was identified as a possible attack vector.',
	'AVATAR_DISALLOWED_EXTENSION'	=> 'this file cannot be displayed because the extension <strong>%s</strong> is not allowed.',
	'AVATAR_EMPTY_REMOTE_DATA'		=> 'the specified avatar could not be uploaded because the remote data appears to be invalid or corrupted.',
	'AVATAR_EMPTY_FILEUPLOAD'		=> 'the uploaded avatar file is empty.',
	'AVATAR_INVALID_FILENAME'		=> '%s is an invalid filename.',
	'AVATAR_NOT_UPLOADED'			=> 'avatar could not be uploaded.',
	'AVATAR_NO_SIZE'				=> 'the width or height of the linked avatar could not be determined. Please enter them manually.',
	'AVATAR_PARTIAL_UPLOAD'			=> 'the specified file was only partially uploaded.',
	'AVATAR_PHP_SIZE_NA'			=> 'the avatar’s filesize is too large.<br />The maximum allowed filesize set in php.ini could not be determined.',
	'AVATAR_PHP_SIZE_OVERRUN'		=> 'the avatar’s filesize is too large. The maximum allowed upload size is %1$d %2$s.<br />Please note this is set in php.ini and cannot be overridden.',
	'AVATAR_URL_INVALID'			=> 'the URL you specified is invalid.',
	'AVATAR_URL_NOT_FOUND'			=> 'the file specified could not be found.',
	'AVATAR_WRONG_FILESIZE'			=> 'the avatar’s filesize must be between 0 and %1d %2s.',
	'AVATAR_WRONG_SIZE'				=> 'the submitted avatar is %5$d pixels wide and %6$d pixels high. Avatars must be at least %1$d pixels wide and %2$d pixels high, but no larger than %3$d pixels wide and %4$d pixels high.',

	'BACK_TO_TOP'			=> 'top',
	'BACK_TO_PREV'			=> 'back to previous page',
	'BAN_TRIGGERED_BY_EMAIL'=> 'a ban has been issued on your e-mail address.',
	'BAN_TRIGGERED_BY_IP'	=> 'a ban has been issued on your IP address.',
	'BAN_TRIGGERED_BY_USER'	=> 'a ban has been issued on your username.',
	'BBCODE_GUIDE'			=> 'bbcode guide',
	'BCC'					=> 'bcc',
	'BIRTHDAYS'				=> 'birthdays',
	'BOARD_BAN_PERM'		=> 'you have been <strong>permanently</strong> banned from this board.<br /><br />Please contact the %2$sBoard Administrator%3$s for more information.',
	'BOARD_BAN_REASON'		=> 'reason given for ban: <strong>%s</strong>',
	'BOARD_BAN_TIME'		=> 'you have been banned from this board until <strong>%1$s</strong>.<br /><br />Please contact the %2$sBoard Administrator%3$s for more information.',
	'BOARD_DISABLE'			=> 'sorry but this board is currently unavailable.',
	'BOARD_DISABLED'		=> 'this board is currently disabled.',
	'BOARD_UNAVAILABLE'		=> 'sorry but the board is temporarily unavailable, please try again in a few minutes.',
	'BROWSING_FORUM'		=> 'users browsing this forum: %1$s',
	'BROWSING_FORUM_GUEST'	=> 'users browsing this forum: %1$s and %2$d guest',
	'BROWSING_FORUM_GUESTS'	=> 'users browsing this forum: %1$s and %2$d guests',
	'BYTES'					=> 'bytes',

	'CANCEL'				=> 'cancel',
	'CHANGE'				=> 'change',
	'CHANGE_FONT_SIZE'		=> 'change font size',
	'CHANGING_PREFERENCES'	=> 'changing board preferences',
	'CHANGING_PROFILE'		=> 'changing profile settings',
	'CLICK_VIEW_PRIVMSG'	=> '%sgo to your inbox%s',
	'COLLAPSE_VIEW'			=> 'collapse view',
	'CLOSE_WINDOW'			=> 'close window',
	'COLOUR_SWATCH'			=> 'colour swatch',
	'COMMA_SEPARATOR'		=> ', ',	// Used in pagination of ACP & prosilver, use localised comma if appropriate, eg: Ideographic or Arabic
	'CONFIRM'				=> 'confirm',
	'CONFIRM_CODE'			=> 'confirmation code',
	'CONFIRM_CODE_EXPLAIN'	=> 'enter the code exactly as it appears. All letters are case insensitive.',
	'CONFIRM_CODE_WRONG'	=> 'the confirmation code you entered was incorrect.',
	'CONFIRM_OPERATION'		=> 'are you sure you wish to carry out this operation?',
	'CONGRATULATIONS'		=> 'congratulations to',
	'CONNECTION_FAILED'		=> 'connection failed.',
	'CONNECTION_SUCCESS'	=> 'connection was successful!',
	'COOKIES_DELETED'		=> 'all board cookies successfully deleted.',
	'CURRENT_TIME'			=> 'it is currently %s',

	'DAY'					=> 'day',
	'DAYS'					=> 'days',
	'DELETE'				=> 'delete',
	'DELETE_ALL'			=> 'delete all',
	'DELETE_COOKIES'		=> 'delete all board cookies',
	'DELETE_MARKED'			=> 'delete marked',
	'DELETE_POST'			=> 'delete post',
	'DELIMITER'				=> 'delimiter',
	'DESCENDING'			=> 'descending',
	'DISABLED'				=> 'disabled',
	'DISPLAY'				=> 'display',
	'DISPLAY_GUESTS'		=> 'display guests',
	'DISPLAY_MESSAGES'		=> 'display messages from previous',
	
//	changed

	'DISPLAY_POSTS'			=> 'posts within',	
	'DISPLAY_TOPICS'		=> 'last post within', 
	
//	

	'DOWNLOADED'			=> 'downloaded',
	'DOWNLOADING_FILE'		=> 'downloading file',
	'DOWNLOAD_COUNT'		=> 'downloaded %d time',
	'DOWNLOAD_COUNTS'		=> 'downloaded %d times',
	'DOWNLOAD_COUNT_NONE'	=> 'not downloaded yet',
	'VIEWED_COUNT'			=> 'viewed %d time',
	'VIEWED_COUNTS'			=> 'viewed %d times',
	'VIEWED_COUNT_NONE'		=> 'not viewed yet',

	'EDIT_POST'							=> 'Edit post',
	'EMAIL'								=> 'E-mail', // Short form for EMAIL_ADDRESS
	'EMAIL_ADDRESS'						=> 'E-mail address',
	'EMAIL_INVALID_EMAIL'				=> 'The e-mail address you entered is invalid.',
	'EMAIL_SMTP_ERROR_RESPONSE'			=> 'Ran into problems sending e-mail at <strong>Line %1$s</strong>. Response: %2$s.',
	'EMPTY_SUBJECT'						=> 'You must specify a subject when posting a new topic.',
	'EMPTY_MESSAGE_SUBJECT'				=> 'You must specify a subject when composing a new message.',
	'ENABLED'							=> 'enabled',
	'ENCLOSURE'							=> 'enclosure',
	'ENTER_USERNAME'					=> 'enter username',
	'ERR_CHANGING_DIRECTORY'			=> 'unable to change directory.',
	'ERR_CONNECTING_SERVER'				=> 'Error connecting to the server.',
	'ERR_JAB_AUTH'						=> 'Could not authorise on Jabber server.',
	'ERR_JAB_CONNECT'					=> 'Could not connect to Jabber server.',
	'ERR_UNABLE_TO_LOGIN'				=> 'The specified username or password is incorrect.',
	'ERR_UNWATCHING'					=> 'An error occurred while trying to unsubscribe.',
	'ERR_WATCHING'						=> 'An error occurred while trying to subscribe.',
	'ERR_WRONG_PATH_TO_PHPBB'			=> 'The phpBB path specified appears to be invalid.',
	'EXPAND_VIEW'						=> 'expand view',
	'EXTENSION'							=> 'extension',
	'EXTENSION_DISABLED_AFTER_POSTING'	=> 'The extension <strong>%s</strong> has been deactivated and can no longer be displayed.',

	'FAQ'					=> 'faq',
	'FAQ_EXPLAIN'			=> 'frequently Asked Questions',
	'FILENAME'				=> 'filename',
	'FILESIZE'				=> 'file size',
	'FILEDATE'				=> 'file date',
	'FILE_COMMENT'			=> 'file comment',
	'FILE_NOT_FOUND'		=> 'The requested file could not be found.',
	'FIND_USERNAME'			=> 'find a member',
	'FOLDER'				=> 'folder',
	'FORGOT_PASS'			=> 'I forgot my password',
	'FORM_INVALID'			=> 'The submitted form was invalid. Try submitting again.',
	'FORUM'					=> 'forum',
	'FORUMS'				=> 'forums',
	'FORUMS_MARKED'			=> 'The selected forums have been marked read.',
	
	'FORUMS_NOT_VISIBLE'	=> 'The forums are only visible when you are registered and logged in. ',    // line added 
	
	'FORUM_CAT'				=> 'forum category',
	'FORUM_INDEX'			=> 'board index',
	'FORUM_LINK'			=> 'forum link',
	'FORUM_LOCATION'		=> 'forum location',
	'FORUM_LOCKED'			=> 'forum locked',
	'FORUM_RULES'			=> 'forum rules',
	'FORUM_RULES_LINK'		=> 'please click here to view the forum rules',
	'FROM'					=> 'from',
	'FSOCK_DISABLED'		=> 'The operation could not be completed because the <var>fsockopen</var> function has been disabled or the server being queried could not be found.',
	'FSOCK_TIMEOUT'			=> 'A timeout occurred while reading from the network stream.',

	'FTP_FSOCK_HOST'				=> 'FTP host',
	'FTP_FSOCK_HOST_EXPLAIN'		=> 'FTP server used to connect your site.',
	'FTP_FSOCK_PASSWORD'			=> 'FTP password',
	'FTP_FSOCK_PASSWORD_EXPLAIN'	=> 'password for your FTP username.',
	'FTP_FSOCK_PORT'				=> 'FTP port',
	'FTP_FSOCK_PORT_EXPLAIN'		=> 'Port used to connect to your server.',
	'FTP_FSOCK_ROOT_PATH'			=> 'path to phpBB',
	'FTP_FSOCK_ROOT_PATH_EXPLAIN'	=> 'Path from the root to your phpBB board.',
	'FTP_FSOCK_TIMEOUT'				=> 'FTP timeout',
	'FTP_FSOCK_TIMEOUT_EXPLAIN'		=> 'The amount of time, in seconds, that the system will wait for a reply from your server.',
	'FTP_FSOCK_USERNAME'			=> 'FTP username',
	'FTP_FSOCK_USERNAME_EXPLAIN'	=> 'username used to connect to your server.',

	'FTP_HOST'					=> 'FTP host',
	'FTP_HOST_EXPLAIN'			=> 'FTP server used to connect your site.',
	'FTP_PASSWORD'				=> 'FTP password',
	'FTP_PASSWORD_EXPLAIN'		=> 'Password for your FTP username.',
	'FTP_PORT'					=> 'FTP port',
	'FTP_PORT_EXPLAIN'			=> 'port used to connect to your server.',
	'FTP_ROOT_PATH'				=> 'path to phpBB',
	'FTP_ROOT_PATH_EXPLAIN'		=> 'path from the root to your phpBB board.',
	'FTP_TIMEOUT'				=> 'FTP timeout',
	'FTP_TIMEOUT_EXPLAIN'		=> 'the amount of time, in seconds, that the system will wait for a reply from your server.',
	'FTP_USERNAME'				=> 'FTP username',
	'FTP_USERNAME_EXPLAIN'		=> 'username used to connect to your server.',

	'GENERAL_ERROR'				=> 'general Error',
	'GB'						=> 'GB',
	'GIB'						=> 'GiB',
	'GO'						=> 'go',
	'GOTO_PAGE'					=> 'go to page',
	'GROUP'						=> 'group',
	'GROUPS'					=> 'groups',
	'GROUP_ERR_TYPE'			=> 'inappropriate group type specified.',
	'GROUP_ERR_USERNAME'		=> 'no group name specified.',
	'GROUP_ERR_USER_LONG'		=> 'group names cannot exceed 60 characters. The specified group name is too long.',
	'GUEST'						=> 'guest',
	'GUEST_USERS_ONLINE'		=> 'there are %d guest users online',
	'GUEST_USERS_TOTAL'			=> '%d guests',
	'GUEST_USERS_ZERO_ONLINE'	=> 'there are 0 guest users online',
	'GUEST_USERS_ZERO_TOTAL'	=> '0 guests',
	'GUEST_USER_ONLINE'			=> 'there is %d guest user online',
	'GUEST_USER_TOTAL'			=> '%d guest',
	'G_ADMINISTRATORS'			=> 'administrators',
	'G_BOTS'					=> 'bots',
	'G_GUESTS'					=> 'guests',
	'G_REGISTERED'				=> 'registered users',
	'G_REGISTERED_COPPA'		=> 'registered COPPA users',
	'G_GLOBAL_MODERATORS'		=> 'global moderators',
	'G_NEWLY_REGISTERED'		=> 'newly registered users',

	'HIDDEN_USERS_ONLINE'			=> '%d hidden users online',
	'HIDDEN_USERS_TOTAL'			=> '%d hidden',
	'HIDDEN_USERS_TOTAL_AND'		=> '%d hidden and ',
	'HIDDEN_USERS_ZERO_ONLINE'		=> '0 hidden users online',
	'HIDDEN_USERS_ZERO_TOTAL'		=> '0 hidden',
	'HIDDEN_USERS_ZERO_TOTAL_AND'	=> '0 hidden and ',
	'HIDDEN_USER_ONLINE'			=> '%d hidden user online',
	'HIDDEN_USER_TOTAL'				=> '%d hidden',
	'HIDDEN_USER_TOTAL_AND'			=> '%d hidden and ',
	'HIDE_GUESTS'					=> 'hide guests',
	'HIDE_ME'						=> 'hide my online status this session',
	'HOURS'							=> 'hours',
	'HOME'							=> 'home',

	'ICQ'						=> 'ICQ',
	'ICQ_STATUS'				=> 'ICQ status',
	'IF'						=> 'if',
	'IMAGE'						=> 'image',
	'IMAGE_FILETYPE_INVALID'	=> 'image file type %d for mimetype %s not supported.',
	'IMAGE_FILETYPE_MISMATCH'	=> 'image file type mismatch: expected extension %1$s but extension %2$s given.',
	'IN'						=> 'in',
	'INDEX'						=> 'index page',
	'INFORMATION'				=> 'information',
	'INTERESTS'					=> 'interests',
	'INVALID_DIGEST_CHALLENGE'	=> 'invalid digest challenge.',
	'INVALID_EMAIL_LOG'			=> '<strong>%s</strong> possibly an invalid e-mail address?',
	'IP'						=> 'ip',
	'IP_BLACKLISTED'			=> 'your ip %1$s has been blocked because it is blacklisted. For details please see <a href="%2$s">%2$s</a>.',

	'JABBER'				=> 'jabber',
	'JOINED'				=> 'joined',
	'JUMP_PAGE'				=> 'enter the page number you wish to go to',
	'JUMP_TO'				=> 'jump to',
	'JUMP_TO_PAGE'			=> 'click to jump to page…',

	'KB'					=> 'KB',
	'KIB'					=> 'KiB',

	'LAST_POST'							=> 'last post',
	'LAST_UPDATED'						=> 'last updated',
	'LAST_VISIT'						=> 'last visit',
	'LDAP_NO_LDAP_EXTENSION'			=> 'LDAP extension not available.',
	'LDAP_NO_SERVER_CONNECTION'			=> 'Could not connect to LDAP server.',
	'LDAP_SEARCH_FAILED'				=> 'An error occurred while searching the LDAP directory.',
	'LEGEND'							=> 'Legend',
	'LOCATION'							=> 'Location',
	'LOCK_POST'							=> 'Lock post',
	'LOCK_POST_EXPLAIN'					=> 'Prevent editing',
	'LOCK_TOPIC'						=> 'Lock topic',
	'LOGIN'								=> 'Login',
	'LOGIN_CHECK_PM'					=> 'Log in to check your private messages.',
	'LOGIN_CONFIRMATION'				=> 'Confirmation of login',
	'LOGIN_CONFIRM_EXPLAIN'				=> 'To prevent brute forcing accounts the board requires you to enter a confirmation code after a maximum amount of failed logins. The code is displayed in the image you should see below. If you are visually impaired or cannot otherwise read this code please contact the %sBoard Administrator%s.', // unused
	'LOGIN_ERROR_ATTEMPTS'				=> 'You exceeded the maximum allowed number of login attempts. In addition to your username and password you now also have to solve the CAPTCHA below.',
	'LOGIN_ERROR_EXTERNAL_AUTH_APACHE'	=> 'You have not been authenticated by Apache.',
	'LOGIN_ERROR_PASSWORD'				=> 'You have specified an incorrect password. Please check your password and try again. If you continue to have problems please contact the %sBoard Administrator%s.',
	'LOGIN_ERROR_PASSWORD_CONVERT'		=> 'It was not possible to convert your password when updating this bulletin board’s software. Please %srequest a new password%s. If you continue to have problems please contact the %sBoard Administrator%s.',
	'LOGIN_ERROR_USERNAME'				=> 'You have specified an incorrect username. Please check your username and try again. If you continue to have problems please contact the %sBoard Administrator%s.',
	'LOGIN_FORUM'						=> 'To view or post in this forum you must enter its password.',
	'LOGIN_INFO'						=> 'In order to login you must be registered. Registering takes only a few moments but gives you increased capabilities. The board administrator may also grant additional permissions to registered users. Before you register please ensure you are familiar with our terms of use and related policies. Please ensure you read any forum rules as you navigate around the board.',
	'LOGIN_VIEWFORUM'					=> 'The board requires you to be registered and logged in to view this forum.',
	'LOGIN_EXPLAIN_EDIT'				=> 'In order to edit posts in this forum you have to be registered and logged in.',
	'LOGIN_EXPLAIN_VIEWONLINE'			=> 'In order to view the online list you have to be registered and logged in.',
	'LOGOUT'							=> 'Logout',
	'LOGOUT_USER'						=> 'Logout [ %s ]',
	'LOG_ME_IN'							=> 'Log me on automatically each visit',

	'MARK'					=> 'mark',
	'MARK_ALL'				=> 'mark all',
	'MARK_FORUMS_READ'		=> 'mark forums read',
	'MARK_SUBFORUMS_READ'	=> 'mark subforums read',
	'MB'					=> 'MB',
	'MIB'					=> 'MiB',
	'MCP'					=> 'moderator control panel',
	'MEMBERLIST'			=> 'members',
	'MEMBERLIST_EXPLAIN'	=> 'view complete list of members',
	'MERGE'					=> 'merge',
	'MERGE_POSTS'			=> 'move posts',
	'MERGE_TOPIC'			=> 'merge topic',
	'MESSAGE'				=> 'message',
	'MESSAGES'				=> 'messages',
	'MESSAGE_BODY'			=> 'message body',
	'MINUTES'				=> 'minutes',
	'MODERATE'				=> 'moderate',
	'MODERATOR'				=> 'moderator',
	'MODERATORS'			=> 'moderators',
	'MONTH'					=> 'month',
	'MOVE'					=> 'move',
	'MSNM'					=> 'MSNM/WLM',

	'NA'						=> 'n/a',
	'NEWEST_USER'				=> 'our newest member <strong>%s</strong>',
	'NEW_MESSAGE'				=> 'new message',
	'NEW_MESSAGES'				=> 'new messages',
	'NEW_PM'					=> '<strong>%d</strong> new message',
	'NEW_PMS'					=> '<strong>%d</strong> new messages',
	'NEW_POST'					=> 'new post',	// Not used anymore
	'NEW_POSTS'					=> 'new posts',	// Not used anymore
	'NEXT'						=> 'next',		// Used in pagination
	'NEXT_STEP'					=> 'next',
	'NEVER'						=> 'never',
	'NO'						=> 'no',
	'NOT_ALLOWED_MANAGE_GROUP'	=> 'you are not allowed to manage this group.',
	'NOT_AUTHORISED'			=> 'you are not authorised to access this area.',
	'NOT_WATCHING_FORUM'		=> 'you are no longer subscribed to updates on this forum.',
	'NOT_WATCHING_TOPIC'		=> 'you are no longer subscribed to this topic.',
	'NOTIFY_ADMIN'				=> 'please notify the board administrator or webmaster.',
	'NOTIFY_ADMIN_EMAIL'		=> 'please notify the board administrator or webmaster: <a href="mailto:%1$s">%1$s</a>',
	'NO_ACCESS_ATTACHMENT'		=> 'you are not allowed to access this file.',
	'NO_ACTION'					=> 'no action specified.',
	'NO_ADMINISTRATORS'			=> 'there are no administrators.',
	'NO_AUTH_ADMIN'				=> 'access to the Administration Control Panel is not allowed as you do not have administrative permissions.',
	'NO_AUTH_ADMIN_USER_DIFFER'	=> 'you are not able to re-authenticate as a different user.',
	'NO_AUTH_OPERATION'			=> 'you do not have the necessary permissions to complete this operation.',
	'NO_CONNECT_TO_SMTP_HOST'	=> 'could not connect to smtp host : %1$s : %2$s',
	'NO_BIRTHDAYS'				=> 'no birthdays today',
	'NO_EMAIL_MESSAGE'			=> 'e-mail message was blank.',
	'NO_EMAIL_RESPONSE_CODE'	=> 'could not get mail server response codes.',
	'NO_EMAIL_SUBJECT'			=> 'no e-mail subject specified.',
	'NO_FORUM'					=> 'the forum you selected does not exist.',
	'NO_FORUMS'					=> 'this board has no forums.',
	'NO_GROUP'					=> 'the requested usergroup does not exist.',
	'NO_GROUP_MEMBERS'			=> 'this group currently has no members.',
	'NO_IPS_DEFINED'			=> 'no IP addresses or hostnames defined',
	'NO_MEMBERS'				=> 'no members found for this search criterion.',
	'NO_MESSAGES'				=> 'no messages',
	'NO_MODE'					=> 'no mode specified.',
	'NO_MODERATORS'				=> 'there are no moderators.',
	'NO_NEW_MESSAGES'			=> 'no new messages',
	'NO_NEW_PM'					=> '<strong>0</strong> new messages',
	'NO_NEW_POSTS'				=> 'No new posts',	// Not used anymore
	'NO_ONLINE_USERS'			=> 'No registered users',
	'NO_POSTS'					=> 'No posts',
	'NO_POSTS_TIME_FRAME'		=> 'No posts exist inside this topic for the selected time frame.',
	'NO_FEED_ENABLED'			=> 'Feeds are not available on this board.',
	'NO_FEED'					=> 'The requested feed is not available.',
	'NO_STYLE_DATA'				=> 'Could not get style data',
	'NO_SUBJECT'				=> 'No subject specified',								// Used for posts having no subject defined but displayed within management pages.
	'NO_SUCH_SEARCH_MODULE'		=> 'The specified search backend doesn’t exist.',
	'NO_SUPPORTED_AUTH_METHODS'	=> 'No supported authentication methods.',
	'NO_TOPIC'					=> 'The requested topic does not exist.',
	'NO_TOPIC_FORUM'			=> 'The topic or forum no longer exists.',
	'NO_TOPICS'					=> 'There are no topics or posts in this forum.',
	'NO_TOPICS_TIME_FRAME'		=> 'No topics exist inside this forum for the selected time frame.',
	'NO_UNREAD_PM'				=> '<strong>0</strong> unread messages',
	'NO_UNREAD_POSTS'			=> 'no unread posts',
	'NO_UPLOAD_FORM_FOUND'		=> 'upload initiated but no valid file upload form found.',
	'NO_USER'					=> 'the requested user does not exist.',
	'NO_USERS'					=> 'the requested users do not exist.',
	'NO_USER_SPECIFIED'			=> 'no username was specified.',

	// Nullar/Singular/Plural language entry. The key numbers define the number range in which a certain grammatical expression is valid.
	'NUM_POSTS_IN_QUEUE'		=> array(
		0			=> 'no posts in queue',		// 0
		1			=> '1 post in queue',		// 1
		2			=> '%d posts in queue',		// 2+
	),

	'OCCUPATION'				=> 'occupation',
	'OFFLINE'					=> 'offline',
	'ONLINE'					=> 'online',
	'ONLINE_BUDDIES'			=> 'online friends',
	'ONLINE_USERS_TOTAL'		=> 'in total there are <strong>%d</strong> users online :: ',
	'ONLINE_USERS_ZERO_TOTAL'	=> 'in total there are <strong>0</strong> users online :: ',
	'ONLINE_USER_TOTAL'			=> 'in total there is <strong>%d</strong> user online :: ',
	'OPTIONS'					=> 'options',

	'PAGE_OF'				=> 'page <strong>%1$d</strong> of <strong>%2$d</strong>',
	'PASSWORD'				=> 'password',
	'PIXEL'					=> 'px',
	'PLAY_QUICKTIME_FILE'	=> 'play Quicktime file',
	'PM'					=> 'pm',
	'PM_REPORTED'			=> 'click to view report',
	'POSTING_MESSAGE'		=> 'posting message in %s',
	'POSTING_PRIVATE_MESSAGE'	=> 'composing private message',
	'POST'					=> 'post',
	'POST_ANNOUNCEMENT'		=> 'announce',
	'POST_STICKY'			=> 'sticky',
	'POSTED'				=> 'posted',
	'POSTED_IN_FORUM'		=> 'in',
	'POSTED_ON_DATE'		=> 'on',
	'POSTS'					=> 'posts',
	'POSTS_UNAPPROVED'		=> 'at least one post in this topic has not been approved.',
	'POST_BY_AUTHOR'		=> 'by',
	'POST_BY_FOE'			=> 'this post was made by <strong>%1$s</strong> who is currently on your ignore list. %2$sDisplay this post%3$s.',
	'POST_DAY'				=> '%.2f posts per day',
	'POST_DETAILS'			=> 'post details',
	'POST_NEW_TOPIC'		=> 'post new topic',
	'POST_PCT'				=> '%.2f%% of all posts',
	'POST_PCT_ACTIVE'		=> '%.2f%% of user’s posts',
	'POST_PCT_ACTIVE_OWN'	=> '%.2f%% of your posts',
	'POST_REPLY'			=> 'post a reply',
	'POST_REPORTED'			=> 'click to view report',
	'POST_SUBJECT'			=> 'post subject',
	'POST_TIME'				=> 'post time',
	'POST_TOPIC'			=> 'post a new topic',
	'POST_UNAPPROVED'		=> 'this post is waiting for approval',
	'PREVIEW'				=> 'preview',
	'PREVIOUS'				=> 'previous',		// Used in pagination
	'PREVIOUS_STEP'			=> 'previous',
	'PRIVACY'				=> 'privacy policy',
	'PRIVATE_MESSAGE'		=> 'private message',
	'PRIVATE_MESSAGES'		=> 'private messages',
	'PRIVATE_MESSAGING'		=> 'private messaging',
	'PROFILE'				=> 'user control panel',

	'READING_FORUM'				=> 'viewing topics in %s',
	'READING_GLOBAL_ANNOUNCE'	=> 'reading global announcement',
	'READING_LINK'				=> 'following forum link %s',
	'READING_TOPIC'				=> 'reading topic in %s',
	'READ_PROFILE'				=> 'profile',
	'REASON'					=> 'reason',
	'RECORD_ONLINE_USERS'		=> 'most users ever online was <strong>%1$s</strong> on %2$s',
	'REDIRECT'					=> 'redirect',
	'REDIRECTS'					=> 'total redirects',
	'REGISTER'					=> 'register',
	'REGISTERED_USERS'			=> 'registered users:',
	'REG_USERS_ONLINE'			=> 'there are %d registered users and ',
	'REG_USERS_TOTAL'			=> '%d registered, ',
	'REG_USERS_TOTAL_AND'		=> '%d registered and ',
	'REG_USERS_ZERO_ONLINE'		=> 'there are 0 registered users and ',
	'REG_USERS_ZERO_TOTAL'		=> '0 registered, ',
	'REG_USERS_ZERO_TOTAL_AND'	=> '0 registered and ',
	'REG_USER_ONLINE'			=> 'there is %d registered user and ',
	'REG_USER_TOTAL'			=> '%d registered, ',
	'REG_USER_TOTAL_AND'		=> '%d registered and ',
	'REMOVE'					=> 'remove',
	'REMOVE_INSTALL'			=> 'please delete, move or rename the install directory before you use your board. If this directory is still present, only the Administration Control Panel (ACP) will be accessible.',
	'REPLIES'					=> 'replies',
	'REPLY_WITH_QUOTE'			=> 'reply with quote',
	'REPLYING_GLOBAL_ANNOUNCE'	=> 'replying to global announcement',
	'REPLYING_MESSAGE'			=> 'replying to message in %s',
	'REPORT_BY'					=> 'report by',
	'REPORT_POST'				=> 'report this post',
	'REPORTING_POST'			=> 'reporting post',
	'RESEND_ACTIVATION'			=> 'resend activation e-mail',
	'RESET'						=> 'reset',
	'RESTORE_PERMISSIONS'		=> 'restore permissions',
	'RETURN_INDEX'				=> '%sreturn to the index page%s',
	'RETURN_FORUM'				=> '%sreturn to the forum last visited%s',
	'RETURN_PAGE'				=> '%sreturn to the previous page%s',
	'RETURN_TOPIC'				=> '%sreturn to the topic last visited%s',
	'RETURN_TO'					=> 'return to',
	'FEED'						=> 'feed',
	'FEED_NEWS'					=> 'news',
	'FEED_TOPICS_ACTIVE'		=> 'active Topics',
	'FEED_TOPICS_NEW'			=> 'new Topics',
	'RULES_ATTACH_CAN'			=> 'you <strong>can</strong> post attachments in this forum',
	'RULES_ATTACH_CANNOT'		=> 'you <strong>cannot</strong> post attachments in this forum',
	'RULES_DELETE_CAN'			=> 'you <strong>can</strong> delete your posts in this forum',
	'RULES_DELETE_CANNOT'		=> 'you <strong>cannot</strong> delete your posts in this forum',
	'RULES_DOWNLOAD_CAN'		=> 'you <strong>can</strong> download attachments in this forum',
	'RULES_DOWNLOAD_CANNOT'		=> 'you <strong>cannot</strong> download attachments in this forum',
	'RULES_EDIT_CAN'			=> 'you <strong>can</strong> edit your posts in this forum',
	'RULES_EDIT_CANNOT'			=> 'you <strong>cannot</strong> edit your posts in this forum',
	'RULES_LOCK_CAN'			=> 'you <strong>can</strong> lock your topics in this forum',
	'RULES_LOCK_CANNOT'			=> 'you <strong>cannot</strong> lock your topics in this forum',
	'RULES_POST_CAN'			=> 'you <strong>can</strong> post new topics in this forum',
	'RULES_POST_CANNOT'			=> 'you <strong>cannot</strong> post new topics in this forum',
	'RULES_REPLY_CAN'			=> 'you <strong>can</strong> reply to topics in this forum',
	'RULES_REPLY_CANNOT'		=> 'you <strong>cannot</strong> reply to topics in this forum',
	'RULES_VOTE_CAN'			=> 'you <strong>can</strong> vote in polls in this forum',
	'RULES_VOTE_CANNOT'			=> 'you <strong>cannot</strong> vote in polls in this forum',

	'SEARCH'					=> 'search',
	'SEARCH_MINI'				=> 'search…',
	'SEARCH_ADV'				=> 'advanced search',
	'SEARCH_ADV_EXPLAIN'		=> 'view the advanced search options',
	'SEARCH_KEYWORDS'			=> 'search for keywords',
	'SEARCHING_FORUMS'			=> 'searching forums',
	'SEARCH_ACTIVE_TOPICS'		=> 'view active topics',
	'SEARCH_FOR'				=> 'search for',
	'SEARCH_FORUM'				=> 'search this forum…',
	'SEARCH_NEW'				=> 'view new posts',
	'SEARCH_POSTS_BY'			=> 'search posts by',
	'SEARCH_SELF'				=> 'view your posts',
	'SEARCH_TOPIC'				=> 'search this topic…',
	'SEARCH_UNANSWERED'			=> 'view unanswered posts',
	'SEARCH_UNREAD'				=> 'view unread posts',
	'SEARCH_USER_POSTS'			=> 'search user’s posts',
	'SECONDS'					=> 'seconds',
	'SELECT'					=> 'select',
	'SELECT_ALL_CODE'			=> 'select all',
	'SELECT_DESTINATION_FORUM'	=> 'please select a destination forum',
	'SELECT_FORUM'				=> 'select a forum',
	'SEND_EMAIL'				=> 'e-mail',				// Used for submit buttons
	'SEND_EMAIL_USER'			=> 'e-mail',				// Used as: {L_SEND_EMAIL_USER} {USERNAME} -> E-mail UserX
	'SEND_PRIVATE_MESSAGE'		=> 'send private message',
	'SETTINGS'					=> 'settings',
	'SIGNATURE'					=> 'signature',
	'SKIP'						=> 'skip to content',
	'SMTP_NO_AUTH_SUPPORT'		=> 'smtp server does not support authentication.',
	'SORRY_AUTH_READ'			=> 'you are not authorised to read this forum.',
	'SORRY_AUTH_VIEW_ATTACH'	=> 'you are not authorised to download this attachment.',
	'SORT_BY'					=> 'sort by',
	'SORT_JOINED'				=> 'joined date',
	'SORT_LOCATION'				=> 'location',
	'SORT_RANK'					=> 'rank',
	'SORT_POSTS'				=> 'posts',
	'SORT_TOPIC_TITLE'			=> 'topic title',
	'SORT_USERNAME'				=> 'username',
	'SPLIT_TOPIC'				=> 'split topic',
	'SQL_ERROR_OCCURRED'		=> 'an SQL error occurred while fetching this page. Please contact the %sBoard Administrator%s if this problem persists.',
	'STATISTICS'				=> 'statistics',
	'START_WATCHING_FORUM'		=> 'subscribe forum',
	'START_WATCHING_TOPIC'		=> 'subscribe topic',
	'STOP_WATCHING_FORUM'		=> 'unsubscribe forum',
	'STOP_WATCHING_TOPIC'		=> 'unsubscribe topic',
	'SUBFORUM'					=> 'subforum',
	'SUBFORUMS'					=> 'subforums',
	'SUBJECT'					=> 'subject',
	'SUBMIT'					=> 'submit',

	'TB'				=> 'TB',
	'TERMS_USE'			=> 'Terms of use',
	'TEST_CONNECTION'	=> 'Test connection',
	'THE_TEAM'			=> 'The team',
	'TIB'				=> 'TiB',
	'TIME'				=> 'Time',
	
	'TOO_LARGE'						=> 'the value you entered is too large.',
	'TOO_LARGE_MAX_RECIPIENTS'		=> 'the value of <strong>Maximum number of allowed recipients per private message</strong> setting you entered is too large.',

	'TOO_LONG'						=> 'the value you entered is too long.',

	'TOO_LONG_AIM'					=> 'the screenname you entered is too long.',
	'TOO_LONG_CONFIRM_CODE'			=> 'the confirm code you entered is too long.',
	'TOO_LONG_DATEFORMAT'			=> 'the date format you entered is too long.',
	'TOO_LONG_ICQ'					=> 'the ICQ number you entered is too long.',
	'TOO_LONG_INTERESTS'			=> 'the interests you entered is too long.',
	'TOO_LONG_JABBER'				=> 'the Jabber account name you entered is too long.',
	'TOO_LONG_LOCATION'				=> 'the location you entered is too long.',
	'TOO_LONG_MSN'					=> 'the MSNM/WLM name you entered is too long.',
	'TOO_LONG_NEW_PASSWORD'			=> 'the password you entered is too long.',
	'TOO_LONG_OCCUPATION'			=> 'the occupation you entered is too long.',
	'TOO_LONG_PASSWORD_CONFIRM'		=> 'the password confirmation you entered is too long.',
	'TOO_LONG_USER_PASSWORD'		=> 'the password you entered is too long.',
	'TOO_LONG_USERNAME'				=> 'the username you entered is too long.',
	'TOO_LONG_EMAIL'				=> 'the e-mail address you entered is too long.',
	'TOO_LONG_EMAIL_CONFIRM'		=> 'the e-mail address confirmation you entered is too long.',
	'TOO_LONG_WEBSITE'				=> 'the website address you entered is too long.',
	'TOO_LONG_YIM'					=> 'the Yahoo! Messenger name you entered is too long.',

	'TOO_MANY_VOTE_OPTIONS'			=> 'you have tried to vote for too many options.',

	'TOO_SHORT'						=> 'the value you entered is too short.',

	'TOO_SHORT_AIM'					=> 'the screenname you entered is too short.',
	'TOO_SHORT_CONFIRM_CODE'		=> 'the confirm code you entered is too short.',
	'TOO_SHORT_DATEFORMAT'			=> 'the date format you entered is too short.',
	'TOO_SHORT_ICQ'					=> 'the ICQ number you entered is too short.',
	'TOO_SHORT_INTERESTS'			=> 'the interests you entered is too short.',
	'TOO_SHORT_JABBER'				=> 'the Jabber account name you entered is too short.',
	'TOO_SHORT_LOCATION'			=> 'the location you entered is too short.',
	'TOO_SHORT_MSN'					=> 'the MSNM/WLM name you entered is too short.',
	'TOO_SHORT_NEW_PASSWORD'		=> 'the password you entered is too short.',
	'TOO_SHORT_OCCUPATION'			=> 'the occupation you entered is too short.',
	'TOO_SHORT_PASSWORD_CONFIRM'	=> 'the password confirmation you entered is too short.',
	'TOO_SHORT_USER_PASSWORD'		=> 'the password you entered is too short.',
	'TOO_SHORT_USERNAME'			=> 'the username you entered is too short.',
	'TOO_SHORT_EMAIL'				=> 'the e-mail address you entered is too short.',
	'TOO_SHORT_EMAIL_CONFIRM'		=> 'the e-mail address confirmation you entered is too short.',
	'TOO_SHORT_WEBSITE'				=> 'the website address you entered is too short.',
	'TOO_SHORT_YIM'					=> 'the Yahoo! Messenger name you entered is too short.',
	
	'TOO_SMALL'						=> 'the value you entered is too small.',
	'TOO_SMALL_MAX_RECIPIENTS'		=> 'the value of <strong>Maximum number of allowed recipients per private message</strong> setting you entered is too small.',

	'TOPIC'				=> 'topic',
	'TOPICS'			=> 'topics',
	'TOPICS_UNAPPROVED'	=> 'at least one topic in this forum has not been approved.',
	'TOPIC_ICON'		=> 'topic icon',
	'TOPIC_LOCKED'		=> 'this topic is locked, you cannot edit posts or make further replies.',
	'TOPIC_LOCKED_SHORT'=> 'topic locked',
	'TOPIC_MOVED'		=> 'moved topic',
	'TOPIC_REVIEW'		=> 'topic review',
	'TOPIC_TITLE'		=> 'topic title',
	'TOPIC_UNAPPROVED'	=> 'this topic has not been approved',
	'TOTAL_ATTACHMENTS'	=> 'attachment(s)',
	'TOTAL_LOG'			=> '1 log',
	'TOTAL_LOGS'		=> '%d logs',
	'TOTAL_NO_PM'		=> '0 private messages in total',
	'TOTAL_PM'			=> '1 private message in total',
	'TOTAL_PMS'			=> '%d private messages in total',
	'TOTAL_POSTS'		=> 'total posts',
	'TOTAL_POSTS_OTHER'	=> 'total posts <strong>%d</strong>',
	'TOTAL_POSTS_ZERO'	=> 'total posts <strong>0</strong>',
	'TOPIC_REPORTED'	=> 'this topic has been reported',
	'TOTAL_TOPICS_OTHER'=> 'total topics <strong>%d</strong>',
	'TOTAL_TOPICS_ZERO'	=> 'total topics <strong>0</strong>',
	'TOTAL_USERS_OTHER'	=> 'total members <strong>%d</strong>',
	'TOTAL_USERS_ZERO'	=> 'total members <strong>0</strong>',
	'TRACKED_PHP_ERROR'	=> 'tracked PHP errors: %s',

	'UNABLE_GET_IMAGE_SIZE'	=> 'it was not possible to determine the dimensions of the image.',
	'UNABLE_TO_DELIVER_FILE'=> 'unable to deliver file.',
	'UNKNOWN_BROWSER'		=> 'unknown browser',
	'UNMARK_ALL'			=> 'unmark all',
	'UNREAD_MESSAGES'		=> 'unread messages',
	'UNREAD_PM'				=> '<strong>%d</strong> unread message',
	'UNREAD_PMS'			=> '<strong>%d</strong> unread messages',
	'UNREAD_POST'			=> 'unread post',
	'UNREAD_POSTS'			=> 'unread posts',
	'UNWATCH_FORUM_CONFIRM'		=> 'are you sure you wish to unsubscribe from this forum?',
	'UNWATCH_FORUM_DETAILED'	=> 'are you sure you wish to unsubscribe from the forum “%s”?',
	'UNWATCH_TOPIC_CONFIRM'		=> 'are you sure you wish to unsubscribe from this topic?',
	'UNWATCH_TOPIC_DETAILED'	=> 'are you sure you wish to unsubscribe from the topic “%s”?',
	'UNWATCHED_FORUMS'			=> 'you are no longer subscribed to the selected forums.',
	'UNWATCHED_TOPICS'			=> 'you are no longer subscribed to the selected topics.',
	'UNWATCHED_FORUMS_TOPICS'	=> 'you are no longer subscribed to the selected entries.',
	'UPDATE'				=> 'update',
	'UPLOAD_IN_PROGRESS'	=> 'the upload is currently in progress.',
	'URL_REDIRECT'			=> 'if your browser does not support meta redirection %splease click HERE to be redirected%s.',
	'USERGROUPS'			=> 'groups',
	'USERNAME'				=> 'username',
	'USERNAMES'				=> 'usernames',
	'USER_AVATAR'			=> 'user avatar',
	'USER_CANNOT_READ'		=> 'you cannot read posts in this forum.',
	'USER_POST'				=> '%d Post',
	'USER_POSTS'			=> '%d Posts',
	'USERS'					=> 'users',
	'USE_PERMISSIONS'		=> 'test out user’s permissions',

	'USER_NEW_PERMISSION_DISALLOWED'	=> 'we are sorry, but you are not authorised to use this feature. you may have just registered here and may need to participate more to be able to use this feature.',

	'VARIANT_DATE_SEPARATOR'	=> ' / ',	// Used in date format dropdown, eg: "Today, 13:37 / 01 Jan 2007, 13:37" ... to join a relative date with calendar date
	'VIEWED'					=> 'viewed',
	

	
	'VIEWING_FAQ'				=> 'viewing faq',
	'VIEWING_MEMBERS'			=> 'viewing member details',
	'VIEWING_ONLINE'			=> 'viewing who is online',
	'VIEWING_MCP'				=> 'viewing moderator control panel',
	'VIEWING_MEMBER_PROFILE'	=> 'viewing member profile',
	'VIEWING_PRIVATE_MESSAGES'	=> 'viewing private messages',
	'VIEWING_REGISTER'			=> 'vegistering account',
	'VIEWING_UCP'				=> 'viewing user control panel',
	'VIEWS'						=> 'views',
	'VIEW_BOOKMARKS'			=> 'view bookmarks',
	'VIEW_FORUM_LOGS'			=> 'view Logs',
	'VIEW_LATEST_POST'			=> 'view the latest post',
	'VIEW_NEWEST_POST'			=> 'view first unread post',
	'VIEW_NOTES'				=> 'view user notes',
	'VIEW_ONLINE_TIME'			=> 'based on users active over the past %d minute',
	'VIEW_ONLINE_TIMES'			=> 'based on users active over the past %d minutes',
	'VIEW_TOPIC'				=> 'view topic',
	'VIEW_TOPIC_ANNOUNCEMENT'	=> 'announcement: ',
	'VIEW_TOPIC_GLOBAL'			=> 'global Announcement: ',
	'VIEW_TOPIC_LOCKED'			=> 'locked: ',
	'VIEW_TOPIC_LOGS'			=> 'view logs',
	'VIEW_TOPIC_MOVED'			=> 'moved: ',
	'VIEW_TOPIC_POLL'			=> 'poll: ',
	'VIEW_TOPIC_STICKY'			=> 'sticky: ',
	'VISIT_WEBSITE'				=> 'visit website',

	'WARNINGS'			=> 'warnings',
	'WARN_USER'			=> 'warn user',
	'WATCH_FORUM_CONFIRM'	=> 'are you sure you wish to subscribe to this forum?',
	'WATCH_FORUM_DETAILED'	=> 'are you sure you wish to subscribe to the forum “%s”?',
	'WATCH_TOPIC_CONFIRM'	=> 'are you sure you wish to subscribe to this topic?',
	'WATCH_TOPIC_DETAILED'	=> 'are you sure you wish to subscribe to the topic “%s”?',
	'WELCOME_SUBJECT'	=> 'welcome to %s forums',
	'WEBSITE'			=> 'website',
	'WHOIS'				=> 'whois',
	'WHO_IS_ONLINE'		=> 'who is online',
	'WRONG_PASSWORD'	=> 'you entered an incorrect password.',

	'WRONG_DATA_COLOUR'			=> 'The colour value you entered is invalid.',
	'WRONG_DATA_ICQ'			=> 'The number you entered is not a valid ICQ number.',
	'WRONG_DATA_JABBER'			=> 'The name you entered is not a valid Jabber account name.',
	'WRONG_DATA_LANG'			=> 'The language you specified is not valid.',
	'WRONG_DATA_WEBSITE'		=> 'The website address has to be a valid URL, including the protocol. For example http://www.example.com/.',
	'WROTE'						=> 'wrote',

	'YEAR'				=> 'year',
	'YEAR_MONTH_DAY'	=> '(yyyy-mm-dd)',
	'YES'				=> 'yes',
	'YIM'				=> 'yim',
	'YOU_LAST_VISIT'	=> 'last visit was: %s',
	'YOU_NEW_PM'		=> 'a new private message is waiting for you in your Inbox.',
	'YOU_NEW_PMS'		=> 'new private messages are waiting for you in your Inbox.',
	'YOU_NO_NEW_PM'		=> 'no new private messages are waiting for you.',

	'datetime'			=> array(
		'TODAY'		=> 'today',
		'TOMORROW'	=> 'tomorrow',
		'YESTERDAY'	=> 'yesterday',
		'AGO'		=> array(
			0		=> 'less than a minute ago',
			1		=> '%d minute ago',
			2		=> '%d minutes ago',
			60		=> '1 hour ago',
		),

		'Sunday'	=> 'Sunday',
		'Monday'	=> 'Monday',
		'Tuesday'	=> 'Tuesday',
		'Wednesday'	=> 'Wednesday',
		'Thursday'	=> 'Thursday',
		'Friday'	=> 'Friday',
		'Saturday'	=> 'Saturday',

		'Sun'		=> 'Sun',
		'Mon'		=> 'Mon',
		'Tue'		=> 'Tue',
		'Wed'		=> 'Wed',
		'Thu'		=> 'Thu',
		'Fri'		=> 'Fri',
		'Sat'		=> 'Sat',

		'January'	=> 'January',
		'February'	=> 'February',
		'March'		=> 'March',
		'April'		=> 'April',
		'May'		=> 'May',
		'June'		=> 'June',
		'July'		=> 'July',
		'August'	=> 'August',
		'September' => 'September',
		'October'	=> 'October',
		'November'	=> 'November',
		'December'	=> 'December',

		'Jan'		=> 'Jan',
		'Feb'		=> 'Feb',
		'Mar'		=> 'Mar',
		'Apr'		=> 'Apr',
		'May_short'	=> 'May',	// Short representation of "May". May_short used because in English the short and long date are the same for May.
		'Jun'		=> 'Jun',
		'Jul'		=> 'Jul',
		'Aug'		=> 'Aug',
		'Sep'		=> 'Sep',
		'Oct'		=> 'Oct',
		'Nov'		=> 'Nov',
		'Dec'		=> 'Dec',
	),

	'tz'				=> array(
		'-12'	=> 'utc - 12 hours',
		'-11'	=> 'utc - 11 hours',
		'-10'	=> 'utc - 10 hours',
		'-9.5'	=> 'utc - 9:30 hours',
		'-9'	=> 'utc - 9 hours',
		'-8'	=> 'utc - 8 hours',
		'-7'	=> 'utc - 7 hours',
		'-6'	=> 'utc - 6 hours',
		'-5'	=> 'utc - 5 hours',
		'-4.5'	=> 'utc - 4:30 hours',
		'-4'	=> 'utc - 4 hours',
		'-3.5'	=> 'utc - 3:30 hours',
		'-3'	=> 'utc - 3 hours',
		'-2'	=> 'utc - 2 hours',
		'-1'	=> 'utc - 1 hour',
		'0'		=> 'utc',
		'1'		=> 'utc + 1 hour',
		'2'		=> 'utc + 2 hours',
		'3'		=> 'utc + 3 hours',
		'3.5'	=> 'utc + 3:30 hours',
		'4'		=> 'utc + 4 hours',
		'4.5'	=> 'utc + 4:30 hours',
		'5'		=> 'utc + 5 hours',
		'5.5'	=> 'utc + 5:30 hours',
		'5.75'	=> 'utc + 5:45 hours',
		'6'		=> 'utc + 6 hours',
		'6.5'	=> 'utc + 6:30 hours',
		'7'		=> 'utc + 7 hours',
		'8'		=> 'utc + 8 hours',
		'8.75'	=> 'utc + 8:45 hours',
		'9'		=> 'utc + 9 hours',
		'9.5'	=> 'utc + 9:30 hours',
		'10'	=> 'utc + 10 hours',
		'10.5'	=> 'utc + 10:30 hours',
		'11'	=> 'utc + 11 hours',
		'11.5'	=> 'utc + 11:30 hours',
		'12'	=> 'utc + 12 hours',
		'12.75'	=> 'utc + 12:45 hours',
		'13'	=> 'utc + 13 hours',
		'14'	=> 'utc + 14 hours',
		'dst'	=> '[ <abbr title="Daylight Saving Time">DST</abbr> ]',
	),

	'tz_zones'	=> array(
		'-12'	=> '[utc - 12] Baker Island Time',
		'-11'	=> '[utc - 11] Niue Time, Samoa Standard Time',
		'-10'	=> '[utc - 10] Hawaii-Aleutian Standard Time, Cook Island Time',
		'-9.5'	=> '[utc - 9:30] Marquesas Islands Time',
		'-9'	=> '[utc - 9] Alaska Standard Time, Gambier Island Time',
		'-8'	=> '[utc - 8] Pacific Standard Time',
		'-7'	=> '[utc - 7] Mountain Standard Time',
		'-6'	=> '[utc - 6] Central Standard Time',
		'-5'	=> '[utc - 5] Eastern Standard Time',
		'-4.5'	=> '[utc - 4:30] Venezuelan Standard Time',
		'-4'	=> '[utc - 4] Atlantic Standard Time',
		'-3.5'	=> '[utc - 3:30] Newfoundland Standard Time',
		'-3'	=> '[utc - 3] Amazon Standard Time, Central Greenland Time',
		'-2'	=> '[utc - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time',
		'-1'	=> '[utc - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time',
		'0'		=> '[utc] Western European Time, Greenwich Mean Time',
		'1'		=> '[utc + 1] Central European Time, West African Time',
		'2'		=> '[utc + 2] Eastern European Time, Central African Time',
		'3'		=> '[utc + 3] Moscow Standard Time, Eastern African Time',
		'3.5'	=> '[utc + 3:30] Iran Standard Time',
		'4'		=> '[utc + 4] Gulf Standard Time, Samara Standard Time',
		'4.5'	=> '[utc + 4:30] Afghanistan Time',
		'5'		=> '[utc + 5] Pakistan Standard Time, Yekaterinburg Standard Time',
		'5.5'	=> '[utc + 5:30] Indian Standard Time, Sri Lanka Time',
		'5.75'	=> '[utc + 5:45] Nepal Time',
		'6'		=> '[utc + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time',
		'6.5'	=> '[utc + 6:30] Cocos Islands Time, Myanmar Time',
		'7'		=> '[utc + 7] Indochina Time, Krasnoyarsk Standard Time',
		'8'		=> '[utc + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time',
		'8.75'	=> '[utc + 8:45] Southeastern Western Australia Standard Time',
		'9'		=> '[utc + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time',
		'9.5'	=> '[utc + 9:30] Australian Central Standard Time',
		'10'	=> '[utc + 10] Australian Eastern Standard Time, Vladivostok Standard Time',
		'10.5'	=> '[utc + 10:30] Lord Howe Standard Time',
		'11'	=> '[utc + 11] Solomon Island Time, Magadan Standard Time',
		'11.5'	=> '[utc + 11:30] Norfolk Island Time',
		'12'	=> '[utc + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time',
		'12.75'	=> '[utc + 12:45] Chatham Islands Time',
		'13'	=> '[utc + 13] Tonga Time, Phoenix Islands Time',
		'14'	=> '[utc + 14] Line Island Time',
	),

	// The value is only an example and will get replaced by the current time on view
	'dateformats'	=> array(
		'd M Y, H:i'			=> '01 Jan 2007, 13:37',
		'd M Y H:i'				=> '01 Jan 2007 13:37',
		'M jS, \'y, H:i'		=> 'Jan 1st, \'07, 13:37',
		'D M d, Y g:i a'		=> 'Mon Jan 01, 2007 1:37 pm',
		'F jS, Y, g:i a'		=> 'January 1st, 2007, 1:37 pm',
		'|d M Y|, H:i'			=> 'Today, 13:37 / 01 Jan 2007, 13:37',
		'|F jS, Y|, g:i a'		=> 'Today, 1:37 pm / January 1st, 2007, 1:37 pm'
	),

	// The default dateformat which will be used on new installs in this language
	// Translators should change this if a the usual date format is different
	'default_dateformat'	=> 'D M d, Y g:i a', // Mon Jan 01, 2007 1:37 pm

));




?>