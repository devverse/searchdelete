<div class="container" id="providerresults">
<div class="row">
<div class="col-md-12 page-header">
<h2>Search Results
<a href="./" class="btn-xs btn-default pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Return to Search form</a></h2>

</div>

</div>

<div class="row top5">
<div class="col-md-7">Export:
	<a class="print-btn" onClick="window.print()"><img style="width:25px;height:25px" src="/img/print5.png" title="Print page"/></a>
	<a class="pdf-btn"><img style="width:27px;height:27px" src="/img/pdf19.png" title="Save as pdf"/></a>
	<a class="email-btn"><img style="width:23px;height:23px" src="/img/opened4.png" title="Email page"/></a><small>&nbsp;Print, PDF and Email will return current page results only.</small>
	</div>
	
	<div class="col-md-9">
		<div class="email-box" style="background-color:#FFFFFF;display:none;">Your Email : <input type="text" class="email-input"/><button class="email-page">Email</button>
		</div>
</div>
</div>
<?php if($resultcount){ ?>
	<div><h3>Found <?=$resultcount?> Results.&nbsp;
	<?php if(isset($srch_filter['start'])){echo "Displaying  ".floor($srch_filter['start']/25+1)." of ".ceil($resultcount/25)." Pages.";} ?>
	</h3></div>

	<?php if ($req_data['state']!='None'||$req_data['street_address']!=''||$req_data['city']!=''||$req_data['zipcode']!=''){ $state = ($req_data['state']!='None')?$req_data['state']:'';echo 'You searched for providers within '.($req_data['distance'] == 1 ? '1': $req_data['distance']).' miles of <b>'.implode(' ',array($req_data['street_address'],$req_data['city'],$state,$req_data['zip'])).'</b><br/>';}?>
	<?php if ($req_data['practicename']!=''){echo 'You searched for practices containing the words <b>"'.$req_data['practicename'].'"</b><br/>';}?>
	<?php if ($req_data['countie_name']!='none'){echo 'You searched for providers around <b>'.$req_data['countie_name'].' county</b><br/>';}?>
	<?php if($req_data['providertype_name']!='none'){ echo 'Filtered by <b>'.$req_data['providertype_name'].'</b>';}?>
	<?php if($req_data['specialtie_name']!='none'){ echo ' > <b>'.$req_data['specialtie_name'].'</b>';}?>

<?php } ?>
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

	<td>
		<div><strong><?php echo $result['practicename'];?></strong></div>
		<div class="name"><?php echo ($result['lastname']!='') ?"{$result['firstname']} {$result['middlename']} {$result['lastname']}":'';?></div>
		<div><?php echo ($result['degree']!='') ? "({$result['degree']})" :'';?></div>
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
			<div><?php echo "Office Hours:" ?> <?php echo utf8_encode($result['officehours']);?></div>
			<div><?php 
				if ($result['languages']) {
					echo "Languages: {$result['languages']}";
				}

			?>
			</div>
			<div>
			<?php 
			if ($result['servicearea']) {
				echo "Service Area: {$result['servicearea']}, {$result['state']}"
			}
			?>
			</div>
		</div>
	</td>
	<td width="100">
		<div>	
			<div>NPI #: <?php echo $result['provId'];?> </div>

			<div>
			<?php 
			if ($result['customfield1ind']) {
				echo "{$result['customfield1desc']}:{$result['customfield1ind']}";
			}
			?>
			</div>

			<div>
			<?php 
			if ($result['customfield2ind']) {
				echo "{$result['customfield2desc']}:{$result['customfield2ind']}";
			}
			?>
			</div>

			<div>
			<?php 
			if ($result['specialexperince']) {
				echo "Special Experience: {$result['specialexperince']}";
			}
			?>
			</div>

			<div>
			<?php 
			if ($result['publictransavailable']) {
				echo "Public Transportation Available: {$result['publictransavailable']}";
			}
			?>
			</div>
			<div>
			<?php 
			if ($result['certifications']) {
				echo "Board Certified";
			}
			?>
			</div>

			<div>
			<?php 
			if ($result['hospaffiliations']) {
				echo "Hospital Affiliations: {$result['hospaffiliations']}";
			}
			?>
			</div>
			<div>
			<?php 
			if ($result['acceptsmedicaid']) {
				echo "Accepts Medicaid: {$result['acceptsmedicaid']}";
			}
			?>
			</div>
			<div>
			<?php 
			if ($result['acceptsmedicare']) {
				echo "Accepts Medicare: {$result['acceptsmedicare']}";
			}
			?>
			</div>
			<div>
			<?php 
			if ($result['acceptingnew']) {
				echo ($result['acceptingnew']=='Y')?"Accepting New Patients: Y": "";
			}
			?>
			</div>
			<div>
			<?php 
			if ($result['handicap']) {
				echo ($result['handicap']=='Y')?"Handicap Accessible: Y":"";
			}
			?>
			</div>
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
<div class="col-md-12">
<h3>Notice</h3>
<p>This directory is updated often. But provider information can change at any time. Before making an appointment be sure to check with the provider to make sure he or she is in the network and taking patients.</p>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		// Table Sorter
        $("table").tablesorter(); 
    
	});
</script>
</div>	
