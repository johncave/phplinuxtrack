<?php
## Configuration settings for PHPlinuxTracker ##

$CONFIG = array (
	"title" => "OpenMandriva Project - BitTorrent Tracker.",
	"lang" => "en",
	"langsAvailable" => array("en", "es"),
	"tordir" => "torrents/",
	"torwebdir" => "/torrents/",
	"redisHost" => "127.0.0.1",
	"redisPort" => 6379,
	"scrapeCache" => 900, #Cache the individual scrapes for this many seconds.
	"tableCache" => 180, #Cache the entire table for this many seconds.
	"highlightColour" => "#E2266E",
	"linkColour" => "#005C9D"
);
