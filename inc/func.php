<?php
## Include Configuration ##
include_once ("config.php");

# Include scraper classes #
include_once ("scrape/udptscraper.php");
include_once ("scrape/httptscraper.php");
#Include Torrent Parsing Class
#include_once ("bdecode.php");
include_once ("scrape/torrent.php");

# If you really want to change the language of the icon-based
# interface of PHPlinuxTrack, you can go ahead and modify or 
# swap this file out for another.
include_once ("lang/".$CONFIG['lang'].".php");



#Define variables ready for table creation to start
$redis = new Redis();
$redis -> connect($CONFIG['redisHost'], $CONFIG['redisPort']);
$offlineTrackers = 0;

# Define custom functions below #

function printCSS(){
global $CONFIG;
#Enter your own CSS here to customise the page.
print <<<EOHD
	html{
		padding: 7px;
	}	
	a{
		color: $CONFIG[highlightColour];
		/*text-shadow: 1px 0px 10px $CONFIG[linkColour];		*/
	}
	a:hover{
		color: $CONFIG[linkColour];
		text-shadow: 0px 0px 30px $CONFIG[highlightColour];
	}
	th {
		text-align:center;
		padding:7px;	
	}
	td {
		text-align:center;
		padding:5px;
	}
	table {
		border:2px solid #21242B;
		box-shadow: 3px 3px 6px #AFB3BD;
	}
	div#attribution{
		text-align:center;
		margin-top: 5%;
	}
	div#attribution a{
		color: purple;		
	}
	div#table{
		margin-top:5%;
	}
	.alignleft { text-align: left; }
	.alignleft a{ color: $CONFIG[linkColour]; text-shadow:none;}
	.alignleft a:hover{ color: $CONFIG[highlightColour];}




EOHD;
	
}

/**
 * Convert bytes to human readable format
 *
 * @param integer bytes Size in bytes to convert
 * @return string
 */
function bytesToSize($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}




function scrapeTorrent($url,$hash){
	global $redis, $offlineTrackers, $CONFIG;
			if($res = $redis->get("plts".$hash)){				
				return(json_decode($res, true));
	} else{			
				if(is_array($url)){
					$url = $url[0][0];					
				}
		try{			
			$timeout = 2;
			#$url = preg_replace("/announce", "", $url);
			$url = str_replace("/announce", "", $url);
			$scraper = new udptscraper($timeout);
			$ret = $scraper->scrape($url,array($hash));
			$redis -> set("plts".$hash, json_encode($ret));
			$redis -> setTimeout ("plts".$hash, $CONFIG['scrapeCache']);
			#print "<br />Scrape results for ".$url." were: ".json_encode($ret);
			return($ret);
		}
		catch(ScraperException $e){
			$ret=array( $hash => array(
			 "seeders" => "?",
			 "leechers" => "?",
			 "completed" => 0
				));
			$redis -> set("plts".$hash, json_encode($ret));
			$redis -> setTimeout ("plts".$hash, $CONFIG['scrapeCache']*3); #The caching length of trackers that seem down is increased to help them to recover if they're overloaded and to increase page load speed (timeouts are expensive to load speed.
			$offlineTrackers++;
			return $ret;
		}
	}
	
}
