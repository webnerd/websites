<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 10/13/13
 * Time: 2:28 PM
 * To change this template use File | Settings | File Templates.
 */

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

if(isset($marksInAllsubjects))
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
    //$rows[] = array('c' => $temp1);
    //$rows[] = array('c' => $temp2);

}

if(isset($marksInsubject))
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
}


    $table['rows'] = $rows;
    // encode the table as JSON
    $jsonTable = json_encode($table);
    //var_dump($chartData);
    //var_dump($jsonTable);
    $data = array();
    $data['jsonTable'] = $jsonTable;
    $data['count'] = $subject;
    $this->load->view(TEMPLATE.'/'.'allSubjectScoreChart',$data);
?>

<div class="marksTable">
    
    <?php foreach($formattedMarks as $subject=>$marks){?>
        <div class="subject" style="float: left; border:1px solid;margin:10px;padding:10px">
            <p><?php echo $subject;?></p>
            <?php foreach($marks as $data){?>
                <span style="border:1px solid;padding: 5px;"><?php echo $data['score'];?></span>
            <?php } ?>
        </div>
    <?php }?>
    
</div>
 <div style="clear: both;"></div>
