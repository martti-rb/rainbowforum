<?php
/**
*
* @package phpBB3
* @version $Id: posting.php 10507 2010-02-18 04:56:06Z jelly_doughnut $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);


// Start session management
$user->session_begin();
$auth->acl($user->data);


// Grab only parameters needed here
$post_id	= request_var('p', 0);
$topic_id	= request_var('t', 0);
$forum_id	= request_var('f', 0);
$draft_id	= request_var('d', 0);
$lastclick	= request_var('lastclick', 0);

$submit		= (isset($_POST['post'])) ? true : false;
$preview	= (isset($_POST['preview'])) ? true : false;
$save		= (isset($_POST['save'])) ? true : false;
$load		= (isset($_POST['load'])) ? true : false;
$delete		= (isset($_POST['delete'])) ? true : false;
$cancel		= (isset($_POST['cancel']) && !isset($_POST['save'])) ? true : false;

$refresh	= (isset($_POST['add_file']) || isset($_POST['delete_file']) || isset($_POST['full_editor']) || isset($_POST['cancel_unglobalise']) || $save || $load) ? true : false;
$mode		= ($delete && !$preview && !$refresh && $submit) ? 'delete' : request_var('mode', '');

$error = $post_data = array();
$current_time = time();

// Was cancel pressed? If so then redirect to the appropriate page
if ($cancel || ($current_time - $lastclick < 2 && $submit))
{
	$f = ($forum_id) ? 'f=' . $forum_id . '&amp;' : '';
	$redirect = ($post_id) ? append_sid("{$phpbb_root_path}viewtopic.$phpEx", $f . 'p=' . $post_id) . '#p' . $post_id : (($topic_id) ? append_sid("{$phpbb_root_path}viewtopic.$phpEx", $f . 't=' . $topic_id) : (($forum_id) ? append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id) : append_sid("{$phpbb_root_path}index.$phpEx")));
	redirect($redirect);
}

if (in_array($mode, array('post', 'reply', 'quote', 'edit', 'delete')) && !$forum_id)
{
	trigger_error('NO_FORUM');
}

// We need to know some basic information in all cases before we do anything.
switch ($mode)
{
	case 'post':
		$sql = 'SELECT *
			FROM ' . FORUMS_TABLE . "
			WHERE forum_id = $forum_id";
	break;

	case 'bump':
	case 'reply':
		if (!$topic_id)
		{
			trigger_error('NO_TOPIC');
		}

		// Force forum id
		$sql = 'SELECT forum_id
			FROM ' . TOPICS_TABLE . '
			WHERE topic_id = ' . $topic_id;
		$result = $db->sql_query($sql);
		$f_id = (int) $db->sql_fetchfield('forum_id');
		$db->sql_freeresult($result);

		$forum_id = (!$f_id) ? $forum_id : $f_id;

		$sql = 'SELECT f.*, t.*
			FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . " f
			WHERE t.topic_id = $topic_id
				AND (f.forum_id = t.forum_id
					OR f.forum_id = $forum_id)" .
			(($auth->acl_get('m_approve', $forum_id)) ? '' : 'AND t.topic_approved = 1');
	break;

	case 'quote':
	case 'edit':
	case 'delete':
		if (!$post_id)
		{
			$user->setup('posting');
			trigger_error('NO_POST');
		}

		// Force forum id
		$sql = 'SELECT forum_id
			FROM ' . POSTS_TABLE . '
			WHERE post_id = ' . $post_id;
		$result = $db->sql_query($sql);
		$f_id = (int) $db->sql_fetchfield('forum_id');
		$db->sql_freeresult($result);

		$forum_id = (!$f_id) ? $forum_id : $f_id;

		$sql = 'SELECT f.*, t.*, p.*, u.username, u.username_clean, u.user_sig, u.user_sig_bbcode_uid, u.user_sig_bbcode_bitfield
			FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . ' f, ' . USERS_TABLE . " u
			WHERE p.post_id = $post_id
				AND t.topic_id = p.topic_id
				AND u.user_id = p.poster_id
				AND (f.forum_id = t.forum_id
					OR f.forum_id = $forum_id)" .
				(($auth->acl_get('m_approve', $forum_id)) ? '' : 'AND p.post_approved = 1');
	break;

	case 'smilies':
		$sql = '';
		generate_smilies('window', $forum_id);
	break;

	case 'popup':
		if ($forum_id)
		{
			$sql = 'SELECT forum_style
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . $forum_id;
		}
		else
		{
			upload_popup();
			return;
		}
	break;

	default:
		$sql = '';
	break;
}




// link mod begin // circle mod begin

$links_forum = ($config['link_forum_id'] == $forum_id) ? true : false;
$circles_forum = ($config['circle_forum_id'] == $forum_id) ? true : false;
$links_circles_forum = $links_forum || $circles_forum;

$repl = new lang_key_replacer();

if ($links_circles_forum)
{
	$repl->search = array(
		'SUBJECT',
		'TOPIC',
		'FORUM',
		'MESSAGE',
		'POST',
		'ATTACHMENT',
		);
	
	$links_circles_transform = ($links_forum) ? 'LINK_' : 'CIRCLE_';
	
	foreach ($repl->search as $val)
	{
		$repl->replace[] = $links_circles_transform . $val;
	}
	
	

}

// link mod end // circle mod end

// topic color mod begin

$topic_color = new topic_color();

// topic color mod end



if (!$sql)
{
	$user->setup('posting');
	trigger_error('NO_POST_MODE');
}

$result = $db->sql_query($sql);
$post_data = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if (!$post_data)
{
	if (!($mode == 'post' || $mode == 'bump' || $mode == 'reply'))
	{
		$user->setup('posting');
	}
	trigger_error(($mode == 'post' || $mode == 'bump' || $mode == 'reply') ? 'NO_TOPIC' : 'NO_POST');
}

// Not able to reply to unapproved posts/topics
// TODO: add more descriptive language key
if ($auth->acl_get('m_approve', $forum_id) && ((($mode == 'reply' || $mode == 'bump') && !$post_data['topic_approved']) || ($mode == 'quote' && !$post_data['post_approved'])))
{
	trigger_error(($mode == 'reply' || $mode == 'bump') ? 'TOPIC_UNAPPROVED' : 'POST_UNAPPROVED');
}

if ($mode == 'popup')
{
	upload_popup($post_data['forum_style']);
	return;
}

$user->setup(array('posting', 'mcp', 'viewtopic'), $post_data['forum_style']);

if ($config['enable_post_confirm'] && !$user->data['is_registered'])
{
	include($phpbb_root_path . 'includes/captcha/captcha_factory.' . $phpEx);
	$captcha =& phpbb_captcha_factory::get_instance($config['captcha_plugin']);
	$captcha->init(CONFIRM_POST);
}

// Use post_row values in favor of submitted ones...
$forum_id	= (!empty($post_data['forum_id'])) ? (int) $post_data['forum_id'] : (int) $forum_id;
$topic_id	= (!empty($post_data['topic_id'])) ? (int) $post_data['topic_id'] : (int) $topic_id;
$post_id	= (!empty($post_data['post_id'])) ? (int) $post_data['post_id'] : (int) $post_id;

// Need to login to passworded forum first?
if ($post_data['forum_password'])
{
	login_forum_box(array(
		'forum_id'			=> $forum_id,
		'forum_name'		=> $post_data['forum_name'],
		'forum_password'	=> $post_data['forum_password'])
	);
}

// Check permissions
if ($user->data['is_bot'])
{
	redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
}

// Is the user able to read within this forum?
if (!$auth->acl_get('f_read', $forum_id))
{
	if ($user->data['user_id'] != ANONYMOUS)
	{
		trigger_error('USER_CANNOT_READ');
	}

	login_box('', $user->lang['LOGIN_EXPLAIN_POST']);
}

// Permission to do the action asked?
$is_authed = false;

switch ($mode)
{
	case 'post':
		if ($auth->acl_get('f_post', $forum_id))
		{
			$is_authed = true;
		}
	break;

	case 'bump':
		if ($auth->acl_get('f_bump', $forum_id))
		{
			$is_authed = true;
		}
	break;

	case 'quote':

		$post_data['post_edit_locked'] = 0;

	// no break;

	case 'reply':
		if ($auth->acl_get('f_reply', $forum_id))
		{
			$is_authed = true;
		}
	break;

	case 'edit':
		if ($user->data['is_registered'] && $auth->acl_gets('f_edit', 'm_edit', $forum_id))
		{
			$is_authed = true;
		}
	break;

	case 'delete':
		if ($user->data['is_registered'] && $auth->acl_gets('f_delete', 'm_delete', $forum_id))
		{
			$is_authed = true;
		}
	break;
}

if (!$is_authed)
{
	$check_auth = ($mode == 'quote') ? 'reply' : $mode;

	if ($user->data['is_registered'])
	{
		trigger_error('USER_CANNOT_' . strtoupper($check_auth));
	}

	login_box('', $user->lang['LOGIN_EXPLAIN_' . strtoupper($mode)]);
}

// Is the user able to post within this forum?
if ($post_data['forum_type'] != FORUM_POST && in_array($mode, array('post', 'bump', 'quote', 'reply')))
{
	trigger_error('USER_CANNOT_FORUM_POST');
}

// Forum/Topic locked?
if (($post_data['forum_status'] == ITEM_LOCKED || (isset($post_data['topic_status']) && $post_data['topic_status'] == ITEM_LOCKED)) && !$auth->acl_get('m_edit', $forum_id))
{
	trigger_error(($post_data['forum_status'] == ITEM_LOCKED) ? 'FORUM_LOCKED' : 'TOPIC_LOCKED');
}

// Can we edit this post ... if we're a moderator with rights then always yes
// else it depends on editing times, lock status and if we're the correct user
if ($mode == 'edit' && !$auth->acl_get('m_edit', $forum_id))
{
	if ($user->data['user_id'] != $post_data['poster_id'])
	{
		trigger_error('USER_CANNOT_EDIT');
	}

	if (!($post_data['post_time'] > time() - ($config['edit_time'] * 60) || !$config['edit_time']))
	{
		trigger_error('CANNOT_EDIT_TIME');
	}

	if ($post_data['post_edit_locked'])
	{
		trigger_error('CANNOT_EDIT_POST_LOCKED');
	}
}

// Handle delete mode...
if ($mode == 'delete')
{
	handle_post_delete($forum_id, $topic_id, $post_id, $post_data);
	return;
}

// Handle bump mode...
if ($mode == 'bump')
{
	if ($bump_time = bump_topic_allowed($forum_id, $post_data['topic_bumped'], $post_data['topic_last_post_time'], $post_data['topic_poster'], $post_data['topic_last_poster_id'])
	   && check_link_hash(request_var('hash', ''), "topic_{$post_data['topic_id']}"))
	{
		$meta_url = phpbb_bump_topic($forum_id, $topic_id, $post_data, $current_time);
		meta_refresh(3, $meta_url);

		$message = $repl->lang('TOPIC_BUMPED') . '<br /><br />' . sprintf($repl->lang('VIEW_MESSAGE'), '<a href="' . $meta_url . '">', '</a>'); // link mod // circle mod
		$message .= '<br /><br />' . sprintf($repl->lang('RETURN_FORUM'), '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id) . '">', '</a>'); // link mod // circle mod

		trigger_error($message);
	}

	trigger_error('BUMP_ERROR');
}

// Subject length limiting to 60 characters if first post... // mod to 100 characters 
if ($mode == 'post' || ($mode == 'edit' && $post_data['topic_first_post_id'] == $post_data['post_id']))
{
	$template->assign_var('S_NEW_MESSAGE', true);
}



// Topic Title Prefix Mod - Begin

$title_prefix = request_var('ttp', 0);		
	
if ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']))
{
	if ($preview || $submit)
	{
		$prefix_select = $title_prefix;		
	}
	else
	{
		if ($mode == 'post')
		{
			$prefix_select = 0;
		}
		if ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])
		{
			$prefix_select = (Isset($post_data['topic_prefix_id'])) ? ($post_data['topic_prefix_id']) : 0;
		}
	}
}
	
// Topic Title Prefix Mod - End 


// link mod begin

if ($links_circles_forum && ($mode == 'reply' || $mode == 'quote')) // must be changed in 'topics' instead of 'forums'
{
	trigger_error($repl->lang('TOPIC_NO_REPLY', false));
}

// link mod end

// Determine some vars
if (isset($post_data['poster_id']) && $post_data['poster_id'] == ANONYMOUS)
{
	$post_data['quote_username'] = (!empty($post_data['post_username'])) ? $post_data['post_username'] : $user->lang['GUEST'];
}
else
{
	$post_data['quote_username'] = isset($post_data['username']) ? $post_data['username'] : '';
}

$post_data['post_edit_locked']	= (isset($post_data['post_edit_locked'])) ? (int) $post_data['post_edit_locked'] : 0;
$post_data['post_subject_md5']	= (isset($post_data['post_subject']) && $mode == 'edit') ? md5($post_data['post_subject']) : '';
$post_data['post_subject']		= (in_array($mode, array('quote', 'edit'))) ? $post_data['post_subject'] : ((isset($post_data['topic_title'])) ? $post_data['topic_title'] : '');
$post_data['topic_time_limit']	= (isset($post_data['topic_time_limit'])) ? (($post_data['topic_time_limit']) ? (int) $post_data['topic_time_limit'] / 86400 : (int) $post_data['topic_time_limit']) : 0;
$post_data['poll_length']		= (!empty($post_data['poll_length'])) ? (int) $post_data['poll_length'] / 86400 : 0;
$post_data['poll_start']		= (!empty($post_data['poll_start'])) ? (int) $post_data['poll_start'] : 0;
$post_data['icon_id']			= (!isset($post_data['icon_id']) || in_array($mode, array('quote', 'reply'))) ? 0 : (int) $post_data['icon_id'];
$post_data['poll_options']		= array();

// Get Poll Data
if ($post_data['poll_start'])
{
	$sql = 'SELECT poll_option_text
		FROM ' . POLL_OPTIONS_TABLE . "
		WHERE topic_id = $topic_id
		ORDER BY poll_option_id";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$post_data['poll_options'][] = trim($row['poll_option_text']);
	}
	$db->sql_freeresult($result);
}

if ($mode == 'edit')
{
	$original_poll_data = array(
		'poll_title'		=> $post_data['poll_title'],
		'poll_length'		=> $post_data['poll_length'],
		'poll_max_options'	=> $post_data['poll_max_options'],
		'poll_option_text'	=> implode("\n", $post_data['poll_options']),
		'poll_start'		=> $post_data['poll_start'],
		'poll_last_vote'	=> $post_data['poll_last_vote'],
		'poll_vote_change'	=> $post_data['poll_vote_change'],
	);
}

$orig_poll_options_size = sizeof($post_data['poll_options']);

$message_parser = new parse_message();

if (isset($post_data['post_text']))
{
	$message_parser->message = &$post_data['post_text'];
	unset($post_data['post_text']);
}

// link mod begin // circle mod begin

$message_parser->links_error_suppress();

// link mod end // circle mod end

// Set some default variables
$uninit = array('post_attachment' => 0, 'poster_id' => $user->data['user_id'], 'enable_magic_url' => 0, 'topic_status' => 0, 'topic_type' => POST_NORMAL, 'post_subject' => '', 'topic_title' => '', 'post_time' => 0, 'post_edit_reason' => '', 'notify_set' => 0);

foreach ($uninit as $var_name => $default_value)
{
	if (!isset($post_data[$var_name]))
	{
		$post_data[$var_name] = $default_value;
	}
}
unset($uninit);

// Always check if the submitted attachment data is valid and belongs to the user.
// Further down (especially in submit_post()) we do not check this again.
$message_parser->get_submitted_attachment_data($post_data['poster_id']);

if ($post_data['post_attachment'] && !$submit && !$refresh && !$preview && $mode == 'edit')
{
	// Do not change to SELECT *
	$sql = 'SELECT attach_id, is_orphan, attach_comment, real_filename
		FROM ' . ATTACHMENTS_TABLE . "
		WHERE post_msg_id = $post_id
			AND in_message = 0
			AND is_orphan = 0
		ORDER BY filetime DESC";
	$result = $db->sql_query($sql);
	$message_parser->attachment_data = array_merge($message_parser->attachment_data, $db->sql_fetchrowset($result));
	$db->sql_freeresult($result);
}

if ($post_data['poster_id'] == ANONYMOUS)
{
	$post_data['username'] = ($mode == 'quote' || $mode == 'edit') ? trim($post_data['post_username']) : '';
}
else
{
	$post_data['username'] = ($mode == 'quote' || $mode == 'edit') ? trim($post_data['username']) : '';
}

$post_data['enable_urls'] = $post_data['enable_magic_url'];

if ($mode != 'edit')
{
	$post_data['enable_sig']		= ($config['allow_sig'] && $user->optionget('attachsig')) ? true: false;
	$post_data['enable_smilies']	= ($config['allow_smilies'] && $user->optionget('smilies')) ? true : false;
	$post_data['enable_bbcode']		= ($config['allow_bbcode'] && $user->optionget('bbcode')) ? true : false;
	$post_data['enable_urls']		= true;
}

$post_data['enable_magic_url'] = $post_data['drafts'] = false;

// User own some drafts?
if ($user->data['is_registered'] && $auth->acl_get('u_savedrafts') && ($mode == 'reply' || $mode == 'post' || $mode == 'quote'))
{
	$sql = 'SELECT draft_id
		FROM ' . DRAFTS_TABLE . '
		WHERE user_id = ' . $user->data['user_id'] .
			(($forum_id) ? ' AND forum_id = ' . (int) $forum_id : '') .
			(($topic_id) ? ' AND topic_id = ' . (int) $topic_id : '') .
			(($draft_id) ? " AND draft_id <> $draft_id" : '');
	$result = $db->sql_query_limit($sql, 1);

	if ($db->sql_fetchrow($result))
	{
		$post_data['drafts'] = true;
	}
	$db->sql_freeresult($result);
}

$check_value = (($post_data['enable_bbcode']+1) << 8) + (($post_data['enable_smilies']+1) << 4) + (($post_data['enable_urls']+1) << 2) + (($post_data['enable_sig']+1) << 1);

// Check if user is watching this topic
if ($mode != 'post' && $config['allow_topic_notify'] && $user->data['is_registered'])
{
	$sql = 'SELECT topic_id
		FROM ' . TOPICS_WATCH_TABLE . '
		WHERE topic_id = ' . $topic_id . '
			AND user_id = ' . $user->data['user_id'];
	$result = $db->sql_query($sql);
	$post_data['notify_set'] = (int) $db->sql_fetchfield('topic_id');
	$db->sql_freeresult($result);
}

// Do we want to edit our post ?
if ($mode == 'edit' && $post_data['bbcode_uid'])
{
	$message_parser->bbcode_uid = $post_data['bbcode_uid'];
}

// HTML, BBCode, Smilies, Images and Flash status
$bbcode_status	= ($config['allow_bbcode'] && $auth->acl_get('f_bbcode', $forum_id)) ? true : false;
$smilies_status	= ($config['allow_smilies'] && $auth->acl_get('f_smilies', $forum_id)) ? true : false;
$img_status		= ($bbcode_status && $auth->acl_get('f_img', $forum_id)) ? true : false;
$url_status		= ($config['allow_post_links']) ? true : false;
$flash_status	= ($bbcode_status && $auth->acl_get('f_flash', $forum_id) && $config['allow_post_flash']) ? true : false;
$quote_status	= true;

// Save Draft
if ($save && $user->data['is_registered'] && $auth->acl_get('u_savedrafts') && ($mode == 'reply' || $mode == 'post' || $mode == 'quote'))
{
	$subject = utf8_normalize_nfc(request_var('subject', '', true));
	$subject = (!$subject && $mode != 'post') ? $post_data['topic_title'] : $subject;
	$message = utf8_normalize_nfc(request_var('message', '', true));

	if ($subject && $message)
	{
		if (confirm_box(true))
		{
			$sql = 'INSERT INTO ' . DRAFTS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
				'user_id'		=> (int) $user->data['user_id'],
				'topic_id'		=> (int) $topic_id,
				'forum_id'		=> (int) $forum_id,
				'save_time'		=> (int) $current_time,
				'draft_subject'	=> (string) $subject,
				'draft_message'	=> (string) $message)
			);
			$db->sql_query($sql);

			$meta_info = ($mode == 'post') ? append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id) : append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;t=$topic_id");

			meta_refresh(3, $meta_info);

			$message = $user->lang['DRAFT_SAVED'] . '<br /><br />';
			$message .= ($mode != 'post') ? sprintf($user->lang['RETURN_TOPIC'], '<a href="' . $meta_info . '">', '</a>') . '<br /><br />' : '';
			$message .= sprintf($user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id) . '">', '</a>');

			trigger_error($message);
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(array(
				'mode'		=> $mode,
				'save'		=> true,
				'f'			=> $forum_id,
				't'			=> $topic_id,
				'subject'	=> $subject,
				'message'	=> $message,
				'attachment_data' => $message_parser->attachment_data,
				)
			);

			$hidden_fields = array(
				'icon_id'			=> 0,

				'disable_bbcode'	=> false,
				'disable_smilies'	=> false,
				'disable_magic_url'	=> false,
				'attach_sig'		=> true,
				'lock_topic'		=> false,

				'topic_type'		=> POST_NORMAL,
				'topic_time_limit'	=> 0,

				'poll_title'		=> '',
				'poll_option_text'	=> '',
				'poll_max_options'	=> 1,
				'poll_length'		=> 0,
				'poll_vote_change'	=> false,
			);

			foreach ($hidden_fields as $name => $default)
			{
				if (!isset($_POST[$name]))
				{
					// Don't include it, if its not available
					unset($hidden_fields[$name]);
					continue;
				}

				if (is_bool($default))
				{
					// Use the string representation
					$hidden_fields[$name] = request_var($name, '');
				}
				else
				{
					$hidden_fields[$name] = request_var($name, $default);
				}
			}

			$s_hidden_fields .= build_hidden_fields($hidden_fields);

			confirm_box(false, 'SAVE_DRAFT', $s_hidden_fields);
		}
	}
	else
	{
		if (utf8_clean_string($subject) === '') //draft
		{
			$error[] = $user->lang['EMPTY_SUBJECT'];
		}

		if (utf8_clean_string($message) === '')
		{
			$error[] = $user->lang['TOO_FEW_CHARS'];
		}
	}
	unset($subject, $message);
}

// Load requested Draft
if ($draft_id && ($mode == 'reply' || $mode == 'quote' || $mode == 'post') && $user->data['is_registered'] && $auth->acl_get('u_savedrafts'))
{
	$sql = 'SELECT draft_subject, draft_message
		FROM ' . DRAFTS_TABLE . "
		WHERE draft_id = $draft_id
			AND user_id = " . $user->data['user_id'];
	$result = $db->sql_query_limit($sql, 1);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if ($row)
	{
		$post_data['post_subject'] = $row['draft_subject'];
		$message_parser->message = $row['draft_message'];

		$template->assign_var('S_DRAFT_LOADED', true);
	}
	else
	{
		$draft_id = 0;
	}
}

// Load draft overview
if ($load && ($mode == 'reply' || $mode == 'quote' || $mode == 'post') && $post_data['drafts'])
{
	load_drafts($topic_id, $forum_id);
}


if ($submit || $preview || $refresh)
{
	$post_data['topic_cur_post_id']	= request_var('topic_cur_post_id', 0);
	$post_data['post_subject']		= utf8_normalize_nfc(request_var('subject', '', true));
	$message_parser->message		= utf8_normalize_nfc(request_var('message', '', true));

	$post_data['username']			= utf8_normalize_nfc(request_var('username', $post_data['username'], true));
	$post_data['post_edit_reason']	= (!empty($_POST['edit_reason']) && $mode == 'edit' && $auth->acl_get('m_edit', $forum_id)) ? utf8_normalize_nfc(request_var('edit_reason', '', true)) : '';

	$post_data['orig_topic_type']	= $post_data['topic_type'];
	$post_data['topic_type']		= request_var('topic_type', (($mode != 'post') ? (int) $post_data['topic_type'] : POST_NORMAL));
	$post_data['topic_time_limit']	= request_var('topic_time_limit', (($mode != 'post') ? (int) $post_data['topic_time_limit'] : 0));

	if ($post_data['enable_icons'] && $auth->acl_get('f_icons', $forum_id))
	{
		$post_data['icon_id'] = request_var('icon', (int) $post_data['icon_id']);
	}

	$post_data['enable_bbcode']		= (!$bbcode_status || isset($_POST['disable_bbcode'])) ? false : true;
	$post_data['enable_smilies']	= (!$smilies_status || isset($_POST['disable_smilies'])) ? false : true;
	$post_data['enable_urls']		= (isset($_POST['disable_magic_url'])) ? 0 : 1;
	$post_data['enable_sig']		= (!$config['allow_sig'] || !$auth->acl_get('f_sigs', $forum_id) || !$auth->acl_get('u_sig')) ? false : ((isset($_POST['attach_sig']) && $user->data['is_registered']) ? true : false);

	if ($config['allow_topic_notify'] && $user->data['is_registered'])
	{
		$notify = (isset($_POST['notify'])) ? true : false;
	}
	else
	{
		$notify = false;
	}

	$topic_lock			= (isset($_POST['lock_topic'])) ? true : false;
	$post_lock			= (isset($_POST['lock_post'])) ? true : false;
	$poll_delete		= (isset($_POST['poll_delete'])) ? true : false;

	if ($submit)
	{
		$status_switch = (($post_data['enable_bbcode']+1) << 8) + (($post_data['enable_smilies']+1) << 4) + (($post_data['enable_urls']+1) << 2) + (($post_data['enable_sig']+1) << 1);
		$status_switch = ($status_switch != $check_value);
	}
	else
	{
		$status_switch = 1;
	}

	// Delete Poll
	if ($poll_delete && $mode == 'edit' && sizeof($post_data['poll_options']) &&
		((!$post_data['poll_last_vote'] && $post_data['poster_id'] == $user->data['user_id'] && $auth->acl_get('f_delete', $forum_id)) || $auth->acl_get('m_delete', $forum_id)))
	{
		if ($submit && check_form_key('posting'))
		{
			$sql = 'DELETE FROM ' . POLL_OPTIONS_TABLE . "
				WHERE topic_id = $topic_id";
			$db->sql_query($sql);

			$sql = 'DELETE FROM ' . POLL_VOTES_TABLE . "
				WHERE topic_id = $topic_id";
			$db->sql_query($sql);

			$topic_sql = array(
				'poll_title'		=> '',
				'poll_start' 		=> 0,
				'poll_length'		=> 0,
				'poll_last_vote'	=> 0,
				'poll_max_options'	=> 0,
				'poll_vote_change'	=> 0
			);

			$sql = 'UPDATE ' . TOPICS_TABLE . '
				SET ' . $db->sql_build_array('UPDATE', $topic_sql) . "
				WHERE topic_id = $topic_id";
			$db->sql_query($sql);
		}

		$post_data['poll_title'] = $post_data['poll_option_text'] = '';
		$post_data['poll_vote_change'] = $post_data['poll_max_options'] = $post_data['poll_length'] = 0;
	}
	else
	{
		$post_data['poll_title']		= utf8_normalize_nfc(request_var('poll_title', '', true));
		$post_data['poll_length']		= request_var('poll_length', 0);
		$post_data['poll_option_text']	= utf8_normalize_nfc(request_var('poll_option_text', '', true));
		$post_data['poll_max_options']	= request_var('poll_max_options', 1);
		$post_data['poll_vote_change']	= ($auth->acl_get('f_votechg', $forum_id) && $auth->acl_get('f_vote', $forum_id) && isset($_POST['poll_vote_change'])) ? 1 : 0;
	}

	// If replying/quoting and last post id has changed
	// give user option to continue submit or return to post
	// notify and show user the post made between his request and the final submit
	if (($mode == 'reply' || $mode == 'quote') && $post_data['topic_cur_post_id'] && $post_data['topic_cur_post_id'] != $post_data['topic_last_post_id'])
	{
		// Only do so if it is allowed forum-wide
		if ($post_data['forum_flags'] & FORUM_FLAG_POST_REVIEW)
		{
			if (topic_review($topic_id, $forum_id, 'post_review', $post_data['topic_cur_post_id']))
			{
				$template->assign_var('S_POST_REVIEW', true);
			}

			$submit = false;
			$refresh = true;
		}
	}

	// Parse Attachments - before checksum is calculated
	$message_parser->parse_attachments('fileupload', $mode, $forum_id, $submit, $preview, $refresh);

	// Grab md5 'checksum' of new message
	$message_md5 = md5($message_parser->message);

	// If editing and checksum has changed we know the post was edited while we're editing
	// Notify and show user the changed post
	if ($mode == 'edit' && $post_data['forum_flags'] & FORUM_FLAG_POST_REVIEW)
	{
		$edit_post_message_checksum = request_var('edit_post_message_checksum', '');
		$edit_post_subject_checksum = request_var('edit_post_subject_checksum', '');

		// $post_data['post_checksum'] is the checksum of the post submitted in the meantime
		// $message_md5 is the checksum of the post we're about to submit
		// $edit_post_message_checksum is the checksum of the post we're editing
		// ...

		// We make sure nobody else made exactly the same change
		// we're about to submit by also checking $message_md5 != $post_data['post_checksum']
		if (($edit_post_message_checksum !== '' && $edit_post_message_checksum != $post_data['post_checksum'] && $message_md5 != $post_data['post_checksum'])
		 || ($edit_post_subject_checksum !== '' && $edit_post_subject_checksum != $post_data['post_subject_md5'] && md5($post_data['post_subject']) != $post_data['post_subject_md5']))
		{
			if (topic_review($topic_id, $forum_id, 'post_review_edit', $post_id))
			{
				$template->assign_vars(array(
					'S_POST_REVIEW'			=> true,

					'L_POST_REVIEW'			=> $user->lang['POST_REVIEW_EDIT'],
					'L_POST_REVIEW_EXPLAIN'	=> $user->lang['POST_REVIEW_EDIT_EXPLAIN'],
				));
			}

			$submit = false;
			$refresh = true;
		}
	}

	// Check checksum ... don't re-parse message if the same
	$update_message = ($mode != 'edit' || $message_md5 != $post_data['post_checksum'] || $status_switch || strlen($post_data['bbcode_uid']) < BBCODE_UID_LEN) ? true : false;

	// Also check if subject got updated...
	$update_subject = $mode != 'edit' || ($post_data['post_subject_md5'] && $post_data['post_subject_md5'] != md5($post_data['post_subject']));

	// Parse message
	if ($update_message)
	{
		if (sizeof($message_parser->warn_msg))
		{
			$error[] = implode('<br />', $message_parser->warn_msg);
			$message_parser->warn_msg = array();
		}

		$message_parser->parse($post_data['enable_bbcode'], ($config['allow_post_links']) ? $post_data['enable_urls'] : false, $post_data['enable_smilies'], $img_status, $flash_status, $quote_status, $config['allow_post_links']);

		// On a refresh we do not care about message parsing errors
		if (sizeof($message_parser->warn_msg) && $refresh)
		{
			$message_parser->warn_msg = array();
		}
	}
	else
	{
		$message_parser->bbcode_bitfield = $post_data['bbcode_bitfield'];
	}

	if ($mode != 'edit' && !$preview && !$refresh && $config['flood_interval'] && !$auth->acl_get('f_ignoreflood', $forum_id))
	{
		// Flood check
		$last_post_time = 0;

		if ($user->data['is_registered'])
		{
			$last_post_time = $user->data['user_lastpost_time'];
		}
		else
		{
			$sql = 'SELECT post_time AS last_post_time
				FROM ' . POSTS_TABLE . "
				WHERE poster_ip = '" . $user->ip . "'
					AND post_time > " . ($current_time - $config['flood_interval']);
			$result = $db->sql_query_limit($sql, 1);
			if ($row = $db->sql_fetchrow($result))
			{
				$last_post_time = $row['last_post_time'];
			}
			$db->sql_freeresult($result);
		}

		if ($last_post_time && ($current_time - $last_post_time) < intval($config['flood_interval']))
		{
			$error[] = $user->lang['FLOOD_ERROR'];
		}
	}

	// Validate username
	if (($post_data['username'] && !$user->data['is_registered']) || ($mode == 'edit' && $post_data['poster_id'] == ANONYMOUS && $post_data['username'] && $post_data['post_username'] && $post_data['post_username'] != $post_data['username']))
	{
		include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

		$user->add_lang('ucp');

		if (($result = validate_username($post_data['username'], (!empty($post_data['post_username'])) ? $post_data['post_username'] : '')) !== false)
		{
			$error[] = $user->lang[$result . '_USERNAME'];
		}

		if (($result = validate_string($post_data['username'], false, $config['min_name_chars'], $config['max_name_chars'])) !== false)
		{
			$min_max_amount = ($result == 'TOO_SHORT') ? $config['min_name_chars'] : $config['max_name_chars'];
			$error[] = sprintf($user->lang['FIELD_' . $result], $user->lang['USERNAME'], $min_max_amount);
		}
	}

	if ($config['enable_post_confirm'] && !$user->data['is_registered'] && in_array($mode, array('quote', 'post', 'reply')))
	{
		$captcha_data = array(
			'message'	=> utf8_normalize_nfc(request_var('message', '', true)),
			'subject'	=> utf8_normalize_nfc(request_var('subject', '', true)),
			'username'	=> utf8_normalize_nfc(request_var('username', '', true)),
		);
		$vc_response = $captcha->validate($captcha_data);
		if ($vc_response)
		{
			$error[] = $vc_response;
		}
	}

	// check form
	if (($submit || $preview) && !check_form_key('posting'))
	{
		$error[] = $user->lang['FORM_INVALID'];
	}

	// Parse subject
	if (!$preview && !$refresh && utf8_clean_string($post_data['post_subject']) === '' && ($mode == 'post' || ($mode == 'edit' && $post_data['topic_first_post_id'] == $post_id)))
	{
		$error[] = $repl->lang('EMPTY_SUBJECT'); // link mod // circle mod
	}

	$post_data['poll_last_vote'] = (isset($post_data['poll_last_vote'])) ? $post_data['poll_last_vote'] : 0;

	if ($post_data['poll_option_text'] &&
		($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']/* && (!$post_data['poll_last_vote'] || $auth->acl_get('m_edit', $forum_id))*/))
		&& $auth->acl_get('f_poll', $forum_id))
	{
		$poll = array(
			'poll_title'		=> $post_data['poll_title'],
			'poll_length'		=> $post_data['poll_length'],
			'poll_max_options'	=> $post_data['poll_max_options'],
			'poll_option_text'	=> $post_data['poll_option_text'],
			'poll_start'		=> $post_data['poll_start'],
			'poll_last_vote'	=> $post_data['poll_last_vote'],
			'poll_vote_change'	=> $post_data['poll_vote_change'],
			'enable_bbcode'		=> $post_data['enable_bbcode'],
			'enable_urls'		=> $post_data['enable_urls'],
			'enable_smilies'	=> $post_data['enable_smilies'],
			'img_status'		=> $img_status
		);

		$message_parser->parse_poll($poll);

		$post_data['poll_options'] = (isset($poll['poll_options'])) ? $poll['poll_options'] : array();
		$post_data['poll_title'] = (isset($poll['poll_title'])) ? $poll['poll_title'] : '';

		/* We reset votes, therefore also allow removing options
		if ($post_data['poll_last_vote'] && ($poll['poll_options_size'] < $orig_poll_options_size))
		{
			$message_parser->warn_msg[] = $user->lang['NO_DELETE_POLL_OPTIONS'];
		}*/
	}
	else if ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'] && $auth->acl_get('f_poll', $forum_id))
	{
		// The user removed all poll options, this is equal to deleting the poll.
		$poll = array(
			'poll_title'		=> '',
			'poll_length'		=> 0,
			'poll_max_options'	=> 0,
			'poll_option_text'	=> '',
			'poll_start'		=> 0,
			'poll_last_vote'	=> 0,
			'poll_vote_change'	=> 0,
			'poll_options'		=> array(),
		);

		$post_data['poll_options'] = array();
		$post_data['poll_title'] = '';
		$post_data['poll_start'] = $post_data['poll_length'] = $post_data['poll_max_options'] = $post_data['poll_last_vote'] = $post_data['poll_vote_change'] = 0;
	}
	else if (!$auth->acl_get('f_poll', $forum_id) && ($mode == 'edit') && ($post_id == $post_data['topic_first_post_id']) && ($original_poll_data['poll_title'] != ''))
	{
		// We have a poll but the editing user is not permitted to create/edit it.
		// So we just keep the original poll-data.
		$poll = array_merge($original_poll_data, array(
			'enable_bbcode'		=> $post_data['enable_bbcode'],
			'enable_urls'		=> $post_data['enable_urls'],
			'enable_smilies'	=> $post_data['enable_smilies'],
			'img_status'		=> $img_status,
		));

		$message_parser->parse_poll($poll);

		$post_data['poll_options'] = (isset($poll['poll_options'])) ? $poll['poll_options'] : array();
		$post_data['poll_title'] = (isset($poll['poll_title'])) ? $poll['poll_title'] : '';
	}
	else
	{
		$poll = array();
	}

	// Check topic type
	if ($post_data['topic_type'] != POST_NORMAL && ($mode == 'post' || ($mode == 'edit' && $post_data['topic_first_post_id'] == $post_id)))
	{
		switch ($post_data['topic_type'])
		{
			case POST_GLOBAL:
			case POST_ANNOUNCE:
				$auth_option = 'f_announce';
			break;

			case POST_STICKY:
				$auth_option = 'f_sticky';
			break;

			default:
				$auth_option = '';
			break;
		}

		if (!$auth->acl_get($auth_option, $forum_id))
		{
			// There is a special case where a user edits his post whereby the topic type got changed by an admin/mod.
			// Another case would be a mod not having sticky permissions for example but edit permissions.
			if ($mode == 'edit')
			{
				// To prevent non-authed users messing around with the topic type we reset it to the original one.
				$post_data['topic_type'] = $post_data['orig_topic_type'];
			}
			else
			{
				$error[] = $user->lang['CANNOT_POST_' . str_replace('F_', '', strtoupper($auth_option))];
			}
		}
	}

	if (sizeof($message_parser->warn_msg))
	{
		$error[] = implode('<br />', $message_parser->warn_msg);
	}

	// DNSBL check
	if ($config['check_dnsbl'] && !$refresh)
	{
		if (($dnsbl = $user->check_dnsbl('post')) !== false)
		{
			$error[] = sprintf($user->lang['IP_BLACKLISTED'], $user->ip, $dnsbl[1]);
		}
	}
	
// calendar mod begin

	$cal_month		= request_var('cal_month', array(0 => 0));
	$cal_day		= request_var('cal_day', array(0 => 0));
	$cal_year		= request_var('cal_year', array(0 => 0));

	get_calendar_post($cal_month, $cal_day, $cal_year, $post_data, $error);
	// return in $post_data['calendar']
	
// calendar mod end

// locations mod begin

	$locations_a	= request_var('loc_a', array(0 => 0));
	$locations_b	= request_var('loc_b', array(0 => 0));
	$locations_c	= request_var('loc_c', array(0 => 0));
	
	$post_data['locations'] = array();
	
	if (sizeof($locations_a))
	{
		$sql = 'SELECT id, parent_id
			FROM ' . LOCATIONS_TABLE . '
			WHERE 1';	
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$location_parents[$row['id']] = $row['parent_id'];	
		}	
		$db->sql_freeresult($result);

		foreach ($locations_a as $key => $loc_a)
		{	
			if ($key >= $post_data['locations_per_topic'])
			{
				$error[] = sprintf($user->lang['MAX_LOCATIONS_EXCEDED'], $post_data['locations_per_topic']);
				break;
			}
			
			if (($locations_b[$key] && ($location_parents[$locations_b[$key]] != $loc_a)) || ($locations_c[$key] && ($location_parents[$locations_c[$key]] != $locations_b[$key])))
			{
				$error[] = $user->lang['LOCATION_MISMATCH'];
				break;
			}
			
			if ($loc_a)
			{
				$post_data['locations'][] = array(
					'location_a'	=> $loc_a,
					'location_b'	=> $locations_b[$key],
					'location_c'	=> $locations_c[$key],
					);
			}
		}
	}
	
// locations mod end

// topic color mod begin

	if($post_id == $post_data['topic_first_post_id'])
	{
		if ($config['topic_color_input'])
		{
			$post_data['topic_bg_color'] = request_var('topic_bg_color', 50);
			$post_data['topic_bg_color_dark'] = request_var('topic_bg_color_dark', 0);
		}
		else
		{
			$post_data['topic_bg_color'] = $post_data['forum_bg_color'];
			$post_data['topic_bg_color_dark'] = 0;		
		}
	}
	
// topic color mod end

// link mod begin

	$post_data['link_ids'] = request_var('attlink', array(0 => 0));

	$post_data['topic_link_url'] = request_var('topic_link_url', '');
	
	if ($links_forum && ($post_data['topic_link_url'] == '' || $post_data['topic_link_url'] == 'http://'))
	{
		$error[] = $user->lang['EMPTY_LINK_URL'];
	}

	$post_data['topic_link_status_id'] = request_var('link_status', 0);		
	$post_data['topic_link_type_id'] = request_var('link_type', 0);
	$post_data['topic_link_source_id'] = request_var('link_source', 0);
	$post_data['topic_link_forum_id'] = request_var('link_forum', 0);

// link mod end 


	// Store message, sync counters
	if (!sizeof($error) && $submit)
	{
		// Check if we want to de-globalize the topic... and ask for new forum
		if ($post_data['topic_type'] != POST_GLOBAL)
		{
			$sql = 'SELECT topic_type, forum_id
				FROM ' . TOPICS_TABLE . "
				WHERE topic_id = $topic_id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($row && !$row['forum_id'] && $row['topic_type'] == POST_GLOBAL)
			{
				$to_forum_id = request_var('to_forum_id', 0);

				if ($to_forum_id)
				{
					$sql = 'SELECT forum_type
						FROM ' . FORUMS_TABLE . '
						WHERE forum_id = ' . $to_forum_id;
					$result = $db->sql_query($sql);
					$forum_type = (int) $db->sql_fetchfield('forum_type');
					$db->sql_freeresult($result);

					if ($forum_type != FORUM_POST || !$auth->acl_get('f_post', $to_forum_id) || !$auth->acl_get('f_noapprove', $to_forum_id))
					{
						$to_forum_id = 0;
					}
				}

				if (!$to_forum_id)
				{
					include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

					$template->assign_vars(array(
						'S_FORUM_SELECT'	=> make_forum_select(false, false, false, true, true, true),
						'S_UNGLOBALISE'		=> true)
					);

					$submit = false;
					$refresh = true;
				}
				else
				{
					if (!$auth->acl_get('f_post', $to_forum_id))
					{
						// This will only be triggered if the user tried to trick the forum.
						trigger_error('NOT_AUTHORISED');
					}

					$forum_id = $to_forum_id;
				}
			}
		}

		if ($submit)
		{
			// Lock/Unlock Topic
			$change_topic_status = $post_data['topic_status'];
			$perm_lock_unlock = ($auth->acl_get('m_lock', $forum_id) || ($auth->acl_get('f_user_lock', $forum_id) && $user->data['is_registered'] && !empty($post_data['topic_poster']) && $user->data['user_id'] == $post_data['topic_poster'] && $post_data['topic_status'] == ITEM_UNLOCKED)) ? true : false;

			if ($post_data['topic_status'] == ITEM_LOCKED && !$topic_lock && $perm_lock_unlock)
			{
				$change_topic_status = ITEM_UNLOCKED;
			}
			else if ($post_data['topic_status'] == ITEM_UNLOCKED && $topic_lock && $perm_lock_unlock)
			{
				$change_topic_status = ITEM_LOCKED;
			}

			if ($change_topic_status != $post_data['topic_status'])
			{
				$sql = 'UPDATE ' . TOPICS_TABLE . "
					SET topic_status = $change_topic_status
					WHERE topic_id = $topic_id
						AND topic_moved_id = 0";
				$db->sql_query($sql);

				$user_lock = ($auth->acl_get('f_user_lock', $forum_id) && $user->data['is_registered'] && $user->data['user_id'] == $post_data['topic_poster']) ? 'USER_' : '';

				add_log('mod', $forum_id, $topic_id, 'LOG_' . $user_lock . (($change_topic_status == ITEM_LOCKED) ? 'LOCK' : 'UNLOCK'), $post_data['topic_title']);
			}

			// Lock/Unlock Post Edit
			if ($mode == 'edit' && $post_data['post_edit_locked'] == ITEM_LOCKED && !$post_lock && $auth->acl_get('m_edit', $forum_id))
			{
				$post_data['post_edit_locked'] = ITEM_UNLOCKED;
			}
			else if ($mode == 'edit' && $post_data['post_edit_locked'] == ITEM_UNLOCKED && $post_lock && $auth->acl_get('m_edit', $forum_id))
			{
				$post_data['post_edit_locked'] = ITEM_LOCKED;
			}

			$data = array(
				'topic_title'			=> (empty($post_data['topic_title'])) ? $post_data['post_subject'] : $post_data['topic_title'],
				'topic_first_post_id'	=> (isset($post_data['topic_first_post_id'])) ? (int) $post_data['topic_first_post_id'] : 0,
				'topic_last_post_id'	=> (isset($post_data['topic_last_post_id'])) ? (int) $post_data['topic_last_post_id'] : 0,
				'topic_time_limit'		=> (int) $post_data['topic_time_limit'],
				'topic_attachment'		=> (isset($post_data['topic_attachment'])) ? (int) $post_data['topic_attachment'] : 0,
				'post_id'				=> (int) $post_id,
				'topic_id'				=> (int) $topic_id,
				'forum_id'				=> (int) $forum_id,
				'icon_id'				=> (int) $post_data['icon_id'],
				'poster_id'				=> (int) $post_data['poster_id'],
				'enable_sig'			=> (bool) $post_data['enable_sig'],
				'enable_bbcode'			=> (bool) $post_data['enable_bbcode'],
				'enable_smilies'		=> (bool) $post_data['enable_smilies'],
				'enable_urls'			=> (bool) $post_data['enable_urls'],
				'enable_indexing'		=> (bool) $post_data['enable_indexing'],
				'message_md5'			=> (string) $message_md5,
				'post_time'				=> (isset($post_data['post_time'])) ? (int) $post_data['post_time'] : $current_time,
				'post_checksum'			=> (isset($post_data['post_checksum'])) ? (string) $post_data['post_checksum'] : '',
				'post_edit_reason'		=> $post_data['post_edit_reason'],
				'post_edit_user'		=> ($mode == 'edit') ? $user->data['user_id'] : ((isset($post_data['post_edit_user'])) ? (int) $post_data['post_edit_user'] : 0),
				'forum_parents'			=> $post_data['forum_parents'],
				'forum_name'			=> $post_data['forum_name'],
				'notify'				=> $notify,
				'notify_set'			=> $post_data['notify_set'],
				'poster_ip'				=> (isset($post_data['poster_ip'])) ? $post_data['poster_ip'] : $user->ip,
				'post_edit_locked'		=> (int) $post_data['post_edit_locked'],
				'bbcode_bitfield'		=> $message_parser->bbcode_bitfield,
				'bbcode_uid'			=> $message_parser->bbcode_uid,
				'message'				=> $message_parser->message,
				'attachment_data'		=> $message_parser->attachment_data,
				'filename_data'			=> $message_parser->filename_data,

				'topic_approved'		=> (isset($post_data['topic_approved'])) ? $post_data['topic_approved'] : false,
				'post_approved'			=> (isset($post_data['post_approved'])) ? $post_data['post_approved'] : false,
			);

// link mod begin

			$data['link_ids'] = $post_data['link_ids'];
			$data['topic_link_url'] = $post_data['topic_link_url'];
			$data['topic_link_status_id'] = $post_data['topic_link_status_id'];			
			$data['topic_link_type_id'] = $post_data['topic_link_type_id'];
			$data['topic_link_source_id'] = $post_data['topic_link_source_id'];
			$data['topic_link_forum_id'] = $post_data['topic_link_forum_id'];
			
// link mod end 
			
// topic color mod begin		
			
			$data['topic_bg_color'] = $post_data['topic_bg_color'];
			$data['topic_bg_color_dark'] = $post_data['topic_bg_color_dark'];

// topic color mod end			
			
// calendar mod begin

			$data['calendar'] = $post_data['calendar'];

// calendar mod end

// locations mod begin

			$data['locations'] = $post_data['locations'];

// locations mod end
		
// Topic Title Prefix Mod - Begin

			$data['topic_prefix_id'] = $title_prefix;
			
// Topic Title Prefix Mod - End

			if ($mode == 'edit')
			{
				$data['topic_replies_real'] = $post_data['topic_replies_real'];
				$data['topic_replies'] = $post_data['topic_replies'];
			}

			// Only return the username when it is either a guest posting or we are editing a post and
			// the username was supplied; otherwise post_data might hold the data of the post that is
			// being quoted (which could result in the username being returned being that of the quoted
			// post's poster, not the poster of the current post). See: PHPBB3-11769 for more information.
			$post_author_name = ((!$user->data['is_registered'] || $mode == 'edit') && $post_data['username'] !== '') ? $post_data['username'] : '';

			// The last parameter tells submit_post if search indexer has to be run
			$redirect_url = submit_post($mode, $post_data['post_subject'], $post_author_name, $post_data['topic_type'], $poll, $data, $update_message, ($update_message || $update_subject) ? true : false);

			if ($config['enable_post_confirm'] && !$user->data['is_registered'] && (isset($captcha) && $captcha->is_solved() === true) && ($mode == 'post' || $mode == 'reply' || $mode == 'quote'))
			{
				$captcha->reset();
			}

			// Check the permissions for post approval.
			// Moderators must go through post approval like ordinary users.
			if ((!$auth->acl_get('f_noapprove', $data['forum_id']) && empty($data['force_approved_state'])) || (isset($data['force_approved_state']) && !$data['force_approved_state']))
			{
				meta_refresh(10, $redirect_url);
				$message = ($mode == 'edit') ? $user->lang['POST_EDITED_MOD'] : $user->lang['POST_STORED_MOD'];
				$message .= (($user->data['user_id'] == ANONYMOUS) ? '' : ' '. $user->lang['POST_APPROVAL_NOTIFY']);
			}
			else
			{
				meta_refresh(3, $redirect_url);

				$message = ($mode == 'post') ? 'TOPIC_' : 'POST_';
				$message .= ($mode == 'edit') ? 'EDITED' : 'STORED';
				$message = $repl->lang($message) . '<br /><br />' . sprintf($repl->lang('VIEW_MESSAGE'), '<a href="' . $redirect_url . '">', '</a>');
			}

			$message .= '<br /><br />' . sprintf($repl->lang('RETURN_FORUM'), '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $data['forum_id']) . '">', '</a>');
			trigger_error($message);
		}
	}
}

// topic title prefix mod begin

$prefixes = $cache->obtain_prefixes();

// topic title prefix mod end 

// link mod begin

$topic_link = new topic_link(true, true, (!$links_forum), $prefixes);

// link mod end

// Preview
if (!sizeof($error) && $preview)
{
	$post_data['post_time'] = ($mode == 'edit') ? $post_data['post_time'] : $current_time;

	$preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

	$preview_signature = ($mode == 'edit') ? $post_data['user_sig'] : $user->data['user_sig'];
	$preview_signature_uid = ($mode == 'edit') ? $post_data['user_sig_bbcode_uid'] : $user->data['user_sig_bbcode_uid'];
	$preview_signature_bitfield = ($mode == 'edit') ? $post_data['user_sig_bbcode_bitfield'] : $user->data['user_sig_bbcode_bitfield'];

	// Signature
	if ($post_data['enable_sig'] && $config['allow_sig'] && $preview_signature && $auth->acl_get('f_sigs', $forum_id))
	{
		$parse_sig = new parse_message($preview_signature);
		$parse_sig->bbcode_uid = $preview_signature_uid;
		$parse_sig->bbcode_bitfield = $preview_signature_bitfield;

		// Not sure about parameters for bbcode/smilies/urls... in signatures
		$parse_sig->format_display($config['allow_sig_bbcode'], $config['allow_sig_links'], $config['allow_sig_smilies']);
		$preview_signature = $parse_sig->message;
		unset($parse_sig);
	}
	else
	{
		$preview_signature = '';
	}

	$preview_subject = censor_text($post_data['post_subject']);

	// Poll Preview
	if (!$poll_delete && ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']/* && (!$post_data['poll_last_vote'] || $auth->acl_get('m_edit', $forum_id))*/))
	&& $auth->acl_get('f_poll', $forum_id))
	{
		$parse_poll = new parse_message($post_data['poll_title']);
		$parse_poll->bbcode_uid = $message_parser->bbcode_uid;
		$parse_poll->bbcode_bitfield = $message_parser->bbcode_bitfield;

		$parse_poll->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies']);

		if ($post_data['poll_length'])
		{
			$poll_end = ($post_data['poll_length'] * 86400) + (($post_data['poll_start']) ? $post_data['poll_start'] : time());
		}

		$template->assign_vars(array(
			'S_HAS_POLL_OPTIONS'	=> (sizeof($post_data['poll_options'])),
			'S_IS_MULTI_CHOICE'		=> ($post_data['poll_max_options'] > 1) ? true : false,

			'POLL_QUESTION'		=> $parse_poll->message,

			'L_POLL_LENGTH'		=> ($post_data['poll_length']) ? sprintf($user->lang['POLL_RUN_TILL'], $user->format_date($poll_end)) : '',
			'L_MAX_VOTES'		=> ($post_data['poll_max_options'] == 1) ? $user->lang['MAX_OPTION_SELECT'] : sprintf($user->lang['MAX_OPTIONS_SELECT'], $post_data['poll_max_options']))
		);

		$parse_poll->message = implode("\n", $post_data['poll_options']);
		$parse_poll->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies']);
		$preview_poll_options = explode('<br />', $parse_poll->message);
		unset($parse_poll);

		foreach ($preview_poll_options as $key => $option)
		{
			$template->assign_block_vars('poll_option', array(
				'POLL_OPTION_CAPTION'	=> $option,
				'POLL_OPTION_ID'		=> $key + 1)
			);
		}
		unset($preview_poll_options);
	}

	// Attachment Preview
	if (sizeof($message_parser->attachment_data))
	{
		$template->assign_var('S_HAS_ATTACHMENTS', true);

		$update_count = array();
		$attachment_data = $message_parser->attachment_data;

		parse_attachments($forum_id, $preview_message, $attachment_data, $update_count, true);

		foreach ($attachment_data as $i => $attachment)
		{
			$template->assign_block_vars('attachment', array(
				'DISPLAY_ATTACHMENT'	=> $attachment)
			);
		}
		unset($attachment_data);
	}

	if (!sizeof($error))
	{
		$template->assign_vars(array(
			'PREVIEW_SUBJECT'		=> $preview_subject,
			'PREVIEW_MESSAGE'		=> $preview_message,
			'PREVIEW_SIGNATURE'		=> $preview_signature,

			'S_DISPLAY_PREVIEW'		=> true)
		);
	}
	
// link mod begin	

	if (sizeof($post_data['link_ids']))
	{
		$sql = 'SELECT t.topic_id, t.forum_id,
					t.topic_title, t.topic_prefix_id, t.topic_bg_color, t.topic_link_url, 
					t.topic_link_status_id, t.topic_link_type_id, 
					t.topic_link_source_id
				FROM ' . TOPICS_TABLE . ' t
				WHERE ' . $db->sql_in_set('t.topic_id', $post_data['link_ids']) . '
					AND t.topic_is_link = 1';

		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('link', array(
				'LINK_FOLDER_IMG'		=> $user->img('link'),
				'LINK_TITLE'			=> $row['topic_title'],
				'LINK_TITLE_PREFIX'		=> ($row['topic_prefix_id']) ? $prefixes[$row['topic_prefix_id']] : '',
				'U_LINK'				=> append_sid($phpbb_root_path . 'viewtopic.' . $phpEx, 't=' . $row['topic_id']. '&amp;f=' . $row['forum_id']),
				'U_LINK_URL'			=> $row['topic_link_url'],
				'LINK_STATUS_TEXT'		=> $topic_link->status[$row['topic_link_status_id']],
				'LINK_STATUS_CLASS'		=> $topic_link->status_class[$row['topic_link_status_id']],
				'LINK_TYPE_SOURCE_STR'	=> $topic_link->type_source_str($row['topic_link_type_id'], $row['topic_link_source_id']),
				'LINK_BG_COLOR'			=> $topic_color->obtain_color($row['topic_bg_color'], 'topic', $post_data['topic_bg_color_dark']),	
				));		
		}

		$db->sql_freeresult($result);
	}
	
// link mod end 	
	
}

// Decode text for message display
$post_data['bbcode_uid'] = ($mode == 'quote' && !$preview && !$refresh && !sizeof($error)) ? $post_data['bbcode_uid'] : $message_parser->bbcode_uid;
$message_parser->decode_message($post_data['bbcode_uid']);

if ($mode == 'quote' && !$submit && !$preview && !$refresh)
{
	if ($config['allow_bbcode'])
	{
		$message_parser->message = '[quote=&quot;' . $post_data['quote_username'] . '&quot;]' . censor_text(trim($message_parser->message)) . "[/quote]\n";
	}
	else
	{
		$offset = 0;
		$quote_string = "&gt; ";
		$message = censor_text(trim($message_parser->message));
		// see if we are nesting. It's easily tricked but should work for one level of nesting
		if (strpos($message, "&gt;") !== false)
		{
			$offset = 10;
		}
		$message = utf8_wordwrap($message, 75 + $offset, "\n");

		$message = $quote_string . $message;
		$message = str_replace("\n", "\n" . $quote_string, $message);
		$message_parser->message =  $post_data['quote_username'] . " " . $user->lang['WROTE'] . ":\n" . $message . "\n";
	}
}

if (($mode == 'reply' || $mode == 'quote') && !$submit && !$preview && !$refresh)
{
	$post_data['post_subject'] = ((strpos($post_data['post_subject'], 'Re: ') !== 0) ? 'Re: ' : '') . censor_text($post_data['post_subject']);
}

$attachment_data = $message_parser->attachment_data;
$filename_data = $message_parser->filename_data;
$post_data['post_text'] = $message_parser->message;

if (sizeof($post_data['poll_options']) || !empty($post_data['poll_title']))
{
	$message_parser->message = $post_data['poll_title'];
	$message_parser->bbcode_uid = $post_data['bbcode_uid'];

	$message_parser->decode_message();
	$post_data['poll_title'] = $message_parser->message;

	$message_parser->message = implode("\n", $post_data['poll_options']);
	$message_parser->decode_message();
	$post_data['poll_options'] = explode("\n", $message_parser->message);
}

// MAIN POSTING PAGE BEGINS HERE

// Forum moderators?
$moderators = array();
if ($config['load_moderators'])
{
	get_moderators($moderators, $forum_id);
}

// Generate smiley listing
generate_smilies('inline', $forum_id);

// Generate inline attachment select box
posting_gen_inline_attachments($attachment_data);

// Do show topic type selection only in first post.
$topic_type_toggle = false;

if ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']))
{
	$topic_type_toggle = posting_gen_topic_types($forum_id, $post_data['topic_type']);
}

$s_topic_icons = false;
if ($post_data['enable_icons'] && $auth->acl_get('f_icons', $forum_id))
{
	$s_topic_icons = posting_gen_topic_icons($mode, $post_data['icon_id']);
}

$bbcode_checked		= (isset($post_data['enable_bbcode'])) ? !$post_data['enable_bbcode'] : (($config['allow_bbcode']) ? !$user->optionget('bbcode') : 1);
$smilies_checked	= (isset($post_data['enable_smilies'])) ? !$post_data['enable_smilies'] : (($config['allow_smilies']) ? !$user->optionget('smilies') : 1);
$urls_checked		= (isset($post_data['enable_urls'])) ? !$post_data['enable_urls'] : 0;
$sig_checked		= $post_data['enable_sig'];
$lock_topic_checked	= (isset($topic_lock) && $topic_lock) ? $topic_lock : (($post_data['topic_status'] == ITEM_LOCKED) ? 1 : 0);
$lock_post_checked	= (isset($post_lock)) ? $post_lock : $post_data['post_edit_locked'];

// If the user is replying or posting and not already watching this topic but set to always being notified we need to overwrite this setting
$notify_set			= ($mode != 'edit' && $config['allow_topic_notify'] && $user->data['is_registered'] && !$post_data['notify_set']) ? $user->data['user_notify'] : $post_data['notify_set'];
$notify_checked		= (isset($notify)) ? $notify : (($mode == 'post') ? $user->data['user_notify'] : $notify_set);

// Page title & action URL
$s_action = append_sid("{$phpbb_root_path}posting.$phpEx", "mode=$mode&amp;f=$forum_id");
$s_action .= ($topic_id) ? "&amp;t=$topic_id" : '';
$s_action .= ($post_id) ? "&amp;p=$post_id" : '';

switch ($mode)
{
	case 'post':
		$page_title = $repl->lang('POST_TOPIC');
	break;

	case 'quote':
	case 'reply':
		$page_title = $user->lang['POST_REPLY'];
	break;

	case 'delete':
	case 'edit':
		$page_title = $repl->lang('EDIT_POST');
	break;
}

// Build Navigation Links
generate_forum_nav($post_data);

// Build Forum Rules
generate_forum_rules($post_data);

// calendar mod begin

$calendar_data = obtain_calendar_suffixes($topic_id);
	
// calendar mod end

// locations mod begin

$topic_locations = new topic_locations();
$topic_locations->set_topics($topic_id);

// locations mod end

// link mod // circle mod begin

$topic_thumbnail = new topic_thumbnail($topic_id);
$topic_thumbnail->set_extension($post_data['topic_thumbnail_ext']);

// link mod // circle mod end

// Posting uses is_solved for legacy reasons. Plugins have to use is_solved to force themselves to be displayed.
if ($config['enable_post_confirm'] && !$user->data['is_registered'] && (isset($captcha) && $captcha->is_solved() === false) && ($mode == 'post' || $mode == 'reply' || $mode == 'quote'))
{

	$template->assign_vars(array(
		'S_CONFIRM_CODE'			=> true,
		'CAPTCHA_TEMPLATE'			=> $captcha->get_template(),
	));
}

$s_hidden_fields = ($mode == 'reply' || $mode == 'quote') ? '<input type="hidden" name="topic_cur_post_id" value="' . $post_data['topic_last_post_id'] . '" />' : '';
$s_hidden_fields .= '<input type="hidden" name="lastclick" value="' . $current_time . '" />';
$s_hidden_fields .= ($draft_id || isset($_REQUEST['draft_loaded'])) ? '<input type="hidden" name="draft_loaded" value="' . request_var('draft_loaded', $draft_id) . '" />' : '';

if ($mode == 'edit')
{
	$s_hidden_fields .= build_hidden_fields(array(
		'edit_post_message_checksum'	=> $post_data['post_checksum'],
		'edit_post_subject_checksum'	=> $post_data['post_subject_md5'],
	));
}

// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
if (isset($captcha) && $captcha->is_solved() !== false)
{
	$s_hidden_fields .= build_hidden_fields($captcha->get_hidden_fields());
}

$form_enctype = (@ini_get('file_uploads') == '0' || strtolower(@ini_get('file_uploads')) == 'off' || !$config['allow_attachments'] || !$auth->acl_get('u_attach') || !$auth->acl_get('f_attach', $forum_id)) ? '' : ' enctype="multipart/form-data"';
add_form_key('posting');

// topic color mod begin

$topic_color->topic_template($post_data['topic_bg_color'], $post_data['topic_bg_color_dark']);

// topic color mod end	

// Start assigning vars for main posting page ...
$template->assign_vars(array(

// calendar mod begin

	'DATE_SUFFIX'				=> (isset($calendar_data[$topic_id]['suffix'])) ? $calendar_data[$topic_id]['suffix'] : '',      					
	'U_CALENDAR_TOPIC' 			=> (isset($calendar_data[$topic_id]['start_jd'])) ? append_sid($phpbb_root_path . 'calendar.' . $phpEx, 'start_jd=' . ($calendar_data[$topic_id]['start_jd'] - CALENDAR_DAYS_OFFSET) . '&amp;t=' . $topic_id) : '',
	
// calendar mod end

// locations mod begin

	'LOCATION_TAG_STRING'		=> 	$topic_locations->get_tag_string($topic_id),

// locations mod end

// link mod begin

	'S_LINKS_FORUM'			=> $links_forum,
	'U_LINK_URL'			=> $post_data['topic_link_url'],
	'LINK_STATUS_TEXT'		=> $topic_link->status[$post_data['topic_link_status_id']],
	'LINK_STATUS_CLASS'		=> $topic_link->status_class[$post_data['topic_link_status_id']],
	'LINK_TYPE_SOURCE_STR'	=> $topic_link->type_source_str($post_data['topic_link_type_id'], $post_data['topic_link_source_id']),
	'LINK_FORUM_STR'		=> $topic_link->forum_str($post_data['topic_link_forum_id']),
		
// link mod end

// circle mod begin

	'S_CIRCLES_FORUM'		=> $circles_forum,

// circle mod end

//	link mod begin // circle mod begin

	'S_LINKS_CIRCLES_FORUM'			=> $links_circles_forum,
	'TLC_SUBJECT'					=> $repl->lang('SUBJECT'),
	'TLC_ADD_ATTACHMENT_EXPLAIN' 	=> $repl->lang('ADD_ATTACHMENT_EXPLAIN'),
	'TOPIC_TITLE_THUMB'				=> ($links_circles_forum && $post_data['topic_thumbnail_ext']) ? $topic_thumbnail->get_img() : false,
	'LINK_CIRCLE_IMG'				=> ($links_forum) ? $user->img('link') : (($circles_forum) ? $user->img('circle') : ''),
	
// link mod end // circle mod end

// Topic Title Prefix Mod - Begin

	'TITLE_PREFIX'				=> ($post_data['topic_prefix_id']) ? $prefixes[$post_data['topic_prefix_id']] : '',
	
// Topic Title Prefix Mod - End

	'L_POST_A'					=> $page_title,
	'L_ICON'					=> ($mode == 'reply' || $mode == 'quote' || ($mode == 'edit' && $post_id != $post_data['topic_first_post_id'])) ? $user->lang['POST_ICON'] : $user->lang['TOPIC_ICON'],
	'L_MESSAGE_BODY_EXPLAIN'	=> (intval($config['max_post_chars'])) ? sprintf($user->lang['MESSAGE_BODY_EXPLAIN'], intval($config['max_post_chars'])) : '',

	'FORUM_NAME'			=> $post_data['forum_name'],
	'FORUM_DESC'			=> ($post_data['forum_desc']) ? generate_text_for_display($post_data['forum_desc'], $post_data['forum_desc_uid'], $post_data['forum_desc_bitfield'], $post_data['forum_desc_options']) : '',
	'TOPIC_TITLE'			=> censor_text($post_data['topic_title']),
	'MODERATORS'			=> (sizeof($moderators)) ? implode(', ', $moderators[$forum_id]) : '',
	'USERNAME'				=> ((!$preview && $mode != 'quote') || $preview) ? $post_data['username'] : '',
	'SUBJECT'				=> $post_data['post_subject'],
	'MESSAGE'				=> $post_data['post_text'],
	'BBCODE_STATUS'			=> ($bbcode_status) ? sprintf($user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>') : sprintf($user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>'),
	'IMG_STATUS'			=> ($img_status) ? $user->lang['IMAGES_ARE_ON'] : $user->lang['IMAGES_ARE_OFF'],
	'FLASH_STATUS'			=> ($flash_status) ? $user->lang['FLASH_IS_ON'] : $user->lang['FLASH_IS_OFF'],
	'SMILIES_STATUS'		=> ($smilies_status) ? $user->lang['SMILIES_ARE_ON'] : $user->lang['SMILIES_ARE_OFF'],
	'URL_STATUS'			=> ($bbcode_status && $url_status) ? $user->lang['URL_IS_ON'] : $user->lang['URL_IS_OFF'],
	'MAX_FONT_SIZE'			=> (int) $config['max_post_font_size'],
	'MINI_POST_IMG'			=> $user->img('icon_post_target', $user->lang['POST']),
	'POST_DATE'				=> ($post_data['post_time']) ? $user->format_date($post_data['post_time']) : '',
	'ERROR'					=> (sizeof($error)) ? implode('<br />', $error) : '',
	'TOPIC_TIME_LIMIT'		=> (int) $post_data['topic_time_limit'],
	'EDIT_REASON'			=> $post_data['post_edit_reason'],
	'U_VIEW_FORUM'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx", "f=$forum_id"),
	'U_VIEW_TOPIC'			=> ($mode != 'post') ? append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;t=$topic_id") : '',
	'U_PROGRESS_BAR'		=> append_sid("{$phpbb_root_path}posting.$phpEx", "f=$forum_id&amp;mode=popup"),
	'UA_PROGRESS_BAR'		=> addslashes(append_sid("{$phpbb_root_path}posting.$phpEx", "f=$forum_id&amp;mode=popup")),

	'S_PRIVMSGS'				=> false,
	'S_CLOSE_PROGRESS_WINDOW'	=> (isset($_POST['add_file'])) ? true : false,
	'S_EDIT_POST'				=> ($mode == 'edit') ? true : false,
	'S_EDIT_REASON'				=> ($mode == 'edit' && $auth->acl_get('m_edit', $forum_id)) ? true : false,
	'S_DISPLAY_USERNAME'		=> (!$user->data['is_registered'] || ($mode == 'edit' && $post_data['poster_id'] == ANONYMOUS)) ? true : false,
	'S_SHOW_TOPIC_ICONS'		=> $s_topic_icons,
	'S_DELETE_ALLOWED'			=> ($mode == 'edit' && (($post_id == $post_data['topic_last_post_id'] && $post_data['poster_id'] == $user->data['user_id'] && $auth->acl_get('f_delete', $forum_id) && !$post_data['post_edit_locked'] && ($post_data['post_time'] > time() - ($config['delete_time'] * 60) || !$config['delete_time'])) || $auth->acl_get('m_delete', $forum_id))) ? true : false,
	'S_BBCODE_ALLOWED'			=> ($bbcode_status) ? 1 : 0,
	'S_BBCODE_CHECKED'			=> ($bbcode_checked) ? ' checked="checked"' : '',
	'S_SMILIES_ALLOWED'			=> $smilies_status,
	'S_SMILIES_CHECKED'			=> ($smilies_checked) ? ' checked="checked"' : '',
	'S_SIG_ALLOWED'				=> ($auth->acl_get('f_sigs', $forum_id) && $config['allow_sig'] && $user->data['is_registered']) ? true : false,
	'S_SIGNATURE_CHECKED'		=> ($sig_checked) ? ' checked="checked"' : '',
	'S_NOTIFY_ALLOWED'			=> (!$user->data['is_registered'] || ($mode == 'edit' && $user->data['user_id'] != $post_data['poster_id']) || !$config['allow_topic_notify'] || !$config['email_enable']) ? false : true,
	'S_NOTIFY_CHECKED'			=> ($notify_checked) ? ' checked="checked"' : '',
	'S_LOCK_TOPIC_ALLOWED'		=> (($mode == 'edit' || $mode == 'reply' || $mode == 'quote') && ($auth->acl_get('m_lock', $forum_id) || ($auth->acl_get('f_user_lock', $forum_id) && $user->data['is_registered'] && !empty($post_data['topic_poster']) && $user->data['user_id'] == $post_data['topic_poster'] && $post_data['topic_status'] == ITEM_UNLOCKED))) ? true : false,
	'S_LOCK_TOPIC_CHECKED'		=> ($lock_topic_checked) ? ' checked="checked"' : '',
	'S_LOCK_POST_ALLOWED'		=> ($mode == 'edit' && $auth->acl_get('m_edit', $forum_id)) ? true : false,
	'S_LOCK_POST_CHECKED'		=> ($lock_post_checked) ? ' checked="checked"' : '',
	'S_LINKS_ALLOWED'			=> $url_status,
	'S_MAGIC_URL_CHECKED'		=> ($urls_checked) ? ' checked="checked"' : '',
	'S_TYPE_TOGGLE'				=> $topic_type_toggle,
	'S_SAVE_ALLOWED'			=> ($auth->acl_get('u_savedrafts') && $user->data['is_registered'] && $mode != 'edit') ? true : false,
	'S_HAS_DRAFTS'				=> ($auth->acl_get('u_savedrafts') && $user->data['is_registered'] && $post_data['drafts']) ? true : false,
	'S_FORM_ENCTYPE'			=> $form_enctype,

	'S_BBCODE_IMG'			=> $img_status,
	'S_BBCODE_URL'			=> $url_status,
	'S_BBCODE_FLASH'		=> $flash_status,
	'S_BBCODE_QUOTE'		=> $quote_status,

	'S_POST_ACTION'			=> $s_action,
	'S_HIDDEN_FIELDS'		=> $s_hidden_fields)
);

// calendar mod begin
				
if ($config['calendar_enable'] && $post_data['calendar_max_periods'] && ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
{
	$date_ary = array();
	
	if ($mode == 'edit' && !$preview && !$refresh)
	{
		$sql = 'SELECT start_jd, end_jd
			FROM ' . CALENDAR_PERIODS_TABLE . ' 
			WHERE topic_id = ' . $topic_id . '
			ORDER BY start_jd ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$greg = cal_from_jd($row['start_jd'], CAL_GREGORIAN);
			$date_ary[] = $greg['month'] . ', ' . $greg['day'] . ', ' . $greg['year'];
			$greg = cal_from_jd($row['end_jd'], CAL_GREGORIAN);
			$date_ary[] = $greg['month'] . ', ' . $greg['day'] . ', ' . $greg['year'];	
		}
		$db->sql_freeresult($result);	
	}
	else if (sizeof($post_data['calendar']))
	{
		foreach($post_data['calendar'] as $key => $cal_period)
		{
			if (isset($cal_period['start_jd']))
			{
				$greg = cal_from_jd($cal_period['start_jd'], CAL_GREGORIAN);
				$date_ary[] = $greg['month'] . ', ' . $greg['day'] . ', ' . $greg['year'];
			}
			else
			{
				break;
			}
			
			if (isset($cal_period['end_jd']))
			{
				$greg = cal_from_jd($cal_period['end_jd'], CAL_GREGORIAN);
				$date_ary[] = $greg['month'] . ', ' . $greg['day'] . ', ' . $greg['year'];
			}
			else
			{
				break;
			}
		}
	}

	$cal_months = array(
		'---',
		$user->lang['datetime']['Jan'],
		$user->lang['datetime']['Feb'],
		$user->lang['datetime']['Mar'],
		$user->lang['datetime']['Apr'],
		$user->lang['datetime']['May_short'],
		$user->lang['datetime']['Jun'],
		$user->lang['datetime']['Jul'],
		$user->lang['datetime']['Aug'],
		$user->lang['datetime']['Sep'],
		$user->lang['datetime']['Oct'],
		$user->lang['datetime']['Nov'],
		$user->lang['datetime']['Dec']
		);

	$min_start = $current_time - ($config['calendar_min_start'] * 86400);
	$max_start = $current_time + ($config['calendar_plus_start'] * 86400);
	
	$min_month = 1;
	$max_month = 12;
	
	$min_day = 1;
	$max_day = 31;	

	$min_year = (int) gmdate('Y', $min_start);
	$max_year = (int) gmdate('Y', $max_start);
	
	if ($min_year == $max_year)
	{
		$min_month = (int) gmdate('n', $min_start);
		$max_month = (int) gmdate('n', $max_start);
		
		if ($min_month == $max_month)
		{
			$min_day = (int) gmdate('j', $min_start);
			$max_day = (int) gmdate('j', $max_start);
		}
	}

	
	$month_options = $month_options_to = '<option value="0" selected="selected">---</option>';
	
	for ($i = $min_month; $i <= $max_month; $i++)
	{
		$month_options .= '<option value="' . $i . '">' . $cal_months[$i] . '</option>';
	}	
		
	$day_options = $day_options_to = '<option value="0" selected="selected">--</option>';
	
	for ($i = $min_day; $i <= $max_day; $i++)
	{
		$day_options .= '<option value="' . $i . '">' . $i . '</option>';
	}

	$year_options = $year_options_to = '<option value="0" selected="selected">----</option>';

	for ($i = $min_year; $i <= $max_year; $i++)
	{
		$year_options .= '<option value="' . $i . '">' . $i . '</option>';
	}
	

	$template->assign_vars(array(
		'CALENDAR_INPUT'				=> true,

		'S_CALENDAR_MONTH_OPTIONS'		=> $month_options,
		'S_CALENDAR_DAY_OPTIONS'		=> $day_options,
		'S_CALENDAR_YEAR_OPTIONS'		=> $year_options,

		'S_CALENDAR_MONTH_OPTIONS_TO'	=> $month_options_to,
		'S_CALENDAR_DAY_OPTIONS_TO'		=> $day_options_to,
		'S_CALENDAR_YEAR_OPTIONS_TO'	=> $year_options_to,

		'SCRIPT_MAX_PERIODS'			=> ' = ' . $post_data['calendar_max_periods'],
		'SCRIPT_MAX_PERIOD_LENGTH'		=> ' = ' . $post_data['calendar_max_period_length'],
		'SCRIPT_MIN_GAP' 				=> ' = ' . $post_data['calendar_min_gap'],
		'SCRIPT_MAX_GAP'				=> ' = ' . $post_data['calendar_max_gap'],
		'SCRIPT_MIN_START_MSEC'			=> ' = ' . ($post_data['calendar_min_start'] * 86400000),
		'SCRIPT_PLUS_START_MSEC'		=> ' = ' . ($post_data['calendar_plus_start'] * 86400000),
			
		'SCRIPT_DATE_ARY'				=> '[' . implode('], [', $date_ary) . ']',
		'SCRIPT_MONTH_OPTION_ARY'		=> '\'' . implode('\', \'', $cal_months) . '\'',
		'SCRIPT_CURRENT_TIME_MSEC'		=> ' = ' . ($current_time * 1000)
	));
}
else
{
	$template->assign_vars(array(
		'CALENDAR_INPUT'		=> false
	));
}
// calendar mod end


// locations mod begin

if ($config['location_tags_enable'] && $post_data['locations_per_topic'] && ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
{
	$loc_ary = array();
	
	if ($mode == 'edit' && !$preview && !$refresh)
	{
		$first = true;
		$sel_loc_a = $sel_loc_b = $sel_loc_c = 0;
	
		$sql = 'SELECT *
			FROM ' . TOPICS_LOCATIONS_TABLE . ' 
			WHERE topic_id = ' . $topic_id;
		$result = $db->sql_query($sql);	
		
		while ($row = $db->sql_fetchrow($result))
		{
			$loc_ary[] = $row['location_a'] . ', ' . $row['location_b'] . ', ' . $row['location_c'];
			if ($first)
			{
				$sel_loc_a = $row['location_a'];
				$sel_loc_b = $row['location_b'];
				$sel_loc_c = $row['location_c'];
				$first = false;
			}
		}	
		
		$db->sql_freeresult($result);		
	}
	else if (sizeof($post_data['locations']))
	{
		foreach ($post_data['locations'] as $row)
		{
			$loc_ary[] = $row['location_a'] . ', ' . $row['location_b'] . ', ' . $row['location_c'];
		}
		
		$sel_loc_a = $post_data['locations'][0]['location_a'];
		$sel_loc_b = $post_data['locations'][0]['location_b'];
		$sel_loc_c = $post_data['locations'][0]['location_c'];
	}

	$sql = 'SELECT *
		FROM ' . LOCATIONS_TABLE . ' 
		ORDER BY name ASC';
	$result = $db->sql_query($sql);

	$loc_a_ids = array(0);	
	$loc_b_ids = array(0);
	$loc_c_ids = array(0);
	
	$loc_b_parent_ids = array(0);	
	$loc_c_parent_ids = array(0);
	$loc_b_has_children = array(0);
	
	$loc_b_parent_indexes = array();
	$loc_c_parent_indexes = array();
	
	$loc_a_options = '<option value="0"';
	$loc_a_options .= ($sel_loc_a) ? '' : ' selected="selected"';
	$loc_a_options .= '></option>';
	$loc_b_options = '<option value="0"';
	$loc_b_options .= ($sel_loc_b) ? '' : ' selected="selected"';
	$loc_b_options .= '></option>';
	$loc_c_options = '<option value="0"';
	$loc_c_options .= ($sel_loc_c) ? '' : ' selected="selected"';
	$loc_c_options .= '></option>';

	while ($row = $db->sql_fetchrow($result))
	{
		$level = substr('aabc', $row['level'], 1);
		$var = 'loc_' . $level . '_options';
		$sel_loc = 'sel_loc_' . $level;
		${$var} .= '<option value="' . $row['id'] . '"';
		${$var} .= ((int) ${$sel_loc} == (int) $row['id']) ? ' selected="selected"' : '';
		${$var} .=	'>' . $row['name'] . ' - ' . $row['code'] . '</option>';		
		
		$var = 'loc_' . $level . '_ids'; 
		${$var}[] = $row['id'];		
	
		switch ($level)
		{
			case 'a' :
			
				break;
			case 'b' :
				$loc_b_has_children[] = $row['has_children'];
			case 'c' :
				$var = 'loc_' . $level . '_parent_ids';
				${$var}[] = $row['parent_id'];
		}	
	}
	$db->sql_freeresult($result);	
	
	$loc_a_flip = array_flip($loc_a_ids);
	$loc_b_flip = array_flip($loc_b_ids);
//	$loc_c_flip = array_flip($loc_c_ids);

	foreach ($loc_b_ids as $key => $id)
	{	
		$loc_b_parent_indexes[] = $loc_a_flip[$loc_b_parent_ids[$key]];
	}
	
	foreach ($loc_c_ids as $key => $id)
	{	
		$loc_c_parent_indexes[] = $loc_b_flip[$loc_c_parent_ids[$key]];
	}
	
	
	$location_hide_first = (sizeof($loc_ary) || $mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])) ? false : true; 

	$template->assign_vars(array(
		'LOCATION_TAGS_INPUT'			=> true,
		'LOCATION_HIDE_FIRST'			=> $location_hide_first, 

		'S_LOC_A_OPTIONS'				=> $loc_a_options,
		'S_LOC_B_OPTIONS'				=> $loc_b_options,
		'S_LOC_C_OPTIONS'				=> $loc_c_options,		

		'SCRIPT_LOC_B_PARENT_IDS_ARY'	=> implode(', ', $loc_b_parent_ids),
		'SCRIPT_LOC_C_PARENT_IDS_ARY'	=> implode(', ', $loc_c_parent_ids),
		'SCRIPT_LOC_B_HAS_CHILDREN_ARY'	=> implode(', ', $loc_b_has_children),
		'SCRIPT_LOC_B_PARENT_INDEX_ARY'	=> implode(', ', $loc_b_parent_indexes),
		'SCRIPT_LOC_C_PARENT_INDEX_ARY'	=> implode(', ', $loc_c_parent_indexes),		
		'SCRIPT_LOC_ARY'				=> '[' . implode('], [', $loc_ary) . ']',
		'SCRIPT_LOC_MAX'				=> ' = ' . $post_data['locations_per_topic'],	
	));
}
else
{
	$template->assign_vars(array(
		'LOCATION_TAGS_INPUT'		=> false,
	));
}

// locations mod end	


// link mod begin

if (!$links_forum && $config['link_forum_id'] && $config['links_per_post'] && ($mode == 'reply' || $mode == 'post' || $mode == 'edit'))
{
	$link_ary = array();

	if ($mode == 'edit' && !$preview && !$refresh)
	{
		$sql = 'SELECT pl.link_id
				FROM ' . POSTS_LINKS_TABLE . ' pl
				WHERE pl.post_id = ' . $post_id;

		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$link_ary[] = $row['link_id'];
		}

		$db->sql_freeresult($result);		
	}
	else 
	{
		$link_ary = ($post_data['link_ids']) ? $post_data['link_ids'] : array();
	}
	
	$loc_a_ids = array();

	$sql = 'SELECT id
		FROM ' . LOCATIONS_TABLE . ' 
		WHERE level = 1
		ORDER BY name ASC';
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$loc_a_select[$row['id']] = $topic_locations->locations[$row['id']];
		$loc_a_ids[] = $row['id'];
	}
	$db->sql_freeresult($result);		

	
	$link_filters = $topic_link->preselect_ary($loc_a_select);	

	foreach($link_filters as $filter => $filter_ary)
	{
		$option_ary = '<option value="0"';
		$option_ary .= ($post_data['topic_link_' . $filter . '_id']) ? '' : ' selected="selected"';
		$option_ary .= '>'  . $user->lang['ALL_LINKS'] . '</option>';
		
		if (sizeof($filter_ary))
		{
			foreach($filter_ary as $id => $name)
			{
				$option_ary .= '<option value="' . $id . '"';
				$option_ary .= ($post_data['topic_link_' . $filter . '_id'] == $id) ? ' selected="selected"' : '';
				$option_ary .= ($id == 'line') ? ' disabled="disabled"' : '';
				$option_ary .= '>' . $name . '</option>';
			}
		}

		$template->assign_vars(array(
		
			'LINK_' . strtoupper($filter) . '_FILTER_OPTIONS' => $option_ary,
		));	
	}	

	$options = '<option value="0"></option>';

	$sql = 'SELECT t.topic_id, 
			t.topic_title,  
			t.topic_link_status_id
		FROM ' . TOPICS_TABLE . ' t 
		WHERE t.topic_is_link = 1
			AND t.forum_id = ' . $config['link_forum_id'] . '
		ORDER BY t.topic_title ASC';
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_title = $row['topic_title'] . (($row['topic_link_status_id']) ? ' [' . $link_filters['status'][$row['topic_link_status_id']] . ']' : '');
		
		$options .= '<option ';
		$options .= 'value="' . $row['topic_id'] . '"';
		$options .= ($row['topic_id'] == $link_select)? ' selected="selected"' : '';
		$options .= '>' . $topic_title . '</option>';
	
	}	
	$db->sql_freeresult($result);	

	
	$template->assign_vars(array(
		'S_SHOW_LINKS_BOX'			=> true,
		'LINK_OPTIONS'				=> $options,
		'LINK_ARY'					=> implode(', ', $link_ary),

		'LINK_STATUS_STR'			=> $topic_link->status_str,
		'LINK_TYPE_STR'				=> $topic_link->type_str,
		'LINK_SOURCE_STR'			=> $topic_link->source_str,
		'LINK_FORUM_STR'			=> $topic_link->forum_str,
		'LINK_PREFIX_STR'			=> $topic_link->prefix_str,		
		'LINK_LOC_STR'				=> (sizeof($loc_a_ids)) ? implode('_', $loc_a_ids) : '',
		
		'LINK_LOC_A'				=> ($post_data['location_a1']) ? $post_data['location_a1'] : 0,
		'LINK_LOC_B'				=> ($post_data['location_b1']) ? $post_data['location_b1'] : 0,
		'LINK_LOC_C'				=> ($post_data['location_c1']) ? $post_data['location_c1'] : 0,
		
		'LINK_PREFIX_ID'			=> ($prefix_select) ? $prefix_select : 0,
		
		'FORUM_ID'					=> $forum_id,
		
		'LINK_FORUM_ID'				=> $config['link_forum_id'],
		'LINKS_PER_POST'			=> $config['links_per_post'],
		'PAGE_TIME'					=> time(),
	)); 

}


if ($links_forum && ($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
{
	$topic_link_url = ($mode == 'edit' || $preview || $refresh) ? $post_data['topic_link_url'] : 'http://';

	$link_filters = $topic_link->tag_ary();	
	
	foreach($link_filters as $filter => $filter_ary)
	{
		$option_ary = '<option value="0"';
		$option_ary .= ($post_data['topic_link_' . $filter . '_id']) ? '' : ' selected="selected"';
		$option_ary .= '> </option>';
		
		if (sizeof($filter_ary))
		{
			if ($filter == 'forum')
			{
				foreach($filter_ary as $id => $name)
				{
					$option_ary .= '<option value="' . $id . '"';
					$option_ary .= ($topic_link->forum_list[$id]['disabled']) ? ' disabled="disabled"' : '';
					$option_ary .= ($post_data['topic_link_' . $filter . '_id'] == $id) ? ' selected="selected"' : '';
					$option_ary .= '>' . $name . '</option>';
				}			
			}
			else
			{
				foreach($filter_ary as $id => $name)
				{
					$option_ary .= '<option value="' . $id . '"';
					$option_ary .= ($post_data['topic_link_' . $filter . '_id'] == $id) ? ' selected="selected"' : '';
					$option_ary .= '>' . $name . '</option>';
				}
			}
		}

		$template->assign_vars(array(
		
			'LINK_' . strtoupper($filter) . '_FILTER_OPTIONS' => $option_ary,
		)); 		
		
	}
	
	$template->assign_vars(array(
	
		'TOPIC_LINK_URL' => $topic_link_url,
	
	)); 
}

// link mod end


// Topic Title Prefix Mod - Begin

if (($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])) && $post_data['prefix_group_id'])
{	
	$ttp_select = '<option value="0"></option>';	
	$ttp_en = false;
	
	$sql = 'SELECT p.prefix_id, p.prefix_name
		FROM ' . TTP_PREFIXES_TABLE . ' p
		LEFT JOIN ' . TTP_PREFIXES_GROUPS_TABLE . ' pg ON (pg.prefix_id = p.prefix_id)
		WHERE pg.group_id = ' . $post_data['prefix_group_id'] . '
		ORDER BY p.prefix_id ASC';
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		$ttp_select .= '<option ';
		$ttp_select .= 'value="' . $row['prefix_id'] . '"';
		$ttp_select .= ($row['prefix_id'] == $prefix_select)? ' selected="selected"' : '';
		$ttp_select .= '>';
		$ttp_select .= $row['prefix_name'];
		$ttp_select .= '</option>';
		$ttp_en = true;
	}	
	$db->sql_freeresult($result);	

	$template->assign_vars(array(
		'S_PREFIX_FILTER'			=> $ttp_en,
		'S_PREFIX_SELECT_OPTIONS'	=> $ttp_select,
		)); 	
}
else
{
	$template->assign_vars(array(
		'S_PREFIX_FILTER'			=> false, 	
	)); 
}
	
// Topic Title Prefix Mod - End 

// topic color mod begin

if (($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])) && $topic_color && $config['topic_color_enable'] && $config['topic_color_input'])
{
	if ($mode == 'edit')
	{	
		$topic_bg_color = $post_data['topic_bg_color'];
		$topic_bg_color_dark = $post_data['topic_bg_color_dark'];
	}
	else
	{	
		$topic_bg_color = 50;
		$topic_bg_color_dark = 0;
	}

	$template->assign_vars(array(	
		'S_SHOW_TOPICS_COLORS_BOX'			=> true,
		'COLOR_SET_ARY'						=> $topic_color->get_script_ary(array('topic_a', 'topic_b', 'topic_c', 'topic_d')),		
		'S_TOPIC_BG_COLOR' 					=> $topic_bg_color,
		'S_TOPIC_BG_COLOR_DARK'				=> $topic_bg_color_dark
	)); 	
}

// topic color mod end


// Build custom bbcodes array
display_custom_bbcodes();

// Poll entry
if (($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']/* && (!$post_data['poll_last_vote'] || $auth->acl_get('m_edit', $forum_id))*/))
	&& $auth->acl_get('f_poll', $forum_id) && !$links_circles_forum) // link mod // circle mod
{
	$template->assign_vars(array(
		'S_SHOW_POLL_BOX'		=> true,
		'S_POLL_VOTE_CHANGE'	=> ($auth->acl_get('f_votechg', $forum_id) && $auth->acl_get('f_vote', $forum_id)),
		'S_POLL_DELETE'			=> ($mode == 'edit' && sizeof($post_data['poll_options']) && ((!$post_data['poll_last_vote'] && $post_data['poster_id'] == $user->data['user_id'] && $auth->acl_get('f_delete', $forum_id)) || $auth->acl_get('m_delete', $forum_id))),
		'S_POLL_DELETE_CHECKED'	=> (!empty($poll_delete)) ? true : false,

		'L_POLL_OPTIONS_EXPLAIN'	=> sprintf($user->lang['POLL_OPTIONS_' . (($mode == 'edit') ? 'EDIT_' : '') . 'EXPLAIN'], $config['max_poll_options']),

		'VOTE_CHANGE_CHECKED'	=> (!empty($post_data['poll_vote_change'])) ? ' checked="checked"' : '',
		'POLL_TITLE'			=> (isset($post_data['poll_title'])) ? $post_data['poll_title'] : '',
		'POLL_OPTIONS'			=> (!empty($post_data['poll_options'])) ? implode("\n", $post_data['poll_options']) : '',
		'POLL_MAX_OPTIONS'		=> (isset($post_data['poll_max_options'])) ? (int) $post_data['poll_max_options'] : 1,
		'POLL_LENGTH'			=> $post_data['poll_length'])
	);
}

// Show attachment box for adding attachments if true
$allowed = ($auth->acl_get('f_attach', $forum_id) && $auth->acl_get('u_attach') && $config['allow_attachments'] && $form_enctype);

// Attachment entry
posting_gen_attachment_entry($attachment_data, $filename_data, $allowed);

// Output page ...
page_header($page_title, false);

$template->set_filenames(array(
	'body' => 'posting_body.html')
);

make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));

// Topic review
if ($mode == 'reply' || $mode == 'quote')
{
	if (topic_review($topic_id, $forum_id))
	{
		$template->assign_var('S_DISPLAY_REVIEW', true);
	}
}

page_footer();

/**
* Show upload popup (progress bar)
*/
function upload_popup($forum_style = 0)
{
	global $template, $user;

	($forum_style) ? $user->setup('posting', $forum_style) : $user->setup('posting');

	page_header($user->lang['PROGRESS_BAR'], false);

	$template->set_filenames(array(
		'popup'	=> 'posting_progress_bar.html')
	);

	$template->assign_vars(array(
		'PROGRESS_BAR'	=> $user->img('upload_bar', $user->lang['UPLOAD_IN_PROGRESS']))
	);

	$template->display('popup');

	garbage_collection();
	exit_handler();
}

/**
* Do the various checks required for removing posts as well as removing it
*/
function handle_post_delete($forum_id, $topic_id, $post_id, &$post_data)
{
	global $user, $db, $auth, $config;
	global $phpbb_root_path, $phpEx;

	// If moderator removing post or user itself removing post, present a confirmation screen
	if ($auth->acl_get('m_delete', $forum_id) || ($post_data['poster_id'] == $user->data['user_id'] && $user->data['is_registered'] && $auth->acl_get('f_delete', $forum_id) && $post_id == $post_data['topic_last_post_id'] && !$post_data['post_edit_locked'] && ($post_data['post_time'] > time() - ($config['delete_time'] * 60) || !$config['delete_time'])))
	{
		$s_hidden_fields = build_hidden_fields(array(
			'p'		=> $post_id,
			'f'		=> $forum_id,
			'mode'	=> 'delete')
		);

		if (confirm_box(true))
		{
			$data = array(
				'topic_first_post_id'	=> $post_data['topic_first_post_id'],
				'topic_last_post_id'	=> $post_data['topic_last_post_id'],
				'topic_replies_real'	=> $post_data['topic_replies_real'],
				'topic_approved'		=> $post_data['topic_approved'],
				'topic_type'			=> $post_data['topic_type'],
				'post_approved'			=> $post_data['post_approved'],
				'post_reported'			=> $post_data['post_reported'],
				'post_time'				=> $post_data['post_time'],
				'poster_id'				=> $post_data['poster_id'],
				'post_postcount'		=> $post_data['post_postcount']
			);

			$next_post_id = delete_post($forum_id, $topic_id, $post_id, $data);
			$post_username = ($post_data['poster_id'] == ANONYMOUS && !empty($post_data['post_username'])) ? $post_data['post_username'] : $post_data['username'];

			if ($next_post_id === false)
			{
				add_log('mod', $forum_id, $topic_id, 'LOG_DELETE_TOPIC', $post_data['topic_title'], $post_username);

				$meta_info = append_sid("{$phpbb_root_path}viewforum.$phpEx", "f=$forum_id");
				$message = $user->lang['POST_DELETED'];
			}
			else
			{
				add_log('mod', $forum_id, $topic_id, 'LOG_DELETE_POST', $post_data['post_subject'], $post_username);

				$meta_info = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$forum_id&amp;t=$topic_id&amp;p=$next_post_id") . "#p$next_post_id";
				$message = $user->lang['POST_DELETED'] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . $meta_info . '">', '</a>');
			}

			meta_refresh(3, $meta_info);
			$message .= '<br /><br />' . sprintf($user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $forum_id) . '">', '</a>');
			trigger_error($message);
		}
		else
		{
			confirm_box(false, 'DELETE_POST', $s_hidden_fields);
		}
	}

	// If we are here the user is not able to delete - present the correct error message
	if ($post_data['poster_id'] != $user->data['user_id'] && $auth->acl_get('f_delete', $forum_id))
	{
		trigger_error('DELETE_OWN_POSTS');
	}

	if ($post_data['poster_id'] == $user->data['user_id'] && $auth->acl_get('f_delete', $forum_id) && $post_id != $post_data['topic_last_post_id'])
	{
		trigger_error('CANNOT_DELETE_REPLIED');
	}

	trigger_error('USER_CANNOT_DELETE');
}

?>