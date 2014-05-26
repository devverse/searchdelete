Edit an existing Client database

<form method="post" action="./updateClient">
Select a client
<select name="client">
<?php
foreach($clients as $client)
{
	echo "<option value='{$client['Client']['id']}'>{$client['Client']['company_name']}</option>";
}
?>
</select>
<input type="submit" value="Use Client">
</form>

<br/>
--OR--
<br/>

Add a new Client
<form method="post" action="./addClient">
<input type="text" name="new_client_name"/>
<input type="submit" value="Add New Client">
</form>
<?php echo $this->Session->flash('err_msg'); ?>
<?php echo $this->Session->flash('succ_msg'); ?>
<?php echo $this->Session->flash('info_msg'); ?>


