<p> Exams Series</p>    
<ul>
        <li><a href="/exam-series/all">All</a></li>
        <?php foreach($examSeriesDetails as $examSeries){?>
        <li><a href="/exam-series/<?php echo $examSeries['id']; ?>" > <?php echo $examSeries['name'];?>(<?php echo date("F j, Y", strtotime($examSeries['start_date']) );?>)</a></li>
        <?php } ?>
    </ul>

<p> Subjects</p>

    <ul>
        <li><a href="/subject/all">All</a></li>
        <?php foreach($subjects as $subject) {?>
           <li>
            <a href='/subject/<?php echo $subject['id'];?>' ><?php echo $subject['name'];?></a>
           </li>
        <?php } ?>
    </ul>

<p>Discussions</p>
    <a href="/discussion-listing/all">All (Related to you.)</a>