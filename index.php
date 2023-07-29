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
        <div role="alert" id="toastlogin" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
            <div class="toast-header">
                <img src="logo.png" class="rounded mr-2" alt="...">
                <strong class="mr-auto">ABC Login</strong>
                <small>11 mins ago</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </body>
    <script src="./assets/scripts/js/lasagna.js"></script>
</html>