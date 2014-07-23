<?php
/**
*
* posting [English]
*
* @package language
* @version $Id: posting.php 9742 2009-07-09 10:34:40Z bantu $
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

$lang = array_merge($lang, array(

// show / hide smilies mod begin
	
	'SMILIES_SHOW'				=> '[show]',
	'SMILIES_HIDE'				=> '[hide]',

// show / hide smilies mod end

// link mod begin

//	'TOPIC_STORED',				=> 'the topic has been posted succesfully', // when uncomment page doesn't show up
	'LINK_TOPIC_STORED'					=> 'this link has been posted successfully.',
	'LINK_TOPIC_STORED_MOD'				=> 'this link has been submitted successfully, but it will need to be approved by a moderator before it is publicly viewable.',	

	'LINK_TOPIC_BUMPED'					=> 'link has been bumped successfully.',	
	'VIEW_LINK_MESSAGE'				=> '%sview your submitted post in this link%s',
	'RETURN_LINK_FORUM'				=> '%sreturn to Links%s',
	'LINK_TOPIC_NO_REPLY'			=> 'you cannot reply to a link topic.',	
	'TOPIC_LINK_URL'				=> 'url',
	'ADD_LINK_ATTACHMENT_EXPLAIN'	=> 'you can upload one thumbnail image, fileformat .jpg .png or .gif - This will be resized automatically to 250 pixels width.',
	
	'ATTACH_LINK'					=> 'attach link',
	'EMPTY_LINK_URL'				=> 'you must specify an url when posting a link.',
	
	'ATTACH_LINK_PRESELECT'			=> 'preselect filter',
	'LINK_LOCATION_FILTER'			=> 'location',
	'LINK_IMAGE_FILTER'				=> 'image thumbnail',
	'SELECT_LINK'					=> 'select link to attach',
	
	'MATCH_FULL_LOCATION'			=> 'match full location tag of this topic',
	'MATCH_CONTINENT'				=> 'match continent tag of this topic',
	'WITHOUT_LOCATION'				=> 'no location tag',
	'WITH_LOCATION'					=> 'with location tag',	
	
	'WITH_IMAGE'					=> 'with image',
	'WITHOUT_IMAGE'					=> 'no image',
	'ALL_LINKS'						=> 'all links',
	
	'WITHOUT_STATUS'				=> 'no status tag',
	'WITH_STATUS'					=> 'with status tag',
	
	'WITHOUT_TYPE'					=> 'no type tag',
	'WITH_TYPE'						=> 'with type tag',
	
	'WITHOUT_SOURCE'				=> 'no source tag',
	'WITH_SOURCE'					=> 'with source tag',
	
	'LINK_THIS_FORUM'				=> 'this forum',
	
	'ATTACH_ANOTHER_LINK'			=> 'attach another link',
	'LINK_TAG_N'					=> '%s link to attach',
	
	'LINK_PREFIX_FILTER'			=> 'prefix',
	'LINK_THIS_PREFIX'				=> 'match prefix of this topic',
	
// link mod end

// circle mod begin

	'CIRCLE_TOPIC_STORED'		=>'this circle has been created successfully.',
	'CIRCLE_TOPIC_STORED_MOD'	=>'this circle has been created successfully, but it will need to be approved by a moderator before it is publicly viewable.',	

	'CIRCLE_TOPIC_BUMPED'		=> 'circle has been bumped successfully.',	
	'VIEW_CIRCLE_MESSAGE'		=> '%sview your submitted post in this circle%s',
	'RETURN_CIRCLE_FORUM'		=> '%sreturn to Circles%s',
	'CIRCLE_TOPIC_NO_REPLY'		=> 'you cannot reply to a circle topic.',
	
	'TOPIC_CIRCLE_HIDDEN'			=> 'hide circle',
	'TOPIC_CIRCLE_HIDDEN_EXPLAIN'	=> 'make the circle invisible for non-circle-members.',
	'TOPIC_CIRCLE_CODE'				=> 'code',
	'TOPIC_CIRCLE_CODE_EXPLAIN'		=> 'limit access to your circle to only forum members that know your code. If no code is set, all forum members can join the circle freely. the code can be maximum 20 characters, is case insensitive and can contain all letters and numbers plus underscores. when the circle is hidden, a code is required.', 
	'ADD_CIRCLE_ATTACHMENT_EXPLAIN'	=> 'you can upload one \'banner\' image, fileformat .jpg .png or .gif - This will be resized automatically to 250 pixels width.',
	
// circle mod end

	'ADD_ATTACHMENT'			=> 'upload attachment',
	'ADD_ATTACHMENT_EXPLAIN'	=> 'if you wish to attach one or more files enter the details below.',
	'ADD_FILE'					=> 'add the file',
	'ADD_POLL'					=> 'poll creation',
	'ADD_POLL_EXPLAIN'			=> 'if you do not want to add a poll to your topic leave the fields blank.',
	'ALREADY_DELETED'			=> 'sorry but this message is already deleted.',
	'ATTACH_QUOTA_REACHED'		=> 'sorry, the board attachment quota has been reached.',
	'ATTACH_SIG'				=> 'attach a signature (signatures can be altered via the UCP)',

	'BBCODE_A_HELP'				=> 'inline uploaded attachment: [attachment=]filename.ext[/attachment]',
	'BBCODE_B_HELP'				=> 'bold text: [b]text[/b]',
	'BBCODE_C_HELP'				=> 'code display: [code]code[/code]',
	'BBCODE_D_HELP'				=> 'flash: [flash=width,height]http://url[/flash]',
	'BBCODE_F_HELP'				=> 'font size: [size=85]small text[/size]',
	'BBCODE_IS_OFF'				=> '%sBBcode%s is <em>OFF</em>',
	'BBCODE_IS_ON'				=> '%sBBcode%s is <em>ON</em>',
	'BBCODE_I_HELP'				=> 'italic text: [i]text[/i]',
	'BBCODE_L_HELP'				=> 'list: [list]text[/list]',
	'BBCODE_LISTITEM_HELP'		=> 'list item: [*]text[/*]',
	'BBCODE_O_HELP'				=> 'ordered list: [list=]text[/list]',
	'BBCODE_P_HELP'				=> 'insert image: [img]http://image_url[/img]',
	'BBCODE_Q_HELP'				=> 'quote text: [quote]text[/quote]',
	'BBCODE_S_HELP'				=> 'font colour: [color=red]text[/color]  Tip: you can also use color=#FF0000',
	'BBCODE_U_HELP'				=> 'underline text: [u]text[/u]',
	'BBCODE_W_HELP'				=> 'insert url: [url]http://url[/url] or [url=http://url]url text[/url]',
	'BBCODE_Y_HELP'				=> 'list: add list element',
	'BUMP_ERROR'				=> 'you cannot bump this topic so soon after the last post.',

	'CANNOT_DELETE_REPLIED'		=> 'sorry but you may only delete posts which have not been replied to.',
	'CANNOT_EDIT_POST_LOCKED'	=> 'this post has been locked. You can no longer edit that post.',
	'CANNOT_EDIT_TIME'			=> 'you can no longer edit or delete that post.',
	'CANNOT_POST_ANNOUNCE'		=> 'sorry but you cannot post announcements.',
	'CANNOT_POST_STICKY'		=> 'sorry but you cannot post sticky topics.',
	'CHANGE_TOPIC_TO'			=> 'change topic type to',
	'CLOSE_TAGS'				=> 'close tags',
	'CURRENT_TOPIC'				=> 'current topic',

	'DELETE_FILE'				=> 'delete file',
	'DELETE_MESSAGE'			=> 'delete message',
	'DELETE_MESSAGE_CONFIRM'	=> 'are you sure you want to delete this message?',
	'DELETE_OWN_POSTS'			=> 'sorry but you can only delete your own posts.',
	'DELETE_POST_CONFIRM'		=> 'are you sure you want to delete this post?',
	'DELETE_POST_WARN'			=> 'once deleted the post cannot be recovered',
	'DISABLE_BBCODE'			=> 'disable BBcode',
	'DISABLE_MAGIC_URL'			=> 'do not automatically parse urls',
	'DISABLE_SMILIES'			=> 'disable smilies',
	'DISALLOWED_CONTENT'		=> 'the upload was rejected because the uploaded file was identified as a possible attack vector.',
	'DISALLOWED_EXTENSION'		=> 'The extension %s is not allowed.',
	'DRAFT_LOADED'				=> 'draft loaded into posting area, you may want to finish your post now.<br />Your draft will be deleted after submitting this post.',
	'DRAFT_LOADED_PM'			=> 'draft loaded into message area, you may want to finish your private message now.<br />your draft will be deleted after submitting this private message.',
	'DRAFT_SAVED'				=> 'draft successfully saved.',
	'DRAFT_TITLE'				=> 'draft title',

	'EDIT_REASON'				=> 'reason for editing this post',
	'EMPTY_FILEUPLOAD'			=> 'the uploaded file is empty.',
	'EMPTY_MESSAGE'				=> 'you must enter a message when posting.',
	'EMPTY_REMOTE_DATA'			=> 'file could not be uploaded, please try uploading the file manually.',

	'FLASH_IS_OFF'				=> '[flash] is <em>OFF</em>',
	'FLASH_IS_ON'				=> '[flash] is <em>ON</em>',
	'FLOOD_ERROR'				=> 'you cannot make another post so soon after your last.',
	'FONT_COLOR'				=> 'font colour',
	'FONT_COLOR_HIDE'			=> 'hide font colour',
	'FONT_HUGE'					=> 'huge',
	'FONT_LARGE'				=> 'large',
	'FONT_NORMAL'				=> 'normal',
	'FONT_SIZE'					=> 'font size',
	'FONT_SMALL'				=> 'small',
	'FONT_TINY'					=> 'tiny',

	'GENERAL_UPLOAD_ERROR'		=> 'could not upload attachment to %s.',

	'IMAGES_ARE_OFF'			=> '[img] is <em>OFF</em>',
	'IMAGES_ARE_ON'				=> '[img] is <em>ON</em>',
	'INVALID_FILENAME'			=> '%s is an invalid filename.',

	'LOAD'						=> 'load',
	'LOAD_DRAFT'				=> 'load draft',
	'LOAD_DRAFT_EXPLAIN'		=> 'here you are able to select the draft you want to continue writing. Your current post will be cancelled, all current post contents will be deleted. View, edit and delete drafts within your User Control Panel.',
	'LOGIN_EXPLAIN_BUMP'		=> 'you need to login in order to bump topics within this forum.',
	'LOGIN_EXPLAIN_DELETE'		=> 'you need to login in order to delete posts within this forum.',
	'LOGIN_EXPLAIN_POST'		=> 'you need to login in order to post within this forum.',
	'LOGIN_EXPLAIN_QUOTE'		=> 'you need to login in order to quote posts within this forum.',
	'LOGIN_EXPLAIN_REPLY'		=> 'you need to login in order to reply to topics within this forum.',

	'MAX_FONT_SIZE_EXCEEDED'	=> 'you may only use fonts up to size %1$d.',
	'MAX_FLASH_HEIGHT_EXCEEDED'	=> 'your flash files may only be up to %1$d pixels high.',
	'MAX_FLASH_WIDTH_EXCEEDED'	=> 'your flash files may only be up to %1$d pixels wide.',
	'MAX_IMG_HEIGHT_EXCEEDED'	=> 'your images may only be up to %1$d pixels high.',
	'MAX_IMG_WIDTH_EXCEEDED'	=> 'your images may only be up to %1$d pixels wide.',

	'MESSAGE_BODY_EXPLAIN'		=> 'enter your message here, it may contain no more than <strong>%d</strong> characters.',
	'MESSAGE_DELETED'			=> 'this message has been deleted successfully.',
	'MORE_SMILIES'				=> 'view more smilies',

	'NOTIFY_REPLY'				=> 'notify me when a reply is posted',
	'NOT_UPLOADED'				=> 'file could not be uploaded.',
	'NO_DELETE_POLL_OPTIONS'	=> 'you cannot delete existing poll options.',
	'NO_PM_ICON'				=> 'no pm icon',
	'NO_POLL_TITLE'				=> 'you have to enter a poll title.',
	'NO_POST'					=> 'the requested post does not exist.',
	'NO_POST_MODE'				=> 'no post mode specified.',

	'PARTIAL_UPLOAD'			=> 'the uploaded file was only partially uploaded.',
	'PHP_SIZE_NA'				=> 'the attachment’s file size is too large.<br />could not determine the maximum size defined by PHP in php.ini.',
	'PHP_SIZE_OVERRUN'			=> 'the attachment’s file size is too large, the maximum upload size is %1$d %2$s.<br />please note this is set in php.ini and cannot be overridden.',
	'PLACE_INLINE'				=> 'place inline',
	'POLL_DELETE'				=> 'delete poll',
	'POLL_FOR'					=> 'run poll for',
	'POLL_FOR_EXPLAIN'			=> 'enter 0 or leave blank for a never ending poll.',
	'POLL_MAX_OPTIONS'			=> 'options per user',
	'POLL_MAX_OPTIONS_EXPLAIN'	=> 'this is the number of options each user may select when voting.',
	'POLL_OPTIONS'				=> 'poll options',
	'POLL_OPTIONS_EXPLAIN'		=> 'place each option on a new line. you may enter up to <strong>%d</strong> options.',
	'POLL_OPTIONS_EDIT_EXPLAIN'	=> 'place each option on a new line. you may enter up to <strong>%d</strong> options. if you remove or add options all previous votes will be reset.',
	'POLL_QUESTION'				=> 'poll question',
	'POLL_TITLE_TOO_LONG'		=> 'the poll title must contain fewer than 100 characters.',
	'POLL_TITLE_COMP_TOO_LONG'	=> 'the parsed size of your poll title is too large, consider removing BBCodes or smilies.',
	'POLL_VOTE_CHANGE'			=> 'allow re-voting',
	'POLL_VOTE_CHANGE_EXPLAIN'	=> 'if enabled users are able to change their vote.',
	'POSTED_ATTACHMENTS'		=> 'posted attachments',
	'POST_APPROVAL_NOTIFY'		=> 'you will be notified when your post has been approved.',
	'POST_CONFIRMATION'			=> 'confirmation of post',
	'POST_CONFIRM_EXPLAIN'		=> 'to prevent automated posts the board requires you to enter a confirmation code. the code is displayed in the image you should see below. if you are visually impaired or cannot otherwise read this code please contact the %sboard administrator%s.',
	'POST_DELETED'				=> 'this message has been deleted successfully.',
	'POST_EDITED'				=> 'this message has been edited successfully.',
	'POST_EDITED_MOD'			=> 'this message has been edited successfully, but it will need to be approved by a moderator before it is publicly viewable.',
	'POST_GLOBAL'				=> 'global',
	'POST_ICON'					=> 'post icon',
	'POST_NORMAL'				=> 'normal',
	'POST_REVIEW'				=> 'post review',
	'POST_REVIEW_EDIT'			=> 'post review',
	'POST_REVIEW_EDIT_EXPLAIN'	=> 'this post has been altered by another user while you were editing it. you may wish to review the current version of this post and adjust your edits.',
	'POST_REVIEW_EXPLAIN'		=> 'at least one new post has been made to this topic. you may wish to review your post in light of this.',
	'POST_STORED'				=> 'this message has been posted successfully.',
	'POST_STORED_MOD'			=> 'this message has been submitted successfully, but it will need to be approved by a moderator before it is publicly viewable.',
	'POST_TOPIC_AS'				=> 'post topic as',
	'PROGRESS_BAR'				=> 'progress bar',

	'QUOTE_DEPTH_EXCEEDED'		=> 'you may embed only %1$d quotes within each other.',

	'SAVE'						=> 'save',
	'SAVE_DATE'					=> 'saved at',
	'SAVE_DRAFT'				=> 'save draft',
	'SAVE_DRAFT_CONFIRM'		=> 'please note that saved drafts only include the subject and the message, any other element will be removed. do you want to save your draft now?',
	'SMILIES'					=> 'smilies',
	'SMILIES_ARE_OFF'			=> 'smilies are <em>OFF</em>',
	'SMILIES_ARE_ON'			=> 'smilies are <em>ON</em>',
	'STICKY_ANNOUNCE_TIME_LIMIT'=> 'sticky/Announcement time limit',
	'STICK_TOPIC_FOR'			=> 'stick topic for',
	'STICK_TOPIC_FOR_EXPLAIN'	=> 'enter 0 or leave blank for a never ending sticky/announcement. please note that this number is relative to the date of the post.',
	'STYLES_TIP'				=> 'tip: styles can be applied quickly to selected text.',

	'TOO_FEW_CHARS'				=> 'your message contains too few characters.',
	'TOO_FEW_CHARS_LIMIT'		=> 'your message contains %1$d characters. the minimum number of characters you need to enter is %2$d.',
	'TOO_FEW_POLL_OPTIONS'		=> 'you must enter at least two poll options.',
	'TOO_MANY_ATTACHMENTS'		=> 'cannot add another attachment, %d is the maximum.',
	'TOO_MANY_CHARS'			=> 'your message contains too many characters.',
	'TOO_MANY_CHARS_POST'		=> 'your message contains %1$d characters. the maximum number of allowed characters is %2$d.',
	'TOO_MANY_CHARS_SIG'		=> 'your signature contains %1$d characters. the maximum number of allowed characters is %2$d.',
	'TOO_MANY_POLL_OPTIONS'		=> 'you have tried to enter too many poll options.',
	'TOO_MANY_SMILIES'			=> 'your message contains too many smilies. The maximum number of smilies allowed is %d.',
	'TOO_MANY_URLS'				=> 'your message contains too many URLs. The maximum number of urls allowed is %d.',
	'TOO_MANY_USER_OPTIONS'		=> 'you cannot specify more options per user than existing poll options.',
	'TOPIC_BUMPED'				=> 'topic has been bumped successfully.',

	'UNAUTHORISED_BBCODE'		=> 'you cannot use certain BBcodes: %s.',
	'UNGLOBALISE_EXPLAIN'		=> 'to switch this topic back from being global to a normal topic, you need to select the forum you wish this topic to be displayed.',
	'UPDATE_COMMENT'			=> 'update comment',
	'URL_INVALID'				=> 'the url you specified is invalid.',
	'URL_NOT_FOUND'				=> 'the file specified could not be found.',
	'URL_IS_OFF'				=> '[url] is <em>OFF</em>',
	'URL_IS_ON'					=> '[url] is <em>ON</em>',
	'USER_CANNOT_BUMP'			=> 'you cannot bump topics in this forum.',
	'USER_CANNOT_DELETE'		=> 'you cannot delete posts in this forum.',
	'USER_CANNOT_EDIT'			=> 'you cannot edit posts in this forum.',
	'USER_CANNOT_REPLY'			=> 'you cannot reply in this forum.',
	'USER_CANNOT_FORUM_POST'	=> 'you are not able to do posting operations on this forum due to the forum type not supporting it.',

	'VIEW_MESSAGE'				=> '%sview your submitted message%s',
	'VIEW_PRIVATE_MESSAGE'		=> '%sview your submitted private message%s',

	'WRONG_FILESIZE'			=> 'the file is too big, maximum allowed size is %1d %2s.',
	'WRONG_SIZE'				=> 'the image must be at least %1$d pixels wide, %2$d pixels high and at most %3$d pixels wide and %4$d pixels high. the submitted image is %5$d pixels wide and %6$d pixels high.',
));

?>