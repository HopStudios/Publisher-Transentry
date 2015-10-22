<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require PATH_THIRD.'publisher/config.php';
require_once PATH_THIRD."publisher_transentry/config.php";

$plugin_info = array(
  'pi_name'			=> 'Publisher Transentry',
  'pi_version'		=> PUBLISHER_TRANSENTRY_VERSION,
  'pi_author' 		=> 'Louis Dekeister (Hop Studios)',
  'pi_author_url' 	=> 'http://www.hopstudios.com/software/',
  'pi_description' 	=> 'Tags utils for Publisher entries',
  'pi_usage' 		=> Publisher_transentry::usage()
);

class Publisher_transentry {

	private $entry_id;
	
    public function __construct()
    {
		$this->entry_id = intval(ee()->TMPL->fetch_param('entry_id', -1));
    }
	
	public function translations_list()
	{
		if ($this->entry_id == -1)
		{
			//return "<ul><li>No entry_id</li></ul>";
			return "";
		}
		
		$query = ee()->db->get_where('publisher_titles', array('entry_id' => $this->entry_id, 'publisher_status' => 'open'));
		
		$languages  = ee()->publisher_model->languages;
		
		$active_class = "current";
		$list_html = "<ul>\n";
		
		foreach ($query->result() as $row)
		{
			//$languages[i] = ( [id] => 1 [short_name] => en [long_name] => English [language_pack] => english [cat_url_indicator] => category [country] => [is_default] => y [is_enabled] => y [direction] => ltr [latitude] => [longitude] => [sites] => ["1"] [short_name_segment] => en )
			if ($languages[$row->publisher_lang_id]['is_enabled'] != 'y')
            {
				//do not display language if configured as is
                continue;
            }
			
			$active = ee()->publisher_lib->lang_id == $row->publisher_lang_id ? ' class="'. $active_class .'"' : '';
			//$list_html .= "<li>".$languages[$row->publisher_lang_id]['long_name']."</li>";
			$list_html .= '<li'. $active .'>'. $languages[$row->publisher_lang_id]['long_name'] .'</li>';
		}
		
		$list_html .= "</ul>\n";
		
		return $list_html;
	}
	
	public function translations_list_switcher()
	{
		if ($this->entry_id == -1)
		{
			//return "<ul><li>No entry_id</li></ul>";
			return "";
		}
		
		$query = ee()->db->get_where('publisher_titles', array('entry_id' => $this->entry_id, 'publisher_status' => 'open'));
		
		$languages  = ee()->publisher_model->languages;
		
		$url = ee()->publisher_helper_url->get_action('set_language', 'Publisher');
		$current_url_encoded = 'url='.base64_encode(ee()->publisher_session->get_current_url());
		
		$current_url = ee()->uri->uri_string;
		
		$active_class = "current";
		$list_html = "<ul>\n";
		
		foreach ($query->result() as $row)
		{
			//$languages[i] = ( [id] => 1 [short_name] => en [long_name] => English [language_pack] => english [cat_url_indicator] => category [country] => [is_default] => y [is_enabled] => y [direction] => ltr [latitude] => [longitude] => [sites] => ["1"] [short_name_segment] => en )
			if ($languages[$row->publisher_lang_id]['is_enabled'] != 'y')
            {
				//do not display language if configured as is
                continue;
            }
			
			$active = ee()->publisher_lib->lang_id == $row->publisher_lang_id ? ' class="'. $active_class .'"' : '';
			//This uses the Publisher action to redirect
			$list_html .= '<li'. $active .'><a href="'. $url .'&lang_id='. $row->publisher_lang_id .'&'. $current_url_encoded .'">'. $languages[$row->publisher_lang_id]['long_name'] .'</a></li>';
			//Manual version : take the root URL and prefix it with language
			//$list_html .= '<li'. $active .'><a href="/'. $languages[$row->publisher_lang_id]['short_name_segment'] . '/' . $current_url .'">'. $languages[$row->publisher_lang_id]['long_name'] .'</a></li>';
		}
		
		$list_html .= "</ul>\n";
		
		return $list_html;
	}
	
	function usage()
	{
		ob_start(); 
	?>

This plugin adds several handy tags for Publisher entries.

Tags
====
{exp:publisher_transentry:translations_list entry_id="1"}
Will display a list of available translations for the entry given. The `entry_id` parameter is mandatory.

{exp:publisher_transentry:translations_list_switcher entry_id="1"}
Will display a list of available translations for the entry given with a link to switch between them. The `entry_id` parameter is mandatory.

	<?php
	$buffer = ob_get_contents();

	ob_end_clean(); 

	return $buffer;
	}
}