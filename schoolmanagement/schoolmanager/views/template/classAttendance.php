<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 11/6/13
 * Time: 11:29 AM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php $this->load->view('/template/teacherHeader');?>

    <!--div class="sub-header">
        <span>
       <a href="">View</a>
   </span>


        <span>
       <a href="">Record</a>
   </span>
    </div-->

    <form action="" method="get">
        <p>Date: <input type="text" name ='startDate' class="datepicker" value="<?php echo $date['startDate'];?>" /></p>
        <input type="submit" value="GO" />
    </form>
<table class="attendance">
    <thead>
    <th>Roll No</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Status</th>
    <th>Reason</th>
    <th>Detailed Attendance</th>
    </thead>
    <tbody>
    <?php $i=0; foreach($classAttendance as $studentAttendance) { ?>
    <tr class="<?php echo (($i++)%2) == 0?'even':'odd';?>">
        <td> <a href="/<?php echo $studentAttendance['username'];?>"><?php echo $studentAttendance['roll_no'];?> </a></td>
        <td><?php echo $studentAttendance['fname'];?></td>
        <td><?php echo $studentAttendance['lname'];?></td>
        <td><?php echo $studentAttendance['status'] == 1?'Present':'Absent';?></td>
        <td> <?php if($studentAttendance['status'] ==  0) {?>
            <?php echo $studentAttendance['reason']; }?>
        </td>
        <td><a href='/attendance/student/<?php echo $studentAttendance['id'];?>?startDate=<?php echo $date['startDate'];?>'>View</a></td>
    </tr>
    <?php } ?>
    </tbody>
</table>