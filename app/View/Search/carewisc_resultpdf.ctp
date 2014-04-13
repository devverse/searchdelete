<h1>Provider Results</h1>
<?php

	$count = 0;
	foreach($results as $r)
	{
		$result = $r['fullrecords'];
?>

<!-- Result Information START-->
<div>
	<div>
		<div><?php echo ($result['lastname']!='') ?"Dr.{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div><div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
	</div>
	<div>
		<div><?php echo $result['specialty'];?></div>
	</td>
	<div>
		<div><?php echo $result['category'];?></div>
	</div>
	<div>
		<div><?php echo $result['practicename'];?></div>
	</div>
	<div>
		<div><?php echo $result['address'] .' '.$result['suite'];?></div><div><?php echo $result['city'].' '.$result['state'].', '.$result['zip4']; ?></div>
	</div>
	<div>
		<div>
			<?php echo "Phone: {$result['phone']}";?></br>
			<?php echo "OfficeHours: {$result['officehours']}";?></br>
			<?php echo "Languages: {$result['languages']}";?></br>
			<?php echo "Service Area: {$result['servicearea']}, {$result['state']}";?>
		</div>
	</div>
	<div>
		<div>	
			<?php echo "Hospital Affiliations: {$result['hospaffiliations']}";?></br>
			<?php echo "Accepts Medicaid: {$result['acceptsmedicaid']}";?></br>
			<?php echo "Accepts Medicatre: {$result['acceptsmedicare']}";?></br>
			<?php echo "Accepting New Patiends: {$result['acceptingnew']}";?></br>
			<?php echo "Handicap Accessible: {$result['handicap']}";?>
		</div>
	</div>
</div>
<!-- Result Information END-->
<?php
	$count++;
	//if($count >0)
		break;
	}
?>