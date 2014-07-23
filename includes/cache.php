<?php
/**
*
* @package acm
* @version $Id: cache.php 10291 2009-11-26 17:41:47Z rxu $
* @copyright (c) 2005 phpBB Group
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
* Class for grabbing/handling cached entries, extends acm_file or acm_db depending on the setup
* @package acm
*/
class cache extends acm
{

// link mod begin

	function obtain_link_filters()
	{
		if (($link_filters = $this->get('link_filters')) === false)
		{
			global $db;
			
			$link_filters = array();
	
			$sql = 'SELECT id, is_source, name
				FROM ' . LINK_TYPES_TABLE . '
				WHERE 1 ORDER BY id ASC';	
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$var = ($row['is_source']) ? 'source' : 'type';
				$link_filters[$var][$row['id']] = $row['name'];
			}	
			$db->sql_freeresult($result);

			$this->put('link_filters', $link_filters);
		}
		return $link_filters;
	}
	
// link mod end


// locations mod begin

	function obtain_locations()
	{
		if (($locations = $this->get('locations')) === false)
		{
			global $db;

			$locations[0] = '';
	
			$sql = 'SELECT id, name
				FROM ' . LOCATIONS_TABLE . '
				WHERE 1';	
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$locations[$row['id']] = $row['name'];	
			}	
			$db->sql_freeresult($result);

			$this->put('locations', $locations);
		}
		return $locations;
	}
	
// locations mod end

// topic title prefix mod begin

	function obtain_prefixes()
	{
		if (($prefixes = $this->get('prefixes')) === false)
		{
			global $db;

			$prefixes = array();
	
			$sql = 'SELECT prefix_id, prefix_name
				FROM ' . TTP_PREFIXES_TABLE;	
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$prefixes[$row['prefix_id']] = $row['prefix_name'];	
			}	
			$db->sql_freeresult($result);

			$this->put('prefixes', $prefixes);
		}
		return $prefixes;
	}
	
	function obtain_prefix_groups()
	{
		if (($prefix_groups = $this->get('prefix_groups')) === false)
		{
			global $db;

			$prefix_groups = array();
			
			$sql = 'SELECT prefix_id
				FROM ' . TTP_PREFIXES_TABLE;	
			$result = $db->sql_query($sql);
			
			while ($row = $db->sql_fetchrow($result))
			{
				$prefix_groups[0][] = $row['prefix_id'];	
			}
			
			$db->sql_freeresult($result);			

			$sql = 'SELECT prefix_id, group_id
				FROM ' . TTP_PREFIXES_GROUPS_TABLE;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$prefix_groups[$row['group_id']][] = $row['prefix_id'];
			}			
			
			$db->sql_freeresult($result);

			$this->put('prefix_groups', $prefix_groups);
		}
		return $prefix_groups;	
	
	}
	
// topic title prefix mod end


// topic color mod begin

	function obtain_color_sets()
	{
		if (($color_sets = $this->get('color_sets')) === false)
		{
			global $db;
			
			$sql = 'SELECT *
				FROM ' . COLOR_SETS_TABLE . ' 
				WHERE 1 = 1';
			$result = $db->sql_query($sql);

			$color_sets = array();
			
			while ($row = $db->sql_fetchrow($result))
			{
				for ($i = 0; $i < 8; $i++)
				{
					$color_sets[$row['id']][$i] = array(
						'r'	=> $row['r' . $i],
						'g'	=> $row['g' . $i],
						'b'	=> $row['b' . $i],
						);
				}
			}
			$db->sql_freeresult($result);

			$this->put('color_sets', $color_sets);
		}
		return $color_sets;
	}
	
// topic color mod end


	/**
	* Get config values
	*/
	function obtain_config()
	{
		global $db;

		if (($config = $this->get('config')) !== false)
		{
			$sql = 'SELECT config_name, config_value
				FROM ' . CONFIG_TABLE . '
				WHERE is_dynamic = 1';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);
		}
		else
		{
			$config = $cached_config = array();

			$sql = 'SELECT config_name, config_value, is_dynamic
				FROM ' . CONFIG_TABLE;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				if (!$row['is_dynamic'])
				{
					$cached_config[$row['config_name']] = $row['config_value'];
				}

				$config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);

			$this->put('config', $cached_config);
		}

		return $config;
	}

	/**
	* Obtain list of naughty words and build preg style replacement arrays for use by the
	* calling script
	*/
	function obtain_word_list()
	{
		global $db;

		if (($censors = $this->get('_word_censors')) === false)
		{
			$sql = 'SELECT word, replacement
				FROM ' . WORDS_TABLE;
			$result = $db->sql_query($sql);

			$censors = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$censors['match'][] = get_censor_preg_expression($row['word']);
				$censors['replace'][] = $row['replacement'];
			}
			$db->sql_freeresult($result);

			$this->put('_word_censors', $censors);
		}

		return $censors;
	}

	/**
	* Obtain currently listed icons
	*/
	function obtain_icons()
	{
		if (($icons = $this->get('_icons')) === false)
		{
			global $db;

			// Topic icons
			$sql = 'SELECT *
				FROM ' . ICONS_TABLE . '
				ORDER BY icons_order';
			$result = $db->sql_query($sql);

			$icons = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$icons[$row['icons_id']]['img'] = $row['icons_url'];
				$icons[$row['icons_id']]['width'] = (int) $row['icons_width'];
				$icons[$row['icons_id']]['height'] = (int) $row['icons_height'];
				$icons[$row['icons_id']]['display'] = (bool) $row['display_on_posting'];
			}
			$db->sql_freeresult($result);

			$this->put('_icons', $icons);
		}

		return $icons;
	}

	/**
	* Obtain ranks
	*/
	function obtain_ranks()
	{
		if (($ranks = $this->get('_ranks')) === false)
		{
			global $db;

			$sql = 'SELECT *
				FROM ' . RANKS_TABLE . '
				ORDER BY rank_min DESC';
			$result = $db->sql_query($sql);

			$ranks = array();
			while ($row = $db->sql_fetchrow($result))
			{
				if ($row['rank_special'])
				{
					$ranks['special'][$row['rank_id']] = array(
						'rank_title'	=>	$row['rank_title'],
						'rank_image'	=>	$row['rank_image']
					);
				}
				else
				{
					$ranks['normal'][] = array(
						'rank_title'	=>	$row['rank_title'],
						'rank_min'		=>	$row['rank_min'],
						'rank_image'	=>	$row['rank_image']
					);
				}
			}
			$db->sql_freeresult($result);

			$this->put('_ranks', $ranks);
		}

		return $ranks;
	}

	/**
	* Obtain allowed extensions
	*
	* @param mixed $forum_id If false then check for private messaging, if int then check for forum id. If true, then only return extension informations.
	*
	* @return array allowed extensions array.
	*/
	function obtain_attach_extensions($forum_id)
	{
		if (($extensions = $this->get('_extensions')) === false)
		{
			global $db;

			$extensions = array(
				'_allowed_post'	=> array(),
				'_allowed_pm'	=> array(),
			);

			// The rule is to only allow those extensions defined. ;)
			$sql = 'SELECT e.extension, g.*
				FROM ' . EXTENSIONS_TABLE . ' e, ' . EXTENSION_GROUPS_TABLE . ' g
				WHERE e.group_id = g.group_id
					AND (g.allow_group = 1 OR g.allow_in_pm = 1)';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$extension = strtolower(trim($row['extension']));

				$extensions[$extension] = array(
					'display_cat'	=> (int) $row['cat_id'],
					'download_mode'	=> (int) $row['download_mode'],
					'upload_icon'	=> trim($row['upload_icon']),
					'max_filesize'	=> (int) $row['max_filesize'],
					'allow_group'	=> $row['allow_group'],
					'allow_in_pm'	=> $row['allow_in_pm'],
				);

				$allowed_forums = ($row['allowed_forums']) ? unserialize(trim($row['allowed_forums'])) : array();

				// Store allowed extensions forum wise
				if ($row['allow_group'])
				{
					$extensions['_allowed_post'][$extension] = (!sizeof($allowed_forums)) ? 0 : $allowed_forums;
				}

				if ($row['allow_in_pm'])
				{
					$extensions['_allowed_pm'][$extension] = 0;
				}
			}
			$db->sql_freeresult($result);

			$this->put('_extensions', $extensions);
		}

		// Forum post
		if ($forum_id === false)
		{
			// We are checking for private messages, therefore we only need to get the pm extensions...
			$return = array('_allowed_' => array());

			foreach ($extensions['_allowed_pm'] as $extension => $check)
			{
				$return['_allowed_'][$extension] = 0;
				$return[$extension] = $extensions[$extension];
			}

			$extensions = $return;
		}
		else if ($forum_id === true)
		{
			return $extensions;
		}
		else
		{
			$forum_id = (int) $forum_id;
			$return = array('_allowed_' => array());

			foreach ($extensions['_allowed_post'] as $extension => $check)
			{
				// Check for allowed forums
				if (is_array($check))
				{
					$allowed = (!in_array($forum_id, $check)) ? false : true;
				}
				else
				{
					$allowed = true;
				}

				if ($allowed)
				{
					$return['_allowed_'][$extension] = 0;
					$return[$extension] = $extensions[$extension];
				}
			}

			$extensions = $return;
		}

		if (!isset($extensions['_allowed_']))
		{
			$extensions['_allowed_'] = array();
		}

		return $extensions;
	}

	/**
	* Obtain active bots
	*/
	function obtain_bots()
	{
		if (($bots = $this->get('_bots')) === false)
		{
			global $db;

			switch ($db->sql_layer)
			{
				case 'mssql':
				case 'mssql_odbc':
				case 'mssqlnative':
					$sql = 'SELECT user_id, bot_agent, bot_ip
						FROM ' . BOTS_TABLE . '
						WHERE bot_active = 1
					ORDER BY LEN(bot_agent) DESC';
				break;

				case 'firebird':
					$sql = 'SELECT user_id, bot_agent, bot_ip
						FROM ' . BOTS_TABLE . '
						WHERE bot_active = 1
					ORDER BY CHAR_LENGTH(bot_agent) DESC';
				break;

				// LENGTH supported by MySQL, IBM DB2 and Oracle for sure...
				default:
					$sql = 'SELECT user_id, bot_agent, bot_ip
						FROM ' . BOTS_TABLE . '
						WHERE bot_active = 1
					ORDER BY LENGTH(bot_agent) DESC';
				break;
			}
			$result = $db->sql_query($sql);

			$bots = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$bots[] = $row;
			}
			$db->sql_freeresult($result);

			$this->put('_bots', $bots);
		}

		return $bots;
	}

	/**
	* Obtain cfg file data
	*/
	function obtain_cfg_items($theme)
	{
		global $config, $phpbb_root_path;

		$parsed_items = array(
			'theme'		=> array(),
			'template'	=> array(),
			'imageset'	=> array()
		);

		foreach ($parsed_items as $key => $parsed_array)
		{
			$parsed_array = $this->get('_cfg_' . $key . '_' . $theme[$key . '_path']);

			if ($parsed_array === false)
			{
				$parsed_array = array();
			}

			$reparse = false;
			$filename = $phpbb_root_path . 'styles/' . $theme[$key . '_path'] . '/' . $key . '/' . $key . '.cfg';

			if (!file_exists($filename))
			{
				continue;
			}

			if (!isset($parsed_array['filetime']) || (($config['load_tplcompile'] && @filemtime($filename) > $parsed_array['filetime'])))
			{
				$reparse = true;
			}

			// Re-parse cfg file
			if ($reparse)
			{
				$parsed_array = parse_cfg_file($filename);
				$parsed_array['filetime'] = @filemtime($filename);

				$this->put('_cfg_' . $key . '_' . $theme[$key . '_path'], $parsed_array);
			}
			$parsed_items[$key] = $parsed_array;
		}

		return $parsed_items;
	}

	/**
	* Obtain disallowed usernames
	*/
	function obtain_disallowed_usernames()
	{
		if (($usernames = $this->get('_disallowed_usernames')) === false)
		{
			global $db;

			$sql = 'SELECT disallow_username
				FROM ' . DISALLOW_TABLE;
			$result = $db->sql_query($sql);

			$usernames = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$usernames[] = str_replace('%', '.*?', preg_quote(utf8_clean_string($row['disallow_username']), '#'));
			}
			$db->sql_freeresult($result);

			$this->put('_disallowed_usernames', $usernames);
		}

		return $usernames;
	}

	/**
	* Obtain hooks...
	*/
	function obtain_hooks()
	{
		global $phpbb_root_path, $phpEx;

		if (($hook_files = $this->get('_hooks')) === false)
		{
			$hook_files = array();

			// Now search for hooks...
			$dh = @opendir($phpbb_root_path . 'includes/hooks/');

			if ($dh)
			{
				while (($file = readdir($dh)) !== false)
				{
					if (strpos($file, 'hook_') === 0 && substr($file, -(strlen($phpEx) + 1)) === '.' . $phpEx)
					{
						$hook_files[] = substr($file, 0, -(strlen($phpEx) + 1));
					}
				}
				closedir($dh);
			}

			$this->put('_hooks', $hook_files);
		}

		return $hook_files;
	}
}

?>