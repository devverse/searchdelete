


<script type="text/javascript">
	$('body').css({
		"background-image": "url(http://www.centersplan.com/wp-content/themes/cp/img/bg.png)",
		"font-family": 'Open Sans,Lucida Grande,sans-serif',
		"line-height": 1.6,
		"margin": 0,
		"padding": 0
    });
</script>
<div class="container">
<div class="page-header">
<h1>Search Form</h1>
</div>
<div id="map_canvas" style="height: 400px; position: relative; background-color: rgb(229, 227, 223); overflow: hidden;"></div>

<div class="row"><div class="col-md-12">
<table id="searchResults" class="tablesorter">
<thead>
<tr>
<th width="25%"><strong>Name</strong></th>
<th width="10%"><strong>Gender</strong></th>
<th width="10%"><strong>Specialty</strong></th>
<th width="10%"><strong>Language</strong></th>
<th width="10%"><strong>Insurance</strong></th>
<th width="35%"><strong>Hospital Affiliation</strong></th>
</tr>
</thead>
<?php
	$count = 0;
	foreach($results as $result)
	{
?>

<!-- Result Information START-->
<tr>
	<td>
		<div class="name"><strong>Dr. <?php echo $result['name'];?></div><div class="drtitle"><?php echo $result['title'];?></strong></div>
	</td>
	<td>
		<div class="gender"><?php echo $result['g'] == 'M'?'Male':'Female';?></div>
	</td>
	<td>
		<?php echo implode(', ', $result['specialties']);?>
		<div class="boardCertified"><?php echo $result['board_certified'] == 1 ? 'Board Certified' : 'Non Board Certified';?></div>
	</td>
	<td>
		<?php echo implode(', ', $result['languages']);?>
	</td>
	<td>
		<?php echo implode(', ', $result['insurances']);?>
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
				<div class="website"><?php echo $result['website'];?></div>
			<div>
		<?php $count++; ?>
		<?php } ?>
	</td>
</tr>
<!-- Result Information END-->
<?php
	}
?>

</table>
</div>
</div>

<div>
	<form id="nextresult" class="form-horizontal" action="../<?php echo $client_name?>/result" method="post">
	<?php
	foreach($srch_filter as $name=>$value)
	{
		if($name == 'start')
			continue;
		echo "<input type='hidden' name='{$name}' value='{$value}'>";
	}
	?>
	<span>
		<?php if($srch_filter['start']!=0){?>
			<a href="javascript:void(0);" onclick="submitsearch('prev')">&lt;&lt; Previous</a>
		<?php } ?>
			&nbsp;&nbsp;
		<?php if($count>=24){?>
			<a href="javascript:void(0);" onclick="submitsearch('next')">Next &gt;&gt;</a>	</span>
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

<!--Todo move map script to better place for faster load-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false">  </script>
<script type="text/javascript">
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
					title:"<?php echo  $location['name'];?>"
				});
				
			infowindow_<?php echo $count; ?> = new google.maps.InfoWindow(
			  { content: "<b><?php echo $location['name'];?></b><br /><?php echo $location['address1'];?><br /><?php echo $location["city"];?>, <?php echo $location['state'];?> <?php echo $location['zipcode'];?><br/>Phone: <?php echo $location['phone'];?>",
				size: new google.maps.Size(50,50)
			  });

			google.maps.event.addListener(marker_<?php echo $count; ?>, 'click', (function() {
				infowindow_<?php echo $count; ?>.open(map,marker_<?php echo $count; ?>);
			}));
			google.maps.event.addListener(map, 'mousedown', (function() {
				infowindow_<?php echo $count; ?>.close(map,marker_<?php echo $count; ?>);
			}));

			<?php 
				$count++; 
				if($count > 25)//LIMIT ON MAPS
					break;
			} ?>
		
		 }
		// Table Sorter
        $("table").tablesorter(); 
    
	});
</script>
</div>	
