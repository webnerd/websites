<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 10/19/13
 * Time: 9:24 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<ul>
    <?php
foreach($studentInfo as $student)
{
?>
    <li><a href='/<?php echo $student['username'];?>'> <?php echo $student['fname'];?></a></li>
<?php } ?>