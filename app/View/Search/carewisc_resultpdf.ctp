<div class="container">
<div class="page-header">
<h2>Search Form</h2>
</div>

<div class="row"><div class="col-md-12">

<table id="searchResults" class="tablesorter table table-striped table-hover table-condensed">
<thead>
<tr>
<th width="10%"><strong>Name</strong></th>
<th width="10%"><strong>Degree</strong></th>
<th width="10%"><strong>Specialty</strong></th>
<th width="10%"><strong>Provider Type</strong></th>
<th width="10%"><strong>Practice Name</strong></th>
<th width="10%"><strong>Address</strong></th>
<th width="10%"><strong>Info</strong></th>

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
		<div class="namde">Dr. <?php echo $result['firstname'].' '.$result['middlename'].' '.$result['lastname'];?></div><div><?php echo $result['degree'];?></div>
	</td>
	<td>
		<div><?php echo $result['degree'];?></div>
	</td>
	<td>
		<div><?php echo $result['specialty'];?></div>
	</td>
	<td>
		<div><?php echo $result['category'];?></div>
	</td>
	<td>
		<div><?php echo $result['practicename'];?></div>
	</td>
	<td>
		<div><?php echo $result['address'] .' '.$result['suite'];?></div><div><?php echo $result['city'].' '.$result['state'].', '.$result['zip4'].', '.$result['county']; ?></div>
	</td>
	<td>
		<div>
			<?php echo $result['languages'];?>
			<?php echo $result['officehours'];?>	
			<?php echo $result['hospaffiliations'];?>	
			<?php echo $result['acceptsmedicaid'];?>	
			<?php echo $result['acceptsmedicare'];?>	
			<?php echo $result['acceptingnew'];?>	
			<?php echo $result['handicap'];?>	
			<?php echo $result['phone'];?>
			<?php echo $result['servicearea'];?>
		</div>
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

<div class="row">
<div class="col-md-12">
<h2>Notice</h2>
<p>Provider information contained in this Directory is updated on a daily basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</p>
</div>
</div>
</div>	
