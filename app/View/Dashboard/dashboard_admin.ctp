<form id="clientsdd">
Edit a clients configuration.
<select name="client">
<?php
foreach($clients as $client)
{
	echo "<option value='{$client['Client']['name']}'>{$client['Client']['company_name']}</option>";
}
?>
</select>
</form>

<script type="text/javascript">
$(function(){
	$('.client-div').hide(); 
    $('#clientsdd')[0].reset();
    $('#clientsdd').on('change','select',function()
    {
       var inputValue = $(this).val();
       $('.client-div').hide();
       $('#'+inputValue+'-div').show();   
	});
});
</script>

<?php foreach($clients as $client){ ?>
<div  id="<?=$client['Client']['name']?>-div" class="client-div">
<form method="post" action="./updateClient">
<table>
<tr>
<th><strong>Views File</strong></th>
<th><strong>Assets</strong></th>
<th><strong>Site Status</strong></th>
<th><strong></strong></th>
</tr>
<tr>
<h3><?=$client['Client']['company_name']?></h3>
<input type="hidden" name="id" value="<?=$client['Client']['id']?>">
<input type="hidden" name="name" value="<?=$client['Client']['name']?>">
<td>
<select name="view_prefix_name" autocomplete="off">
	<option value="custom">Custom</option>
	<option value="" <?php if($client['Client']['view_prefix_name']==''){echo "selected='selected'";}?>>Default</option>
</select>
</td>
<td>
<select name="asset_folder_name" autocomplete="off">
	<option value="custom">Custsom</option>
	<option value="" <?php if($client['Client']['asset_folder_name']==''){echo "selected='selected'";}?>>Default</option>
</select>
</td>
<td>
<select name="disable" autocomplete="off">
	<option value="1">Disable</option>
	<option value="0" <?php if($client['Client']['disable']=='0'){echo "selected='selected'";}?>>Enable</option>
</select>
</td>
<td>
<input type="submit" value="update">
</td>
</tr>
</table>
</form>
</div>
<?php } ?>

<br/>
--OR--
<br/>
<br/>
Add a new Client
<form method="post" action="./addClient">
<input type="text" name="new_client_name"/>
<input type="submit" value="Add New Client">
</form>
<?php echo $this->Session->flash('err_msg'); ?>
<?php echo $this->Session->flash('succ_msg'); ?>
<?php echo $this->Session->flash('info_msg'); ?>


