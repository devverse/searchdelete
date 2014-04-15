<div class="container">
<div class="page-header">
<h2>Search Form</h2>
</div>

<div class="row"><div class="col-md-12">

<table>


<?php

	$count = 0;
	foreach($results as $r)
	{
		$result = $r['fullrecords'];
?>

<!-- Result Information START-->
<tr>
	<td colspan="1" width="30%">
		<div><?php echo $result['practicename'];?></div>
		<div><?php echo $result['address'] .' '.$result['suite'];?></div><div><?php echo $result['city'].' '.$result['state'].', '.$result['zip4']; ?></div>
		<br/>
		<div class="name"><?php echo ($result['lastname']!='') ?"Dr.{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div><div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
	</td>
	<td colspan="1" width="20%">
		
		<div><?php echo "Specialties: <br/>{$result['specialty']}";?></div>
		<div><?php echo "Category: <br/>{$result['category']}";?></div>
	</td>
	<td colspan="1" width="25%">
		<div>
			<div><?php echo "Phone: {$result['phone']}";?></div>
			<div><?php echo "OfficeHours: <br/>{$result['officehours']}";?></div>
			<div><?php echo "Languages:  <br/>{$result['languages']}";?></div>
			<div><?php echo "Service Area: {$result['servicearea']}, {$result['state']}";?></div>
		</div>
	</td>
	<td colspan="1" width="25%">
		<div>	
			<div><?php echo "Hospital Affiliations: <br/>{$result['hospaffiliations']}";?></div>
			<div><?php echo "Medicaid: {$result['acceptsmedicaid']}";?></div>
			<div><?php echo "Medicatre: {$result['acceptsmedicare']}";?></div>
			<div><?php echo "Accepts New Patients: {$result['acceptingnew']}";?></div>
			<div><?php echo "Handicap Accessible: {$result['handicap']}";?></div>
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
<small>Provider information contained in this Directory is updated on a daily basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</small>
</div>
</div>
</div>	

<img style="width:600px;" src="<?php echo $maplink; ?>">

