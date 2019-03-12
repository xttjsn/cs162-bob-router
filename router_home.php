<?php
    // Added by zespirit in 2019: this header deals with CORS issues.
    header("Access-Control-Allow-Origin: *");

    session_start();

    // Validate the current session
    if ($_SESSION["username"] !== "admin") {
        // redirect to login
        header("location: index.php");
    }

    // User is authenticated.
?>

<html>
    <head>
        <link href='https://fonts.googleapis.com/css?family=Jura:400,500,600,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="home.css">
    </head>
    <body>
        <h1> Welcome, Admin! </h1>
        <h2> Router Stats </h2>
    </body>
    <?php
        $action = $_GET["action"];
        if ($action) {
            include($action . ".php");
        }
    ?>
</html>
