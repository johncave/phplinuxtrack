<?php
$starttime=microtime(true);
require_once ("./inc/func.php");
## Index.php - main file for PHPlinuxTracker ##

?>
<html>
<head>
<title><?=$CONFIG['title']?></title>

<link rel="stylesheet" href="//assets.johncave.co.nz/bootstrap/3.3.4/css/bootstrap.min.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<style>
<?php
printCSS();
?>
</style>
</head>
<?=$LANG['headhtml']?>

<center>
<div id="table">


</div>
</center>



<div id="attribution">
Powered by <a href="https://github.com/johncave/phplinuxtrack">PHPlinuxTrack 0.3</a>, created by <a href="https://johncave.co.nz/">John Cave</a>.<br />
Updated at <?=$redis->get('pltgt')?> UTC. Generated in <?php print round(microtime(true)-$starttime, 3)?> seconds.
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//assets.johncave.co.nz/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" >
$('#table').html("<img src='//assets.johncave.co.nz/images/loading.gif'/><br /><?=$LANG['loading']?>").load('/table.php');
console.log("Loading Table");
</script>

</html>
