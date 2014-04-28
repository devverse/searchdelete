<div class="container" id="providerresults">
<div class="row">
<div class="col-md-12 page-header">
<h2>Search Results
<a href="./" class="btn-xs btn-default pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Return to Search form</a></h2>

</div>

</div>

<div class="row top5">
<div class="col-md-3">Export:
	<a class="print-btn" onClick="window.print()"><img style="width:25px;height:25px" src="/img/print5.png" title="Print page"/></a>
	<a class="pdf-btn"><img style="width:27px;height:27px" src="/img/pdf19.png" title="Save as pdf"/></a>
	<a class="email-btn"><img style="width:23px;height:23px" src="/img/opened4.png" title="Email page"/></a>
	</div>
	
	<div class="col-md-9">
		<div class="email-box" style="background-color:#FFFFFF;display:none;">Your Email : <input type="text" class="email-input"/><button class="email-page">Email</button>
		</div>
</div>
</div>
<div class="row">
<table id="searchResults" class="tablesorter table table-striped table-hover table-condensed">
<thead>
<tr>
<th width="10%"><h6>Practice Name</h6></th>
<th width="10%"><h6>Provider Type</h6></th>
<th width="10%"><h6>Specialty</h6></th>
<th width="10%"><h6>Address</h6></th>
<th width="10%"><h6>Info</h6></th>
<th width="10%"><h6><!--info 2--></h6></th>

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
<!--	<td>
		<div class="name"><strong><?php echo ($result['lastname']!='') ?"Dr.{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div><div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></strong></div>
	</td>-->
	
	<td>
		<div><?php echo $result['practicename'];?></div>
   </td>
	<td>
		<div><?php echo $result['category'];?></div>
	</td>
	<td>
		<div><?php echo $result['specialty'];?></div>
	</td>
	<td>
		<div><?php echo $result['address'] .' '.$result['suite'];?></div>
		<div><?php echo $result['city'].', '.$result['state'].' '.$result['zip4']; ?></div>
		<div><?php echo ($result['county'] != '')?$result['county'].' County':'' ?></div>
	</td>
	<td width="100">
		<div>
			<div><?php echo "Phone: {$result['phone']}";?></div>
			<!--<div><?php echo "Office Hours: {$result['officehours']}";?></div>-->
			<div><?php echo "Languages: {$result['languages']}";?></div>
			<!--<div><?php echo "Service Area: {$result['servicearea']}, {$result['state']}";?></div>-->
		</div>
	</td>
	<td width="100">
		<div>	
			<!--<div><?php echo "Hospital Affiliations: {$result['hospaffiliations']}";?></div>-->
			<!--<div><?php echo "Accepts Medicaid: {$result['acceptsmedicaid']}";?></div>-->
			<!--<div><?php echo "Accepts Medicare: {$result['acceptsmedicare']}";?></div>-->
			<div><?php echo ($result['acceptingnew']=='Y'||$result['acceptingnew']=='')?"Accepting New Patients: Y": "";?></div>
			<div><?php echo ($result['handicap']=='Y'||$result['handicap']=='')?"Handicap Accessible: Y":"";?></div>
		</div>
	</td>
</tr>
<!-- Result Information END-->
<?php
	$count++;
	}
?>

</table>

<?php if(count($result) < 1){?>
<h3>No providers were found. Please change your search criteria.</h3>

<?php } ?>
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
<div id="map_canvas" style="margin:0px 50px;height: 400px;width:90%; position: relative; background-color: rgb(229, 227, 223); overflow: hidden;" class="col-md-12"></div>
<center>
<div>* This map shows locations of only the current page's results.</div></center>
</div>

<div class="row">
<div class="col-md-12">
<h3>Notice</h3>
<p>Provider information contained in this Directory is updated on a frequent basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</p>
</div>
</div>

<!--Todo move map script to better place for faster load-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false">  </script>
<script type="text/javascript">
	$(document).ready(function() {
		initializemap();
		
		function initializemap() {
			var myOptions = {
			  center: new google.maps.LatLng(<?php if($coor){echo $coor['lat'].' , '.$coor['long']; }else{echo $results[10]['fullrecords']['latitude'].' , '.$results[10]['fullrecords']['longitude'];} ?>),
			  zoom:<?php if($coor){echo 8;}else{echo 8;} ?>,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById('map_canvas'),
				myOptions);

			<?php
				$count = 1;
				foreach($results as $r)
				{
					$result = $r['fullrecords'];
			?>

			marker_<?php echo $count; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo $result["latitude"];?>, <?php echo $result["longitude"];?>),
					map: map,
					title:"<?php echo  $result['practicename'];?>"
				});
				
			infowindow_<?php echo $count; ?> = new google.maps.InfoWindow(
			  { content: "<b><?php echo $result['practicename'];?></b><br /><?php echo $result['address'];?><br /><?php echo $result["city"];?>, <?php echo $result['state'];?> <?php echo $result['zip4'];?><br/>Phone: <?php echo $result['phone'];?>",
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
