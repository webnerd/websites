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
        .header span
        {
            margin:5px;
        }
        #welcome_note, .class_name
        {
            color:green;
            font-size: large;
            font-weight: bold;
            margin-bottom: 1em;
        }

        table .even, table .even
        {
            background-color: #d4d4d4;
        }

        table .odd, table .odd
        {
            background-color: #7ab5d3;
        }

        table tr td
        {
            padding:5px;
            text-align: center;
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
        img.ui-datepicker-trigger
        {
            position: relative;
            right: 28px;
            top: 5px;
        }
    </style>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
        $(function() {
            $( "#datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd',
                showOn: "button",
                buttonImage: "/web/images/calendar_icon.jpg",
                buttonImageOnly: true
            });
        });
    </script>
</head>
<body>
<div id="welcome_note">Welcome <?php echo $_SESSION['username'];?>!!</div>
<div class="header">
    <span><a href="/<?php echo $_SESSION['username-userid'];?>"> HOME </a></span>
    <span><a href="/logout"> LOGOUT </a></span>
</div>

<div id="container">
