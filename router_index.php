<?php
    // Added by zespirit in 2019: this header deals with CORS issues.
    header("Access-Control-Allow-Origin: *");

    session_start();
    if ($_SESSION["username"] == "admin") {
        // Good enough for me
        header("location: home.php?action=view");
    }

    // try to open the file and if it doesn't exist run setup
    if (!file_exists("admin.pin.txt") && !$_GET["dontsetup"]) {
        header("location: setup.php");
    }
?>

<html>
    <head>
        <link href='https://fonts.googleapis.com/css?family=Jura:400,500,600,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>

    <body>
        <div id="loginDiv">
            <img src="http://hmapr.com/wp-content/uploads/2011/07/secure-lock.png"/>
            <h1>CISCO Administration Portal</h1>
            <h2>Authentication Required</h2>
        </div>

        <div id="loginForm">
            <?php if($_GET["error"]) { ?>
                <p class="error"><?php echo $_GET["error_string"]; ?></p>
            <?php } ?>

            <form id="login_form" action="login.php" method="POST" onsubmit="return validateLoginForm()" >
                <input type="text" name="username" placeholder="jcarberry"/>
                <input type="password" name="pin" placeholder="1234"/>
                <p>I am not a robot <input type="radio" name="notRobot"/></p>
                <input type="submit" value="Submit"/>
            </form>
        </div>
    </body>
    <script>
        function validateLoginForm() {
            form = document.getElementById("login_form");
            if (!form["notRobot"].checked) {
                alert("You must not be a robot in order to submit this form.");
                return false;
            }
        }
    </script>
</html>
