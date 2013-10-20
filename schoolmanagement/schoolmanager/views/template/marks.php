<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php

$marksArray = array();
foreach($marksInExamSeries as $scoreDetails){
    $marksArray[$scoreDetails['exam_series_id']][] = array($scoreDetails['name'],(integer)$scoreDetails['score']);
}
foreach($marksArray as $key=>$marks){
    $data['marks'] = $marks;
    $data['count'] = $key;
    $this->load->view(TEMPLATE.'/'.'examSeriesScoreChart',$data);
}

?>
