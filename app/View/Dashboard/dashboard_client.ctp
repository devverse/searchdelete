
Client Dashboard

<br/>
<?php
	foreach($links as $link)
	{
		$l = ucfirst($link);
		echo "<a href='./a/{$link}'>Go to {$l}</a><br>";
	}

?>
