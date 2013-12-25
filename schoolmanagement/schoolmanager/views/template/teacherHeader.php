<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 12/17/13
 * Time: 10:46 PM
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="header">
   <span>
       <a href="/score/<?php echo $_SESSION['username'];?>">Score</a>
   </span>
    <span>
        <a href="/attendance/<?php echo $_SESSION['username'];?>">Attendance</a>
    </span>
    <span>
        <a href="/discussion-group/<?php echo $_SESSION['username'];?>">Discussion Forum</a>
    </span>
</div>