<?php
$q = $db->prepare("SELECT * FROM watchers WHERE unwatch_key = ?");
$q->execute(array($_GET['unsubscribe']));
$r = $q->fetch(PDO::FETCH_ASSOC);

$q = $db->prepare("SELECT * FROM watching WHERE id = ?");
$q->execute(array($r['watching']));
$p = $q->fetch(PDO::FETCH_ASSOC);

$q = $db->prepare("DELETE FROM watchers WHERE unwatch_key = ?");
$q->execute(array($_GET['unsubscribe']));
?>
<div class="m-b-md">
	You have successfully unsubscribed for the keyword <?=$p['phrase']?>.<br />
	If you would like to unsubscribe from all notifications please click <a href="home.php?unsub_email=<?=htmlentities($r['email'])?>">here.</a>
	<br /><br />

	You can resubscribe or subscribe to new keywords <a href="home.php">here.</a>
</div>