<div class="container" style="width:550px;margin:1.5em">
<div class="page-header">
<h2>Search Results</h2>
</div>

<div class="row"><div class="col-md-12">

<table width="550px">


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
		<div><?php echo $result['address'] .' '.$result['suite'];?></div><div><?php echo $result['city'].', '.$result['state'].' '.$result['zip4']; ?></div>
		<br/>
		<div class="name"><?php echo ($result['lastname']!='') ?"Dr.{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div><div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
	</td>
	<td colspan="1" width="20%">
		<div><?php echo "Category: <br/>{$result['category']}";?></div>
		<div><?php echo "Specialties: <br/>{$result['specialty']}";?></div>
	</td>
	<td colspan="1" width="25%">
		<div>
			<div><?php echo "Phone: {$result['phone']}";?></div>
			<!--<div><?php echo "OfficeHours: <br/>{$result['officehours']}";?></div>-->
			<div><?php echo "Languages:  <br/>{$result['languages']}";?></div>
			<!--<div><?php echo "Service Area: {$result['servicearea']}, {$result['state']}";?></div>-->
		</div>
	</td>
	<td colspan="1" width="25%">
		<div>	
			<!--<div><?php echo "Hospital Affiliations: <br/>{$result['hospaffiliations']}";?></div>-->
			<!--<div><?php echo "Medicaid: {$result['acceptsmedicaid']}";?></div>-->
			<!--<div><?php echo "Medicatre: {$result['acceptsmedicare']}";?></div>-->
			<div><?php echo ($result['acceptingnew']=='Y'||$result['acceptingnew']=='')?"Accepting New Patients: Y": "";?></div>
			<div><?php echo ($result['handicap']=='Y'||$result['handicap']=='')?"Handicap Accessible: Y":"";?></div>
		</div>
	</td>
</tr>
<!-- Result Information END-->
<?php
		$count++;
		if($count > 5)
		{
			$count = 0;
?>
		</table>
		<div class="row">
		<div class="col-md-12">
		<h2>Notice</h2>
		<p><small>Provider information contained in this Directory is updated on a frequent basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</small></p>
		</div>
		</div>
		<table width="550px" style="page-break-before:always">
<?php

		}
	}
?>

</table>
</div>
</div>

<div class="row">
<div class="col-md-12">
<h3>Notice</h3>
<p><small>Provider information contained in this Directory is updated on a frequent basis and may have changed. Therefore, please check with your provider before receiving services to confirm whether he or she is participating and accepting patients before scheduling your appointment.</small></p>
</div>
</div>
<img style="width:99%;" src="files/<?php echo $statimgname; ?>.png"/>
</div>	

<!--<img style="width:75%;" src="img/print5.png"/>-->

<!--<img style="width:200px;" src="http://maps.google.com/maps/api/staticmap?center=40.7536854,-73.9991637&zoom=16&size=500x300&maptype=roadmap&sensor=false&language=&markers=color:red|label:none|40.7536854,-73.9991637"/>-->

