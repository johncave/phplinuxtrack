# phplinuxtrack
PHPlinuxTrack is a simple PHP torrent statistics script designed to ease and beautify the display of the torrent files of a linux distro or other software project. It allows visitors to see important information about your project's torrents at a glance using its icon-based interface. It was created by [John Cave](https://johncave.co.nz) of the [OpenMandriva](https://www.openmandriva.org) project. 

Basically, PHPlinuxTrack makes a pretty display of the seeders, downloaders and total transfers of your torrent files. It does this using Bootstrap as the frontend, PHP as the scripting language, as Redis as a caching data store.

## Features
- Attractive, icon-based interface.
- Doesn't look like it's seen multiple decades.
- Magnet link generation.
- Asynchronous loading of the torrent data table.
- Very easy to add new torrents.
- All assets served by CDNJS or my own CDN.
- Easy to translate (if you feel the icon-based interface needs translating).

The requirements are:
 - redis
 - PHP5
 - Consider installing [phpredis](https://github.com/phpredis/phpredis) to get an extra speed boost.

## Installation
 - Unzip the files to your webroot, or a subdirectory thereof.
 - Upload your torrent files to the torrents/ subdirectory of the PHPlinuxTrack directory.
 - Set a suitable header message in inc/lang/en.php. 
 - Set colours in inc/config.php that fit with your project's website colour scheme.
 - If using Redis on localhost:6379, setup should now be complete. If not, you'll have to update the relevant settings in inc/config.php. 
 - If you want to use a funky directory for your torrents, you'll have to set this up in config.php, along with giving PHPlinuxTrack a suitable web path to link to downloads of the torrent files.
 - You can adjust caching parameters as you see fit. However, I recommend setting a value higher than 300 for the scrapeCache directive, to save the bandwidth of the generous tracker providers out there.

## Wishlist
- Support for Memcache as well as Redis.
- Changing the link in the Name column (between .torrent file or magnet link) based on number of seeders.
- Prettier loading animation.
- 

## Note
Placing non .torrent files in the torrent directory will cause no end of errors. I consider this software to be a beta, but as it does no writing, it shouldn't corrupt any data.
