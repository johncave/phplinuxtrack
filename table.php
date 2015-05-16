<?php
include_once ('inc/func.php');
?>
<table width="75%" border="1">
	<colgroup>
		<col span="1" style="width:auto; text-align:left;">
		<col span="1" style="width:8%">
		<col span="1" style="width:8%">
		<col span="1" style="width:8%">
		<col span="1" style="width:8%">
		<col span="1" style="width:10%">
		<col span="1" style="width:8%">
	</colgroup>	

<?php # Print the table headers ?>
<tr>
<th class='alignleft'><?=$LANG['name']?></th>
<th title="<?=$LANG['seeders']?>"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></th>
<th title="<?=$LANG['leechers']?>"><span class="glyphicon glyphicon-arrow-down"  aria-hidden="true"></span></th>
<th title="<?=$LANG['size']?>"><span class="glyphicon glyphicon-hdd"  aria-hidden="true"></span></th>
<th title="<?=$LANG['complete']?>"><span class="glyphicon glyphicon-ok"  aria-hidden="true"></span></th>
<th title="<?=$LANG['shared']?>"><img src="//assets.johncave.co.nz/bootstrap/glyphs/share-alt.png" height="19px"  alt="" ></th>
<th title="<?=$LANG['get']?>"> <span class="glyphicon glyphicon-download"  aria-hidden="true"></span> </th>
</tr>


<?php
#First off, see if the table is cached in Redis
$table = $redis -> get('pltt');
#$age = time()-($redis -> get('plttage'));
if($table){
	#print "Served from Redis.";
	print $table;	
} else{
	#print "Creating new table";
	# Now start building rows for each Torrent file in the torrent directory. #
	$files = array_diff(scandir($CONFIG['tordir']), array('..', '.'));
	$table = "";
	#var_dump($files);

 
	foreach ($files as $file){
		$torrent = new Torrent($CONFIG['tordir'].$file);
		$scrape = scrapeTorrent($torrent -> announce(), $torrent->hash_info());
		#print json_encode($scrape);
		if($scrape[$torrent->hash_info()]['completed'] == 0){
			$completed = "?";		
		} else {
			$completed = $scrape[$torrent->hash_info()]['completed'];
		}
		$table .= "<tr>";
		$table .= "<td class='alignleft'><a href='".$CONFIG['torwebdir'].$file."'>".$torrent->name()."</a>";
		$table .= "<td>".$scrape[$torrent->hash_info()]['seeders']."</td>";
		$table .= "<td>".$scrape[$torrent->hash_info()]['leechers']."</td>";
		$table .= "<td> ".bytesToSize($torrent->size())." </td>";
		$table .= "<td> ".$completed." </td>";
		$shared = $torrent->size()*$scrape[$torrent->hash_info()]['completed'];	
		$table .= "<td> ".bytesToSize($shared)."</td>";
		$table .= "<td> <span style='font-size:20px'><a href='".$CONFIG['torwebdir'].$file."'><span class='glyphicon glyphicon-download-alt' title='".$LANG['getFile']."' aria-hidden='true'></span></a>  <a href='".$torrent->magnet()."'> <span class='glyphicon glyphicon-magnet' title='".$LANG['getMagnet']."' aria-hidden='true'></span></a></span></td>";
	
	
	
 		$table .= "</tr>\n";	
	
	
	}
	$table .= "</table>";
	#Store the table in Redis then print it.
	#print "Generated table";
	$redis -> set('pltt', $table);
	$redis -> setTimeout('pltt', $CONFIG['tableCache']);
	$redis -> set('pltgt', gmdate("H:i:s"));
	#$redis -> set('plttage', time());
	print $table;

}
/*
foreach ($files as $file){
print "<tr>";
$torrent = new BDecode($CONFIG['tordir'].$file);
print " <td>".$torrent->result['info']['name']."</td>";
print "<td> </td> <td> </td>";
print "<td> ".bytesToSize($torrent->result['info']['length'])."</td>";
#print "<br /><br /><br />";
print "</tr>";	

}
*/
?>

