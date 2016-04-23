<div class="container">
<div class="col-md-12 page-header">
<h2><span class="glyphicon glyphicon-map-marker"></span>Search Results
<a href="./" class="btn-xs btn-default pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Return to Search form</a></h2>

</div>
<div id="map_canvas" style="height: 400px; position: relative; background-color: rgb(229, 227, 223); overflow: hidden;"></div>

<div class="row"><div class="col-md-12">

<?php if($resultcount){ ?>
	<div><h3>Found <?=$resultcount?> Results.&nbsp;
	<?php if(isset($srch_filter['start'])){echo "Displaying  ".floor($srch_filter['start']/25+1)." of ".ceil($resultcount/25)." Pages.";} ?>
	</h3></div>

	<?php if ($req_data['state']!='None'||$req_data['street_address']!=''||$req_data['city']!=''||$req_data['zipcode']!=''){ $state = ($req_data['state']!='None')?$req_data['state']:'';echo 'You searched for providers within '.$req_data['distance'].' miles of <b>'.implode(' ',array($req_data['street_address'],$req_data['city'],$state,$req_data['zipcode'])).'</b><br/>';}?>
	<?php if ($req_data['practicename']!=''){echo 'You searched for practices containing the words <b>"'.$req_data['practicename'].'"</b><br/>';}?>
	<?php if ($req_data['countie_name']!='none'){echo 'You searched for providers around <b>'.$req_data['countie_name'].' county</b><br/>';}?>
	<?php if($req_data['providertype_name']!='none'){ echo 'Filtered by <b>'.$req_data['providertype_name'].'</b>';}?>
	<?php if($req_data['specialtie_name']!='none'){ echo ' > <b>'.$req_data['specialtie_name'].'</b>';}?>

<?php } ?>

<table id="searchResults" class="tablesorter table table-striped table-hover table-condensed">
<thead>
<tr>
<th width="15%"><strong>Practice</strong></th>
<th width="10%"><strong>Gender</strong></th>
<th width="10%"><strong>Specialty</strong></th>
<th width="10%"><strong>Category</strong></th>
<th width="15%"><strong>Address</strong></th>
<th width="15%"><strong>Info</strong></th>
<th width="30%"><strong>Hospital Affiliation</strong></th>
</tr>
</thead>
<?php
	$count = 0;
	foreach($results as $r)
	{
		$result = $r['fullrecords'];

?>

<!-- Result Information START-->
<tr>
	<td>
		<div><strong><?php echo $result['practicename'];?></strong></div>
		<div class="name"><?php echo ($result['lastname']!='') ?"{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div>
		<div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
   </td>
	<td>
		<div class="gender"><?php echo $result['g'] == 'M'?'Male':'';?><?php echo $result['g'] == 'F'?'Female':'';?></div>
	</td>
	<td>
		<?php echo $result['specialty'];?>
	</td>
	<td>
		<?php echo $result['category'];?>
	</td>
	<td>
		<div><?php echo $result['address'] .' '.$result['suite'];?></div>
		<div><?php echo $result['city'].', '.$result['state'].' '.$result['zip4']; ?></div>
		<div><?php echo ($result['county'] != '')?$result['county'].' County':'' ?></div>
		<?php if($coor && $req_data['practicename'] == ''){?>
			<div><a target="_blank" href="http://maps.google.com/maps?f=d&hl=en&saddr=<?= $result["address"];?>, <?= $result["city"];?>, <?= $result["state"];?>, <?= $result["zipcode"];?>&sll=&sspn=33.214763,82.265625&z=12">Get Directions</a></div><!--Has Coordinates-->
		<?php }else{ ?>
			<div><a target="_blank" href="http://maps.google.com/maps?f=d&hl=en&daddr=<?= $result["address"];?>, <?= $result["city"];?>, <?= $result["state"];?>, <?= $result["zipcode"];?>&sll=&sspn=33.214763,82.265625&z=12">Get Directions</a></div><!--No Coordinates-->
		<?php } ?>
	</td>
	<td>
		<div class="phone"><?php echo $result['phone'];?></div>
		<div class="website"><?php echo $result['handicap'] == 'Y' ? 'Handicap Accessible' : '';?></div>
		<div class="website"><?php echo $result['acceptingnew'] == 'Y' ? 'Accepting New' : '';?></div>
		<div class="boardCertified"><?php echo $result['customfield1desc'] == 'Board Certified' ? 'Board Certified' : '';?></div>
	</td>
	<td>
		<?php foreach($result['locations'] as $ploc){ ?>
			<div class="location-div">
				<div class="location-name">
					<?php echo $ploc['name']; ?>
				</div>
				<div class="location-add1">
					<?php echo $ploc['address1']; ?>
				</div>
				<div class="location-add2">
					<?php echo $ploc['address2']; ?>
				</div>
				<div class="location-add3">
					<?php echo $ploc['address3']; ?>
				</div>
				<div class="location-add4">
					<?php echo $ploc['address4']; ?>
				</div>
				<div class="location-city">
					<?php echo $ploc['city'].', '.$ploc['state'].' '.$ploc['zipcode'] ; ?>
				</div>
				<div class="location-phone">
					<?php echo $ploc['phone']; ?>
				</div>
				<div class="location-phone">
					<?php echo $ploc['ephone']; ?>
				</div>
				<div class="location-site">
					<?php echo $ploc['website']; ?>
				</div>
				<div class="location-wheelchair">
					<?php echo $ploc['wheelchair_accessible'] == '1'?'* Wheel Chair Accesible':''; ?>
				</div>
			<div>
		<?php } ?>
	<td>
	</td>
</tr>
<!-- Result Information END-->
<?php
	$count++;
	}
?>

</table>
</div>
</div>

<div>
	<form id="nextresult" class="form-horizontal" action="../<?php echo $client_url_name?>/result" method="post">
	<?php
	foreach($srch_filter as $name=>$value)
	{
		echo "<input type='hidden' name='{$name}' value='{$value}'>";
	}
	?>
	<ul class="pager">
		<?php if($srch_filter['start']!=0){?>
			<li class="previous"><a href="javascript:void(0);" onclick="submitsearch('prev')">&larr; Previous</a></li>
		<?php } ?>
		<?php if($count>=24){?>
			<li class="next"><a href="javascript:void(0);" onclick="submitsearch('next')">Next &rarr;</a></li>
			</ul>
		<?php } ?>
	</form>
	<script type="text/javascript">
	function submitsearch(type){
		var form = $('#nextresult');
		<?php 
			echo isset($srch_filter['start']) ? "var start = {$srch_filter['start']};" : "var start = 0;";
		?>
		if(type == 'prev')
			start -= 25
		else
			start += 25
		$('#nextresult input[name=start]').remove();
		form.append("<input type='hidden' name='start' value='"+start+"'/>");
		form.submit();
	}</script>
</div>

<div class="row">
<div class="col-md-12">
<h2>Notice</h2>
<p>Provider information contained in this Directory is updated on a daily basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</p>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		 $("table").tablesorter(); 
	});
</script>

<!--Todo move map script to better place for faster load-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false">//google maps</script>
<script type='text/javascript'>
	$(document).ready(function() {
		initializemap();
		
		function initializemap() {
			var myOptions = {
			  center: new google.maps.LatLng(<?php echo $coor['lat']; ?>, <?php  echo $coor['long']; ?>),
			  zoom:11,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById('map_canvas'),
				myOptions);

			<?php
				$count = 1;
				foreach($locations as $location)
				{
			?>

			marker_<?php echo $count; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo $location["latitude"];?>, <?php echo $location["longitude"];?>),
					map: map,
					title:'<?php echo $location["name"];?>',
					icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
				});
				
			infowindow_<?php echo $count; ?> = new google.maps.InfoWindow(
			  { content: '<b><?php echo $location["practicename"];?></b><br /><?php echo $location["address"];?><br /><?php echo $location["city"];?>, <?php echo $location["state"];?> <?php echo $location["zipcode"];?><br/>Phone: <?php echo $location["phone"];?><br/>',
				size: new google.maps.Size(50,50)
			  });

			google.maps.event.addListener(marker_<?php echo $count; ?>, 'click', (function() {
				infowindow_<?php echo $count; ?>.open(map,marker_<?php echo $count; ?>);
			}));
			google.maps.event.addListener(map, 'mousedown', (function() {
				infowindow_<?php echo $count; ?>.close(map,marker_<?php echo $count; ?>);
			}));

			<?php $count++; } ?>
		
		 }
	});
</script>
</div>	
