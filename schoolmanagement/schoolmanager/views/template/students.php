<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 10/26/13
 * Time: 5:59 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php $this->load->view('/template/teacherHeader');?>

<div class='class_name'>Class : <?php echo $classSection;?></div>

<table class="students">
    <thead>
        <th>Roll No</th>
        <th>First Name</th>
        <th>Last Name</th>
    </thead>
    <tbody>
        <?php $i=0; foreach($class as $student){?>
        <tr class="student_info <?php echo (($i++)%2 == 0)?'even':'odd';?>">

                <td> <a href="/<?php echo $student['username'];?>"><?php echo $student['roll_no'];?> </a></td>
                <td><?php echo $student['fname'];?></td>
                <td><?php echo $student['lname'];?></td>

        </tr>
        <?php } ?>
    </tbody>
</table>