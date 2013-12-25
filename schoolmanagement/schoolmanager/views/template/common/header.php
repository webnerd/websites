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
</head>
<body>
<div id="welcome_note">Welcome <?php echo $_SESSION['username'];?>!!</div>
<div class="header">
    <span><a href="/<?php echo $_SESSION['username'];?>"> HOME </a></span>
    <span><a href="/logout"> LOGOUT </a></span>
</div>

<div id="container">
