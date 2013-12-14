<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 10/13/13
 * Time: 2:28 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php if (isset($_SESSION['subjectId']) && is_numeric($_SESSION['subjectId'])){?><p><a href='/attendance/student'>Attendance </a> </p><?php } ?>
<!--form action="/attendance/student" method="get">
    <p>Start Date: <input type="text" name ='startDate' class="datepicker" value="<?php echo $date['startDate'];?>" /></p>
    <p>End Date: <input type="text" name ='endDate' class="datepicker" value="<?php echo $date['endDate'];?>" /></p>
    <input type="submit" value="GO" />
</form-->
<?php
$formattedMarks = array();
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<ul>
    <!--li class="subject">
            <div class="name"><span class="label"> Name</span> <span class="data"><?php echo $marks['name'];?></span></div>
            <div class="exam-date"><span class="label">Date</span> <span class="data"><?php echo date("F j, Y", strtotime($marks['date']));?></span></div>
            <div class="score"><span class="label">Score</span> <span class="data"><?php echo $marks['score'];?></span></div>
        </li-->
</ul>

<?php

if(isset($marksInAllsubjects) && !empty($marksInAllsubjects))
{

    foreach($marksInAllsubjects as $marks)
    {
        $formattedMarks[$marks['name']] [] = array('date'=>date("F j, Y", strtotime($marks['date'])),'score'=>$marks['score'],'code'=>$marks['code'],'max_marks'=>$marks['max_marks']);
    }

    $maxCount = getMaxScoreCountFromSubject($formattedMarks);
    $chartData = array();
    $table = array();
    $table['cols'][] = array('label' => 'X', 'type' => 'string');
    $rows = array();
    $temp = array();
    $temp1= array();
    $temp2 = array();
    $temp2[] = $temp1[] = array('v' => 'Marks');

    foreach($formattedMarks as $subject=>$allMarks){

       $table['cols'][] = array('label' => $subject, 'type' => 'number');
       //$table['cols'][] = array('label' => 'MARKS 2', 'type' => 'number');

        foreach($allMarks as $key=>$marks){

            $chartData[$subject][] = $marks['score'];
            // each column needs to have data inserted via the $temp array
            $temp[$key][0] = array('v' => 'Marks');
            $temp[$key][] =array('v' => (int)$marks['score']);
        }
        // insert the temp array into $rows
    }

    foreach($temp as $data){
        $rows[] = array('c' => $data);
    }
    $table['rows'] = $rows;
    // encode the table as JSON
    $jsonTable = json_encode($table);
    $data = array();
    $data['jsonTable'] = $jsonTable;
    $data['count'] = $subject;
    $this->load->view(TEMPLATE.'/'.'allSubjectScoreChart',$data);
}

if(isset($marksInsubject) && !empty($marksInsubject))
{

    foreach($marksInsubject as $marks)
    {
        $formattedMarks[$marks['name']] [] = array('date'=>date("F j, Y", strtotime($marks['date'])),'score'=>$marks['score'],'code'=>$marks['code'],'max_marks'=>$marks['max_marks']);
    }

    $table = array();
    $rows = array();

    foreach($formattedMarks as $subject=>$allMarks)
    {

        $table['cols'][] = array('label' => 'X', 'type' => 'string');
        $table['cols'][] = array('label' => $subject, 'type' => 'number');
        //$table['cols'][] = array('label' => 'MARKS 2', 'type' => 'number');

        foreach($allMarks as $marks){
            $chartData[$subject][] = $marks['score'];
            $temp = array();
            // each column needs to have data inserted via the $temp array
            $temp[] = array('v' => $marks['date']);
            $temp[] = array('v' => (int)$marks['score']);
            $rows[] = array('c' => $temp);
        }
    }
    $table['rows'] = $rows;
    // encode the table as JSON
    $jsonTable = json_encode($table);
    $data = array();
    $data['jsonTable'] = $jsonTable;
    $data['count'] = $subject;
    $this->load->view(TEMPLATE.'/'.'allSubjectScoreChart',$data);
}

?>

<div class="marksTable">
    <table class="subject">
    <?php $i=0;foreach($formattedMarks as $subject=>$marks){?>
            <tr class="<?php echo (($i++)%2) == 0?'even':'odd';?>">
            <td class="subject_name"><?php echo $subject;?></td>
            <?php foreach($marks as $data){?>
                <td class="score"><?php echo $data['score'];?></td>
            <?php } ?>
            </tr>
    <?php }?>
    </table>
</div>
 <div style="clear: both;"></div>
