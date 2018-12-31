<div class="m-b-md">
Want to be notified whenever a keyword appears on <a href="https://www.reddit.com/r/<?=$subreddit?>/" target="_blank">/r/<?=$subreddit?>?</a><br />
Fill out the form below and we'll email you if it does!
<br /><br />
	<form action="home.php?submit=new" method="POST">
		<input type="text" name="keyword" placeholder="Keyword(s) - Example: Strikefire II" required autofocus class="rounded-top form-control">
		<br />
		<input type="email" name="email" class="form-control" placeholder="Email address" value="<?=($_POST['email']) ? htmlentities($_POST['email']) : "" ?>" required>
		<br />
		<input type="submit" value="Get notified!" class="form-control btn-primary">
	</form>
</div>