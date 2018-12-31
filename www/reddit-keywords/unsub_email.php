<?php
$q = $db->prepare("SELECT * FROM watchers WHERE email = ?");
$q->execute(array($_GET['unsub_email']));
$r = $q->fetchAll(PDO::FETCH_ASSOC);

$q = $db->prepare("DELETE FROM watchers WHERE email = ?");
$q->execute(array($_GET['unsub_email']));
?>
<div class="m-b-md">
	You have successfully unsubscribed from <?=count($r)?> keywords.
	<br /><br />

	You can resubscribe or subscribe to new keywords <a href="home.php">here.</a>
</div>