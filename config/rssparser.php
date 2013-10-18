<?php 

return array(
	'feed_url'         => 'http://www.symptomfind.com/channel/diseases-conditions/feed/',
	'enable_cache'     => true,
	'cache_duration'   => 3600,  //The number of seconds to cache for. 60 is 1 minute, 600 is 10 minutes, 900 is 15 minutes, 1800 is 30 minutes, 3600 is 1 hour. 
	'cache_location'   => path('storage').'cache',
	'order_by_date'    => true,
	'container_div_id' => 'newsfeed',
	'item_count'       => 10,	// number of feed items 
	'format'		   => 'json',	 // raw / html /json
	'use_daysago'	   => false,		// needs "Date" bundle
	'thumbnail_x_size' => 30,
	'thumbnail_y_size' => 30,
);
