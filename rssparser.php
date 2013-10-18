<?php

/**
 * Rssparser Laravel bundle
 *
 * @link 	https://github.com/subdesign/rssparser
 * @author  Barna Szalai <b.sz@devartpro.com>
 */

class Rssparser 
{

	public static function parse($feed_url, $count = 4)
	{
		if ($count == 0 ){
			$count  = 4;
		}
		$sp = IoC::resolve('simplepie');

		$sp->set_feed_url( $feed_url );
		$sp->enable_cache( Config::get('rssparser::rssparser.enable_cache') );
		$sp->set_cache_duration( Config::get('rssparser::rssparser.cache_duration') );
		$sp->set_cache_location( Config::get('rssparser::rssparser.cache_location') );		
		$sp->enable_order_by_date( Config::get('rssparser::rssparser.order_by_date') );		
		$success = $sp->init();

		if($success)
		{			
			if( Config::get('rssparser::rssparser.format') === 'html' )
			{
				$html = '';
				$html .= '<div id="'.Config::get('rssparser::rssparser.container_div_id').'"><ul>';

				foreach($sp->get_items(0, Config::get('rssparser::rssparser.item_count') ) as $item)
				{
					$html .= '<li><b>'.$item->get_title().'</b></li>';

					if( Bundle::exists('date') )
					{
						if( Config::get('rssparser::rssparser.use_daysago') )
						{
							$html .= '<li>'.Date::forge($item->get_date())->ago().'</li>'; 				
						}
						else
						{
							$html .= '<li>'.$item->get_date().'</li>'; 				
						}						
					}
					else
					{
						$html .= '<li>'.$item->get_date().'</li>'; 				
					}					

					if($enclosure = $item->get_enclosure())
					{
						$author = $item->get_author();
						$html .= '<li><img src="'.$enclosure->get_thumbnail().'" width="'.Config::get('rssparser::rssparser.thumbnail_x_size').'" height="'.Config::get('rssparser::rssparser.thumbnail_x_size').'"/>'.' '.HTML::link($author->get_link(), $author->get_name(), array('target' => '_blank')).'</li>';
					}

					$html .= '<li class="last">'.HTML::link($item->get_link(), 'More &raquo;', array('target' => '_blank')).'</li>';
						
				}
				$html .= '</ul></div>';
							
				return $html;
			}
			elseif( Config::get('rssparser::rssparser.format') === 'json' )
			{
				$result_json = array();
				foreach($sp->get_items(0, $count ) as $item)
				{
					$newitem = array(
						"title" => $item->get_title(),
						"description" => $item->get_description(),
						"date" => $item->get_date('j F Y | g:i a'),
						"author" => $item->get_author(),
						"thumbnail" => $item->get_enclosure()->get_link(),
						"thumbnail2" => $item->get_enclosure()->get_thumbnail(),
						"link" => $item->get_link(),
						"permalink" => $item->get_permalink(),
					);
					
					$result_json[] = $newitem;
				}	
				
				return $result_json;
			}
			else
			{
					
				return $sp->get_raw_data();
			}
		}	
		else
		{
			return 'Error reading rss';
		}
	}

}