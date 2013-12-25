<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 11/6/13
 * Time: 11:29 AM
 * To change this template use File | Settings | File Templates.
 */
?>
<form action="/attendance/student" method="get">
    <p>Start Date: <input type="text" name ='startDate' class="datepicker" value="<?php echo $date['startDate'];?>" /></p>
    <p>End Date: <input type="text" name ='endDate' class="datepicker" value="<?php echo $date['endDate'];?>" /></p>
    <input type="submit" value="GO" />
</form>
<p>
    <?php //echo  $studentAttendanceData[0]['fname'].' '.$studentAttendanceData[0]['lname'];?>
</p>
<table class="attendance">
    <thead>
    <th>Date</th>
    <th>Roll No</th>
    <th>Status</th>
    <th>Reason</th>
    </thead>
    <tbody>
    <?php $i=0; foreach($studentAttendanceData as $studentAttendance) { ?>
    <tr class="<?php echo (($i++)%2) == 0?'even':'odd';?>">
        <td><?php echo $studentAttendance['date'];?></td>
        <td> <a href="/<?php echo $studentAttendance['username'];?>"><?php echo $studentAttendance['roll_no'];?> </a></td>
        <td><?php echo $studentAttendance['status'] == 1?'Present':'Absent';?></td>
        <td> <?php if($studentAttendance['status'] ==  0) {?>
            <?php echo $studentAttendance['reason']; }?>
        </td>
    </tr>
        <?php } ?>
    </tbody>
</table>