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

    <style type="text/css">
        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }
        #body{
            margin: 0 15px 0 15px;
        }

        p.footer{
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container{
            margin: 10px;
            border: 1px solid #D0D0D0;
            -webkit-box-shadow: 0 0 8px #D0D0D0;
        }
        #header span
        {
            margin:5px;
        }
        #welcome_note
        {
            color:green;
            font-size: large;
            font-weight: bold;
            margin-bottom: 1em;
        }

        table.subject .even
        {
            background-color: #d4d4d4;
        }

        table.subject .odd
        {
            background-color: #7ab5d3;
        }
        table.subject .subject_name
        {
            text-align: center;
            padding:5px;
            width:10em;
        }
        table.subject .score
        {
            text-align: center;
            padding:5px;
            width:5em;
        }
    </style>
</head>
<body>
<div id="welcome_note">Welcome <?php echo $_SESSION['username'];?>!!</div>
<div id="header">
    <span><a href="/"> HOME </a></span>
    <span><a href="/logout"> LOGOUT </a></span>
</div>

<div id="container">
