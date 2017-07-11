<?php
use johncave\PhpLinuxTrack;

/**
 * @var PhpLinuxTrack\Language $oLanguage
 * @var PhpLinuxTrack\Redis|Redis $oRedis
 */
?>
<h1 class="page-header"><?= $oLanguage->item('heading') ?></h1>

<div class="text-center">
    <div id="table">
        <?php include __DIR__ . '/table.html.php' ?>
    </div>
</div>

<div id="attribution">
    <span>Powered by </span><a href="//github.com/johncave/phplinuxtrack">PHPlinuxTrack
        1.0.0</a><span>, created by </span>
    <a href="//johncave.co.nz">John Cave</a><span>.</span>
    <br/>
    <span>Updated at <?= $oRedis->get('pltgt') ?> UTC.</span>
    <span>Generated in <?= PhpLinuxTrack\Formatting::totalTime($startTime) ?> seconds.</span>
</div>

<script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>