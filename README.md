# phplinuxtrack

PHPlinuxTrack is a simple PHP torrent statistics script designed to ease and beautify the display of the torrent files of a linux distro or other software project.

It allows visitors to see important information about your project's torrents at a glance using its icon-based interface. It was created by [John Cave](https://johncave.co.nz) of the [OpenMandriva](https://www.openmandriva.org) project. 

Basically, PHPlinuxTrack makes a pretty display of the seeders, downloaders and total transfers of your torrent files. It does this using [Bootstrap] for the frontend, [PHP] as the scripting language, and [Redis] as a caching data store.

## Features
- Attractive, icon-based interface.
- Doesn't look like it's seen multiple decades.
- Magnet link generation.
- Very easy to add new torrents.
- All assets served by CDNs
- Easy to translate (if you feel the icon-based interface needs translating).
- Composer compatible
- PSR-2 and PSR-4 compliant

The requirements are:
 - redis
 - PHP5+
 - (optional) [PhpRedis] for a speed boost

## Installation
 - Unzip the files to your web root, or a subdirectory thereof.
 - Upload your torrent files to the `torrents/` subdirectory of the `public` directory.
 - Set a suitable header message in `resources/languages/.
 - If using Redis on `localhost:6379`, setup should now be complete. If not, you'll have to update the relevant settings in `config/.env`.
 - If you want to use a different directory for your torrents, you'll have to set this up in `config/.env`, along with giving PHPlinuxTrack a suitable web path to link to downloads of the torrent files.
 - You can adjust caching parameters as you see fit. However, I recommend setting a value higher than 300 for the scrapeCache directive, to save the bandwidth of the generous tracker providers out there.

## To Do
- Support for Memcache as well as Redis.
- Changing the link in the Name column (between `.torrent` file or [magnet] link) based on number of seeders.
- Prettier loading animation.

[PhpRedis]: https://github.com/phpredis/phpredis
[Bootstrap]: https://getbootstrap.com
[PHP]: https://php.net
[Redis]: https://redis.io
[magnet]: https://en.wikipedia.org/wiki/Magnet_URI_scheme
