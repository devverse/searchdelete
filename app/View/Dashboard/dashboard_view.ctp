<script type='text/javascript'>
	$(document).ready(function() {

	});

</script>
<form>
<?php 
		
		foreach ($record_keys as $rkey)
		{
			if($rkey == 'id')
				continue;
			echo $rkey;
			echo "<input type='text' name='{$rkey}'>";
		} 
	?>
<input type="submit" value="Add Record"/>
</form>

<br/>

<form id="nextresult" class="form-horizontal" action="./providers/view" method="post"></form>
<a href='javascript:void(0);' onclick='submintview(<?=$prev_index?>)'>&lt;&lt; Previous</a>
<a href='javascript:void(0);' onclick='submintview(<?=$next_index?>)'>Next &gt;&gt;</a>

<script type="text/javascript">
function submintview(type){
	$('#nextresult').append("<input type='hidden' name='pg_index' value='"+type+"'/>").submit();
}
</script>

</div>

<ul>
<?php foreach ($records as $key => $record) {
?>
<li>
<form action="./provider/edit" method="POST">
	<input type='hidden' value='<?	echo ($record['id']) ?>'/ name="edit_id">
	<input type='submit' value='edit'/>
</form>
<form action="./provider/delete" method="POST">
	<input type='hidden' value='<?	echo ($record['id']) ?>'/ name="del_id">
	<input type='submit' value='delete'/>
</form>&nbsp;
<!--do a form here for editing-->
	<?php 
		
		foreach ($record_keys as $rkey)
		{
			if($rkey != 'id')
				echo $record[$rkey];
		} 
	?>
</li>
<?php
}

?>
</ul>
<br/>
