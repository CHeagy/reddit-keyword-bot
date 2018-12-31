<?php
include "reddit-keywords/config.php";
$dsn = "mysql:host=" . $hostname . ";dbname=" . $database;
$db = new PDO($dsn, $username, $password);
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>/r/<?=$subreddit?> Notifications</title>

        <!-- Fonts and Styles -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/reddit-keywords.css">
        
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
            	<?php
				if($_GET['submit'] == "new") {
					include "reddit-keywords/add_new.php";
					include "reddit-keywords/new_form.php";
				} else if($_GET['unsubscribe']) {
					include "reddit-keywords/unsubscribe.php";
				} else if($_GET['unsub_email']) {
					include "reddit-keywords/unsub_email.php";
				} else {
					include "reddit-keywords/new_form.php";
				}
				?>
            </div>
        </div>
    </body>
</html>