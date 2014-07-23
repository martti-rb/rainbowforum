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

class acp_topic_color
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template, $cache;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_topic_color');
        $this->tpl_name     = 'acp_topic_color';
        $this->page_title   = 'ACP_TOPIC_COLOR_SETTINGS';
                            
        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			set_config('topic_color_enable', request_var('topic_color_enable', 0));
			set_config('topic_color_input', request_var('topic_color_input', 0));			
			set_config('topic_color_preset_min', request_var('topic_color_preset_min', 40));
			set_config('topic_color_preset_max', request_var('topic_color_preset_max', 60));
			set_config('forum_color_preset_min', request_var('forum_color_preset_min', 40));
			set_config('forum_color_preset_max', request_var('forum_color_preset_max', 60));			
					
			$delete = request_var('csd', array(0 => 0));

			if (sizeof($delete))
			{
				foreach ($delete as $color_set_id)
				{
					if (!color_set_id)
					{
						continue;
					}
					
					$db->sql_query('DELETE FROM ' . COLOR_SETS_TABLE . ' WHERE id = ' . $color_set_id);
					// $cache->destroy('color', COLOR_SETS_TABLE); */
				}
			}
		}

		$color_set_assign = array(
			'topic_a', 'topic_b', 'topic_c', 'topic_d',
			'quote_a', 'quote_b', 'quote_c', 'quote_d',
			'code_a', 'code_b',
			'attachment_a', 'attachment_b',
			'forum_a', 'forum_b',
			);
			
		$color_sets = array(0 => array(
			array('r' => 255, 'g' => 0, 'b' => 0),
			array('r' => 255, 'g' => 255, 'b' => 0),				
			array('r' => 0, 'g' => 255, 'b' => 0),
			array('r' => 0, 'g' => 255, 'b' => 255),
			array('r' => 0, 'g' => 0, 'b' => 255),
			array('r' => 255, 'g' => 0, 'b' => 255),
			array('r' => 127, 'g' => 127, 'b' => 127),
			array('r' => 127, 'g' => 127, 'b' => 127),									
			));	

		$color_set_names[0] = '';
		
		$sql = 'SELECT *
			FROM ' . COLOR_SETS_TABLE . ' 
			WHERE 1 = 1';
		$result = $db->sql_query($sql);

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
			
			$color_set_names[$row['id']] = $row['name'];
		}
		$db->sql_freeresult($result);

		if ($submit)
		{
			/* new color set */
			
			$new_color_set_name = request_var('new_color_set', '');
			$copy_from = request_var('color_set_copy', 0);
			
			if ($new_color_set_name != '')
			{
				$key = (array_key_exists($copy_from, $color_set_names)) ? $copy_from : 0;
				
				$sql_ary = array(
					'name'	=> $new_color_set_name,
					);

				for ($i = 0; $i < 8; $i++)
				{
					$sql_ary = array_merge($sql_ary, array(
						'r' . $i	=> $color_sets[$key][$i]['r'],
						'g' . $i	=> $color_sets[$key][$i]['g'],
						'b' . $i	=> $color_sets[$key][$i]['b'],						
						));
				}

				$db->sql_query('INSERT INTO ' . COLOR_SETS_TABLE . $db->sql_build_array('INSERT', $sql_ary));
				
				$new_set_id = $db->sql_nextid();
				
				$color_set_names[$new_set_id] = $new_color_set_name;
				$color_sets[$new_set_id] = $color_sets[$key];	
			}
		}

		$c_template = array();
		$topic_jpeg_output = array();
	
		foreach ($color_set_assign as $value)
		{
			$color_set_key = ($submit) ? request_var($value, 0) : $config['color_' . $value];
			$color_set_key = (array_key_exists($color_set_key, $color_set_names)) ? $color_set_key : 0;
			set_config('color_' . $value, $color_set_key);
			
			if (substr($value, 0, 5) == 'topic')
			{
				$topic_jpeg_output[$value] = $color_set_key;		
			}
			
			$color_set_options = '';

			foreach ($color_set_names as $color_set_id => $name)
			{
				$color_set_options .= '<option value="' . $color_set_id . '"';
				$color_set_options .= ($config['color_' . $value] == $color_set_id) ? ' selected="selected"' : '';
				$color_set_options .= '>' . $name;
				$color_set_options .= '</option>';
			}
			
			$c_template = array_merge($c_template, array(
				'COLOR_SET_OPTIONS_' . strtoupper($value) => $color_set_options,
				));
		}

		$editor = ($config['topic_color_editor']) ? unserialize($config['topic_color_editor']) : array();
		$editor_2 = ($config['topic_color_editor_2']) ? unserialize($config['topic_color_editor_2']) : array();	
	
		for ($i = 0; $i < 4; $i++)
		{
			$color_set_edit_id = ($submit) ? request_var('edit_' . $i, 0) : $editor['edit_' . $i];
			$editor['edit_' . $i] = $color_set_edit_id;
			
			$color_set_options = '';
			
			foreach ($color_set_names as $color_set_id => $name)
			{
				$color_set_options .= '<option value="' . $color_set_id . '"';
				$color_set_options .= ($color_set_edit_id == $color_set_id) ? ' selected="selected"' : '';
				$color_set_options .= '>' . $name;
				$color_set_options .= '</option>';
			}
			
			$c_template = array_merge($c_template, array(
				'COLOR_SET_OPTIONS_EDIT_' . strtoupper($i) => $color_set_options,
				));
		}	
		
		if ($submit)
		{	
			foreach ($color_set_names as $color_set_id => $name)
			{			
				$color_set_changed = request_var('hcsc' . $color_set_id, 0);

				if ($color_set_id && $color_set_changed)
				{	
					$sql = 'UPDATE ' . COLOR_SETS_TABLE . ' SET '; 
					
					for ($i = 0; $i < 8; $i++)
					{
						foreach(array('r', 'g', 'b') as $color_char)
						{
							$value = (int) request_var('hcs' . $color_set_id . $color_char . $i, 0);						
							$sql .= $color_char . $i . ' = ' . $value . ', ';
							$color_sets[$color_set_id][$i][$color_char] = $value;
						}					
					}

					$sql = rtrim($sql, ', ');
					$sql .= ' WHERE id = ' . $color_set_id;
					$db->sql_query($sql);					
				}
			}
			
			foreach($topic_jpeg_output as $topic_color_name => $color_set_id)
			{
				$img = imagecreatetruecolor(640, 1);
				
				for ($x = 0; $x < 640; $x++)
				{
					$f1 = ($x % 80) / 80;
					$f2 = (80 - ($x % 80)) / 80;
					$i1 = floor($x/80);
					$i1 = ($i1 < 8) ? $i1 : $i1 - 1;

					if ($i1 == 7)  
					{
						$red = $color_sets[$color_set_id][$i1]['r'];
						$green = $color_sets[$color_set_id][$i1]['g'];
						$blue = $color_sets[$color_set_id][$i1]['b'];
					}
					else 
					{
						$i2 = $i1 + 1;		
						$red = floor(($color_sets[$color_set_id][$i1]['r'] * $f2) + ($color_sets[$color_set_id][$i2]['r'] * $f1));
						$green = floor(($color_sets[$color_set_id][$i1]['g'] * $f2) + ($color_sets[$color_set_id][$i2]['g'] * $f1));
						$blue = floor(($color_sets[$color_set_id][$i1]['b'] * $f2) + ($color_sets[$color_set_id][$i2]['b'] * $f1)); 	
					}
				
					$coloral = imagecolorallocate($img, $red, $green, $blue);
					imagesetpixel($img, $x, 0, $coloral);
				}
				
				imagejpeg($img, $phpbb_root_path . 'styles/prosilver/imageset/bg_' . $topic_color_name . '.jpg');
				imagedestroy($img);
			}
		}
		
		$color_set_options = '';			
		$color_set_ids_ary = '';
		$color_set_names_ary = '';
		$color_set_ary = '';
		$color_set_changed_ary = '';
		
		foreach ($color_set_names as $color_set_id => $name)
		{
			$color_set_options .= '<option value="' . $color_set_id . '"';
			$color_set_options .= '>' . $name;
			$color_set_options .= '</option>';
			
			$color_set_ids_ary .= $color_set_id . ', '; 
			$color_set_names_ary .= '\'' . $name . '\', ';
			$color_set_ary .= '[';
			$color_set_changed_ary .= 0 . ', ';
			
			for ($i = 0; $i < 8; $i++)
			{
				$color_set_ary .= '[' . $color_sets[$color_set_id][$i]['r'];
				$color_set_ary .= ', ' . $color_sets[$color_set_id][$i]['g'];				
				$color_set_ary .= ', ' . $color_sets[$color_set_id][$i]['b'] . '], ';				
			}
			
			$color_set_ary = rtrim($color_set_ary, ', ');			
			$color_set_ary .= '], ';
		}
	
		$color_set_ids_ary = rtrim($color_set_ids_ary, ', ');
		$color_set_names_ary = rtrim($color_set_names_ary, ', ');
		$color_set_ary = rtrim($color_set_ary, ', ');
		$color_set_changed_ary = rtrim($color_set_changed_ary, ', ');			
		
		if ($submit)
		{	
			$editor['edit_focus'] = request_var('edit_focus', 0);			
			$editor['merge'] = request_var('merge', 100);
			$editor['edit_slider'] = request_var('edit_slider', 0);
			$editor['mod_slider'] = request_var('mod_slider', 4);
	
			$editor_2['edit_cb_m'] = request_var('edit_cb_m', '');
			$editor_2['edit_s_m'] = request_var('edit_s_m', '');
			$editor_2['edit_ch_m'] = request_var('edit_ch_m', '');
			$editor_2['edit_cb_s'] = request_var('edit_cb_s', '');
			$editor_2['edit_s_s'] = request_var('edit_s_s', '');
			$editor_2['edit_ch_s'] = request_var('edit_ch_s', '');
			$editor_2['edit_stf'] = request_var('edit_stf', 0);
		}

		set_config('topic_color_editor', serialize($editor));
		set_config('topic_color_editor_2', serialize($editor_2));		

		
		$template->assign_vars(array_merge($c_template, array(
			'TEST_PHP'					=> $test_php,
		
			'COLOR_SET_IDS_ARY'			=> $color_set_ids_ary,
			'COLOR_SET_NAMES_ARY'		=> $color_set_names_ary,
			'COLOR_SET_ARY'				=> $color_set_ary,
			'COLOR_SET_CHANGED_ARY'		=> $color_set_changed_ary,
			
			'EDIT_FOCUS'				=> $editor['edit_focus'],
			'MERGE_VALUE'				=> $editor['merge'],
			'EDIT_SLIDER'				=> $editor['edit_slider'],
			'MOD_SLIDER'				=> $editor['mod_slider'],

			'EDIT_CB_M'					=> $editor_2['edit_cb_m'], 
			'EDIT_S_M'					=> $editor_2['edit_s_m'],
			'EDIT_CH_M'					=> $editor_2['edit_ch_m'],
			'EDIT_CB_S'					=> $editor_2['edit_cb_s'],
			'EDIT_S_S'					=> $editor_2['edit_s_s'],
			'EDIT_CH_S'					=> $editor_2['edit_ch_s'],
			'EDIT_STF'					=> $editor_2['edit_stf'],

			'COLOR_SET_OPTIONS'			=> $color_set_options,
			'S_TOPIC_COLOR_ENABLE'		=> ($config['topic_color_enable']) ? true : false,
			'S_TOPIC_COLOR_INPUT'		=> ($config['topic_color_input']) ? true : false,			
			
			'TOPIC_COLOR_PRESET_MIN'	=> $config['topic_color_preset_min'],
			'TOPIC_COLOR_PRESET_MAX'	=> $config['topic_color_preset_max'],
			'FORUM_COLOR_PRESET_MIN'	=> $config['forum_color_preset_min'],
			'FORUM_COLOR_PRESET_MAX'	=> $config['forum_color_preset_max'],
			
			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			)));
    }
}

?>