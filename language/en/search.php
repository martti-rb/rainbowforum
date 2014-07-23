<?php
/**
*
* search [English]
*
* @package language
* @version $Id: search.php 10004 2009-08-17 13:25:04Z rxu $
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

// topic color mod begin

	'SORT_COLOR'			=> 'color',

// topic color mod end

	'ALL_AVAILABLE'			=> 'all available',
	'ALL_RESULTS'			=> 'all results',

	'DISPLAY_RESULTS'		=> 'display results as',

	'FOUND_SEARCH_MATCH'		=> 'search found %d match',
	'FOUND_SEARCH_MATCHES'		=> 'search found %d matches',
	'FOUND_MORE_SEARCH_MATCHES'	=> 'search found more than %d matches',

	'GLOBAL'				=> 'global announcement',

	'IGNORED_TERMS'			=> 'ignored',
	'IGNORED_TERMS_EXPLAIN'	=> 'the following words in your search query were ignored because they are too common words: <strong>%s</strong>.',

	'JUMP_TO_POST'			=> 'jump to post',

	'LOGIN_EXPLAIN_EGOSEARCH'	=> 'the board requires you to be registered and logged in to view your own posts.',
	'LOGIN_EXPLAIN_UNREADSEARCH'=> 'the board requires you to be registered and logged in to view your unread posts.',
	'LOGIN_EXPLAIN_NEWPOSTS'	=> 'the board requires you to be registered and logged in to view new posts since your last visit.',

	'MAX_NUM_SEARCH_KEYWORDS_REFINE'	=> 'you specified too many words to search for. Please do not enter more than %1$d words.',

	'NO_KEYWORDS'			=> 'you must specify at least one word to search for. Each word must consist of at least %d characters and must not contain more than %d characters excluding wildcards.',
	'NO_RECENT_SEARCHES'	=> 'no searches have been carried out recently.',
	'NO_SEARCH'				=> 'sorry but you are not permitted to use the search system.',
	'NO_SEARCH_RESULTS'		=> 'no suitable matches were found.',
	'NO_SEARCH_TIME'		=> 'sorry but you cannot use search at this time. Please try again in a few minutes.',
	'NO_SEARCH_UNREADS'		=> 'sorry but searching for unread posts has been disabled on this board.',
	'WORD_IN_NO_POST'		=> 'no posts were found because the word <strong>%s</strong> is not contained in any post.',
	'WORDS_IN_NO_POST'		=> 'no posts were found because the words <strong>%s</strong> are not contained in any post.',

	'POST_CHARACTERS'		=> 'characters of posts',
	
	'RECENT_SEARCHES'		=> 'recent searches',
	'RESULT_DAYS'			=> 'limit results to previous',
	'RESULT_SORT'			=> 'sort results by',
	'RETURN_FIRST'			=> 'return first',
	'RETURN_TO_SEARCH_ADV'	=> 'return to advanced search',

	'SEARCHED_FOR'				=> 'search term used',
	'SEARCHED_TOPIC'			=> 'searched topic',
	'SEARCH_ALL_TERMS'			=> 'search for all terms or use query as entered',
	'SEARCH_ANY_TERMS'			=> 'search for any terms',
	'SEARCH_AUTHOR'				=> 'search for author',
	'SEARCH_AUTHOR_EXPLAIN'		=> 'use * as a wildcard for partial matches.',
	'SEARCH_FIRST_POST'			=> 'first post of topics only',
	'SEARCH_FORUMS'				=> 'search in forums',
	'SEARCH_FORUMS_EXPLAIN'		=> 'select the forum or forums you wish to search in. subforums are searched automatically if you do not disable “search subforums“ below.',
	'SEARCH_IN_RESULTS'			=> 'search these results',
	'SEARCH_KEYWORDS_EXPLAIN'	=> 'place <strong>+</strong> in front of a word which must be found and <strong>-</strong> in front of a word which must not be found. put a list of words separated by <strong>|</strong> into brackets if only one of the words must be found. use * as a wildcard for partial matches.',
	'SEARCH_MSG_ONLY'			=> 'message text only',
	'SEARCH_OPTIONS'			=> 'search options',
	'SEARCH_QUERY'				=> 'search query',
	'SEARCH_SUBFORUMS'			=> 'search subforums',
	'SEARCH_TITLE_MSG'			=> 'post subjects and message text',
	'SEARCH_TITLE_ONLY'			=> 'topic titles only',
	'SEARCH_WITHIN'				=> 'search within',
	'SORT_ASCENDING'			=> 'ascending',
	'SORT_AUTHOR'				=> 'author',
	'SORT_DESCENDING'			=> 'descending',
	'SORT_FORUM'				=> 'forum',
	'SORT_POST_SUBJECT'			=> 'post subject',
	'SORT_TIME'					=> 'post time',

	'TOO_FEW_AUTHOR_CHARS'	=> 'you must specify at least %d characters of the authors name.',
));

?>