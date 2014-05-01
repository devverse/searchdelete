<div class="container">
<div class="page-header">

<p>
<h3>Provider Search Results: <?php echo date("F j, Y"); ?></h3>
<br/></center>
<ul style="text-align:left;">
<li>This is a list of providers that matched your search. </li>
<li><strong>This is not a full list of all of our providers.</strong></li>
<li>It is only correct as of the date of your search. (or date above)</li>
<li>To be sure you have the latest listing, use our online search. </li>
</ul>
<br/>
<div>Please call 1-855-463-0026 if you need help finding a provider. We can also answer questions about which providers are in the network.
</div>
</p>

	
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
		<div><?php echo $result['address'] .' '.$result['suite'];?></div><div><?php echo $result['city'].', '.$result['state'].' '.$result['zip4']; ?></div>
		<br/>
		<div class="name"><?php echo ($result['lastname']!='') ?"Dr. {$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div><div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
	</td>
	<td colspan="1" width="20%">
		<div><?php echo "Category: <br/>{$result['category']}";?></div>
		<div><?php echo "Specialties: <br/>{$result['specialty']}";?></div>
	</td>
	<td colspan="1" width="25%">
		<div>
			<div><?php echo "Phone: {$result['phone']}";?></div>
			<div><?php echo "Hours: <br/>{$result['officehours']}";?></div>
			<div><?php echo "Languages:  <br/>{$result['languages']}";?></div>
			<!--<div><?php echo "Service Area: {$result['servicearea']}, {$result['state']}";?></div>-->
		</div>
	</td>
	<td colspan="1" width="25%">
		<div>	
			<!--<div><?php echo "Hospital Affiliations: <br/>{$result['hospaffiliations']}";?></div>-->
			<!--<div><?php echo "Medicaid: {$result['acceptsmedicaid']}";?></div>-->
			<!--<div><?php echo "Medicare: {$result['acceptsmedicare']}";?></div>-->
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
</div>
</div>

<div class="row">
<div class="col-md-12">
<h2>Notice</h2>
<small>This directory is updated often, but provider information can change at any time. Before making an appointment be sure to check with the provider to make sure he or she is in the network and taking patients.</small>
</div>
</div>
</div>	

<img style="width:600px;" src="<?php echo $maplink; ?>">

