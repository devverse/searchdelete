<h3>Client Dashboard</h3>
<form method="post" action="./logout">
	<input type="submit" value="Logout"/>
</form>
<div>
<span><?php echo $this->Session->flash('succ_msg') ; ?></span>
<span><?php echo $this->Session->flash('err_msg') ; ?></span>
<span><?php echo $this->Session->flash('notice_msg') ; ?></span>
</div>

<div class="control-group">
	<form id="radio-action">
	<div clas="controls">
	<div class="radio">
		<label class="radio"><input type="radio" name="actiontype" value="migrate">Migrate Data</label>
		<label class="radio"><input type="radio" name="actiontype" value="addrecord">Add Record</label>
		<label class="radio"><input type="radio" name="actiontype" value="editrecord">Edit Record</label>
	</div>
	</div>
	</form>
</div>

<script type="text/javascript">
$(function(){
	$('.dashboard-div').hide();
    $('#radio-action')[0].reset();
    $('#radio-action').on('change','input',function()
        {	 $('.dashboard-div').hide();
             var inputValue = $(this).val();

             $('.'+'actiontype-'+inputValue).show();
        });
});
</script>

<div class="actiontype-migrate dashboard-div">
Migrate Data
<p><small>
Instructions: Please upload a zip file with the record of your providers. The zip file must contain one file name providers.txt and must me a tab separated value file. Follow Command Printings Data format for proper input type.
</small></p>
<form method="post" action="./upload" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"/><br>
	<input type="submit" value="Migrate!"/>
</form>
</div>

<div class="actiontype-addrecord dashboard-div">
Add Record
<p><small>
Instructions: Please follow Command Printings Data format for proper input type.
</small></p>
<form method="post" action="./addprovider">
	<ul style="float:left">
	<?php $halfway = (($field_count-2)/2); ?>
	<?php foreach($fields as $key=>$field){ 
		if(in_array($field, array('id','longitude_str','latitude_str')))
			continue;
		if(round($halfway,PHP_ROUND_HALF_ODD) == $key)
			echo '</ul><ul style="float:left">';
	?>
		<li><label for="<?=$field?>-add"><?=$field?></label>
		<input type="text" name="<?=$field?>" value="" id="<?=$field?>-add"/></li>
	<?php } ?>
</ul>
	<div style="clear:both"><input type="submit" value="Add Record"/></div>
</form>

</div>

<div class="actiontype-editrecord dashboard-div">
Edit/Delete Record
<p><small>
Instructions: Search by practice name or street address of the record you want to update.
</small></p>
<form method="post" action="../dashboard/index">
	<input type="text" name="Search" value="">
	<input type="submit" value="Search"/>
</form>
</div>

<script type="text/javascript">$(function(){
	$('#edit-div').on('click','.show-div-btn',function(){
		var updatedivid = $(this).attr('data-div');
		$('.update-div').hide();
		$('#'+updatedivid).show();
	});
});
</script>
<!--edit panel-->
<?php if(isset($editrecords[0])){ ?>
	<div id="edit-div">
	<p><small>
	Instructions: 
	The search has been limited to 25 records.
	This is meant for individual record updates. To edit sets of records, please contact your database administrator.
	</small></p>

	<?php 
	foreach($editrecords as $key=>$fullrecord)
	{	$f = $fullrecord['Fullrecord'];
	?>
		<div style="clear:both;">
		<form method="post" action="./updateprovider">
			<span><?=$f['practicename']?></span><span><?=$f['address']?></span><a href="javascript:void(0);" class="show-div-btn" data-div="update-div-<?=$key?>">Show</a>
			<div id='update-div-<?=$key?>' class="update-div" style="display:none">
			<p><small>
			Instructions:
			Please follow Command Printing's Data format for proper input types.
			</small></p>
			<ul style="float:left">
			<?php 
				$third = round(((count($f)-1)/3),PHP_ROUND_HALF_ODD) ; 
				$count = 0
			?>
			<?php foreach($f as $k=>$v) {
				if($third == $count||$third*2 == $count)
					echo '</ul><ul style="float:left">';

				if($k == 'id')
				{
					echo "<input type='hidden' name='{$k}' value='{$v}'/>";
					$count++;
					continue;
				}
			?>
				<li><label for="<?=$k?><?=$key?>-edit"><?=$k?></label><input type="text" name="<?=$k?>" value="<?=$v?>" id="<?=$k?><?=$key?>-edit"/></li>
			<?php 
				$count++;
				} 
			?>
			</ul>
			</div>
			<input type="submit" value="Update Record"/>
		</form>

		<form method="post" action="./deleteprovider">
			<input type="hidden" name="id"  value="<?=$f['id']?>">
			<input type="submit" value="Delete Record"/>
		</form>
		</div>
	<?php } ?>
	</div>
<?php } ?>