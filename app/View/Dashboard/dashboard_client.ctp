<!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            
          </button>
          <a class="navbar-brand" href="#">Admin Dashboard</a>
        </div>
        <!--
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Add Record</a></li>
          </ul>
        </div>-->
      </div>
    </div>
    
    

<div class="container">
<h3>Client Dashboard</h3>

<div class="well">
        <p>Some instructions here.</p>
      </div>
<form method="post" action="./logout"><input type="submit" value="Logout" class="btn btn-default btn-xs navbar-right" />
</form>


<div>
<span><?php echo $this->Session->flash('succ_msg') ; ?></span>
<span><?php echo $this->Session->flash('err_msg') ; ?></span>
<span><?php echo $this->Session->flash('notice_msg') ; ?></span>
</div>

<div class="control-group">
	<form id="radio-action">
	<div clas="controls">
	<div class="radiogroup">
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
     $('#migrate-form').on('click','#migrate-btn',function()
        {	 
        	$('.actiontype-migrate').append('<br/><b><i>Migration in Progress. Please Wait a Few Minutes.</i></b>');
        	$("#migrate-btn").attr('disabled', 'disabled');
        });
});	
</script>

<div class="actiontype-migrate dashboard-div">
<h4>Migrate Data</h4>
<p><small>
Instructions: Please upload a zip file with the record of your providers. The zip file must contain one file name providers.txt and must me a tab separated value file. Follow Command Printings Data format for proper input type.
</small></p>
<form id="migrate-form" method="post" action="./upload" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"/><br>
	<input id="migrate-btn" type="submit" class="btn btn-default" value="Migrate!"/>
</form>
</div>

<div class="actiontype-addrecord dashboard-div">
<h4>Add Record</h4>
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
	<div style="clear:both"><input type="submit" class="btn btn-default" value="Add Record"/></div>
</form>

</div>

<div class="actiontype-editrecord dashboard-div clearfix">
<h4>Edit/Delete Record</h4>
<p><small>
Instructions: Search by practice name or street address of the record you want to update.
</small></p>

<form method="post" action="../dashboard/index" class="navbar-form navbar-left">
	<input type="text" class="form-control" name="Search" value="">
	<input type="submit" class="btn btn-default" value="Search"/>
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
	<p><small class="clearfix">
	Instructions: 
	The search has been limited to 25 records.
	This is meant for individual record updates. To edit sets of records, please contact your database administrator.
	</small></p>

	<?php 
	foreach($editrecords as $key=>$fullrecord)
	{	$f = $fullrecord['Fullrecord'];
	?>
		<div class="clearfix row-even">
		
		<div class="col-md-10">
		
			<span><?=$f['practicename']?></span><br><span><?=$f['address']?></span> <a href="javascript:void(0);" class="show-div-btn" data-div="update-div-<?=$key?>">Show Record</a>
			
			
			</div>
			<div class="col-md-2">
			</div>
			<div class="col-md-12">
				<div id='update-div-<?=$key?>' class="update-div" style="display:none">
				<form method="post" action="./updateprovider">
				<p><small>Instructions: Please follow Command Printing's Data format for proper input types.</small></p>
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
					<li><label for="<?=$k?><?=$key?>-edit"><?=$k?></label>: <input type="text" name="<?=$k?>" value="<?=$v?>" id="<?=$k?><?=$key?>-edit"/></li>
				<?php 
					$count++;
					} 
				?>
				</ul>
				<input type="submit" value="Update Record" class="btn btn-default btn-xs"/>
				</form>
				<form method="post" action="./deleteprovider">
				<input type="hidden" name="id"  value="<?=$f['id']?>">
				<input type="submit" value="Delete Record" class="btn btn-default btn-xs"/>
				</form>
				</div>
			</div>

		</div>
	<?php } ?>
	</div>
<?php } ?>

</div>
