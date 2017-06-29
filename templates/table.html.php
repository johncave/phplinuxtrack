<?php
use johncave\PhpLinuxTrack;
use Scrapers\Trackers;

/**
 * @var PhpLinuxTrack\Language $oLanguage
 * @var PhpLinuxTrack\Redis|Redis $oRedis
 */
?>
<table class="table table-striped table-bordered">
    <colgroup>
        <col class="text-left">
        <col class="width-percent-8">
        <col class="width-percent-8">
        <col class="width-percent-8">
        <col class="width-percent-8">
        <col class="width-percent-10">
        <col class="width-percent-8">
    </colgroup>
    <tr>
        <th class='text-left'><?= $oLanguage->item('name') ?></th>
        <th class="text-center" title="<?= $oLanguage->item('seeders') ?>">
            <span class="fa fa-fw fa-arrow-up" aria-hidden="true"></span>
        </th>
        <th class="text-center" title="<?= $oLanguage->item('leechers') ?>">
            <span class="fa fa-fw fa-arrow-down" aria-hidden="true"></span>
        </th>
        <th class="text-center" title="<?= $oLanguage->item('size') ?>">
            <span class="fa fa-fw fa-hdd-o" aria-hidden="true"></span>
        </th>
        <th class="text-center" title="<?= $oLanguage->item('complete') ?>">
            <span class="fa fa-fw fa-check" aria-hidden="true"></span>
        </th>
        <th class="text-center" title="<?= $oLanguage->item('shared') ?>">
            <span class="fa fa-fw fa-line-chart" aria-hidden="true"></span>
        </th>
        <th class="text-center" title="<?= $oLanguage->item('get') ?>">
            <span class="fa fa-fw fa-download" aria-hidden="true"></span>
        </th>
    </tr>
    <?php

    // First off, see if the table is cached in Redis
    $sTable = $oRedis->get('pltt');

    // $age = time()-($redis -> get('plttage'));
    if ($sTable) {
        echo $sTable;
    } else {
        // Now start building rows for each Torrent file in the torrent directory. #
        $aFiles = array_diff(scandir($_ENV['TORRENT_DIRECTORY']), array('..', '.'));
        $sTable = "";

        foreach ($aFiles as $sFile) {
            $oTorrent = new Torrent($_ENV['TORRENT_DIRECTORY'] . $sFile);

            // Skip if not a valid torrent file or no trackers exist
            if ($oTorrent->announce() == null && isset($oTorrent->announce()[0][0])) {
                continue;
            }

            $sFirstAnnounce = $oTorrent->announce()[0][0];

            if (strpos($sFirstAnnounce, 'http') > -1) {
                $oScraper = new Trackers\HttpScraper();
            } elseif (strpos($sFirstAnnounce, 'udp')) {
                $oScraper = new Trackers\UdpScraper();
            } else {
                continue;
            }

            $aUdpResults = $oScraper->scrape($sFirstAnnounce, $oTorrent->hash_info());
            $aUdpScrapeResult = $aUdpResults[$oTorrent->hash_info()];

            if ($aUdpScrapeResult['completed'] == 0) {
                $completed = "?";
            } else {
                $completed = $aUdpScrapeResult['completed'];
            }

            // Generate size of torrent in bytes
            $sSizeInBytes = PhpLinuxTrack\Formatting::bytesToSize($oTorrent->size());

            // Generate torrent shared size in bytes/plain
            $iSharedSize = $oTorrent->size() * $aUdpScrapeResult['completed'];
            $sSharedSize = PhpLinuxTrack\Formatting::bytesToSize($iSharedSize);

            $sTable .= <<<HTML
<tr>
    <td class='text-left'>
        <a href="{$_ENV['TORRENT_WEB_DIRECTORY']}{$sFile}">{$oTorrent->name()}</a>
    </td>
    <td>
        <span>{$aUdpScrapeResult['seeders']}</span>
    </td>
    <td>
        <span>{$aUdpScrapeResult['leechers']}</span>
    </td>
    <td>
        <span>{$sSizeInBytes}</span>
    </td>
    <td>
        <span>{$completed}</span>
    </td>
    <td>
        <span>{$sSharedSize}</span>
    </td>
    <td>
        <span style='font-size:20px'>
            <a href="{$_ENV['TORRENT_WEB_DIRECTORY']}{$sFile}">
                <span class='glyphicon glyphicon-download-alt'
                      title="{$oLanguage->item('getFile')}" aria-hidden='true'></span>
            </a>
            <a href="{$oTorrent->magnet()}">
                <span class='glyphicon glyphicon-magnet'
                      title="{$oLanguage->item('getMagnet')}" aria-hidden='true'></span>
            </a>
        </span>
    </td>
</tr>
HTML;

        }

        // Store the table in Redis then print it.
        $oRedis->set('pltt', $sTable);
        $oRedis->expire('pltt', $_ENV['CACHE_TABLE']);
        $oRedis->set('pltgt', gmdate("H:i:s"));
    }
    ?>

    <?= $sTable ?>
</table>