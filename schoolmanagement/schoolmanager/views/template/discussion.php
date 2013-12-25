<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 12/22/13
 * Time: 2:31 PM
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="discussion_container">
    <div class="discussion_topic_title"><?php echo $discussionTopicData['title'];?> </div>
    <div class="discussion_topic_description"><?php echo $discussionTopicData['description'];?> </div>
    <div class="discussion_topic_creator">By- <?php echo $discussionTopicData['username'];?> </div>
<p><strong>Comments</strong></p>
<table class="discussion_topic_comments">
    <thead>
    <th>Sr No</th>
    <th>Reason</th>
    <th>By</th>
    </thead>
    <tbody>
    <?php $i=0; foreach($discussionCommentData as $discussionComment) { ?>
    <tr class="<?php echo (($i++)%2) == 0?'even':'odd';?>">
        <td><?php echo $i; ?></td>
        <td><?php echo $discussionComment['content'];?></td>
        <td><?php echo $discussionComment['username'];?></td>
    </tr>
        <?php } ?>
    </tbody>
</table>

</div>