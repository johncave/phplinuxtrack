# phplinuxtrack
PHPlinuxTrack is a simple PHP torrent display mechanism designed to ease and beautify the managing the torrent files of a linux distro or other software project. It was created by John Cave of the OpenMandriva project.

Basically, PHPlinuxTrack makes a pretty display of the seeders, downloaders and total transfers of your torrent files. It does this using Bootstrap as the frontend.

The requirements are:
 - phpredis
 - redis
 - (in future, memcache may be supported as an alternative to Redis)
 - PHP5

Installation:
 - Unzip the files to your webroot, or a subdirectory thereof.
 - Upload your torrent files to the torrents/ subdirectort of the PHPlinuxTrack directory.
 - If using Redis on localhost:6379, setup should now be complete. If not, you'll have to update the relevant settings in inc/config.php. 
 - If you want to use a funky directory for your torrents, you'll have to set this up in config.php, along with giving PHPlinuxTrack a suitable web path to serve downloads from that directory on.
 - You can adjust caching parametres as you see fit. However, I recommend against setting a value lower than 300 for the scrapeCache directive, to save the bandwidth of the generous tracker providers out there.
