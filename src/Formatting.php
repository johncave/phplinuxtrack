<?php

namespace johncave\PhpLinuxTrack;

use Scrapers\Trackers;

class Formatting
{
    public static function bytesToSize($bytes, $precision = 2)
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

    public static function totalTime($startTime = null)
    {
        return round(microtime(true) - $startTime, 3);
    }

    public static function scrapeTorrent(Redis $oRedis, $url, $hash)
    {
        if ($res = $oRedis->get("plts" . $hash)) {
            return (json_decode($res, true));
        } else {
            if (is_array($url)) {
                $url = $url[0][0];
            }
            try {
                $timeout = 2;
                #$url = preg_replace("/announce", "", $url);
                $url = str_replace("/announce", "", $url);
                $scraper = new Trackers\UdpScraper($timeout);
                $ret = $scraper->scrape($url, array($hash));
                $oRedis->set("plts" . $hash, json_encode($ret));
                $oRedis->expire("plts" . $hash, $_ENV['CACHE_SCRAPE']);
                #print "<br />Scrape results for ".$url." were: ".json_encode($ret);
                return ($ret);
            } catch (Trackers\Exception\ScraperException $e) {
                $ret = array(
                    $hash => array(
                        "seeders" => "?",
                        "leechers" => "?",
                        "completed" => 0
                    )
                );
                $oRedis->set("plts" . $hash, json_encode($ret));
                $oRedis->expire("plts" . $hash,
                    $_ENV['CACHE_SCRAPE'] * 3); #The caching length of trackers that seem down is increased to help them to recover if they're overloaded and to increase page load speed (timeouts are expensive to load speed.
                $offlineTrackers++;
                return $ret;
            }
        }

    }
}