<?php
$q = $db->prepare("SELECT * FROM watching WHERE lower_phrase = ?");
$q->execute(array(strtolower($_POST['keyword'])));
$r = $q->fetchAll(PDO::FETCH_ASSOC);
if(count($r) != 1) {
	$q = $db->prepare("INSERT INTO watching (phrase, lower_phrase) VALUES (?, ?)");
	$q->execute(array($_POST['keyword'], strtolower($_POST['keyword'])));
	$q = $db->prepare("SELECT * FROM watching WHERE lower_phrase = ? ORDER BY id DESC LIMIT 1");
	$q->execute(array(strtolower($_POST['keyword'])));
	$r = $q->fetchAll(PDO::FETCH_ASSOC);
}
$q = $db->prepare("INSERT INTO watchers (email, watching, unwatch_key) VALUES (?, ?, ?)");
$key = md5($_POST['email'] . time());
$q->execute(array($_POST['email'], $r[0]['id'], $key));

?>

<div class="m-b-md">
	You have successfully subscribed for the keyword <?=htmlentities($_POST['keyword'])?>.<br /><br />

	You can <strong>unsubscribe</strong> anytime by clicking <a href="home.php?unsubscribe=<?=$key?>">here.</a><br />
	<small>This link will be sent with every email.</small>
	<hr />
</div>