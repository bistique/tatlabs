<?php
    require_once './lib/config.php';
    require_once './lib/header.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./css/login.css" />
    </head>
        <body>
        <div class="login-box">
            <h2>Login</h2>
            <form>
                <div class="user-box">
                    <input type="text" name="username" id="username" required="Type username">
                    <label>Username</label>
                </div>
                <div class="user-box">
                    <input type="password" name="pwd" id="pwd" required="Input password">
                    <label>Password</label>
                </div>
               
                <a href="" id="submit">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Submit
                </a>
            </form>
        </div>
    </body>
    <script src="./assets/scripts/js/lasagna.js"></script>
</html>