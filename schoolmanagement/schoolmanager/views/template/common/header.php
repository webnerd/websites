<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 9/29/13
 * Time: 7:34 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>E-School</title>
    <link rel="stylesheet" href='/web/css/default.css' />
    <link rel="stylesheet" href='/web/css/smoothness/jquery-ui-1.10.3.custom.css' />
    <script src='/web/js/jquery-1.9.1.js'></script>
    <script src='/web/js/jquery-ui-1.10.3.custom.js'></script>
    <script src='/web/js/default.js'></script>
</head>
<body>
<div id="welcome_note">Welcome <?php echo $_SESSION['username'];?>!!</div>
<div class="header">
    <span><a href="/<?php echo $_SESSION['username-userid'];?>"> HOME </a></span>
    <span><a href="/logout"> LOGOUT </a></span>
</div>

<div id="container">
