<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 12/17/13
 * Time: 10:49 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php $this->load->view('/template/teacherHeader');?>

<ul>
    <?php foreach($discussionListing as $discussion){?>
    <li><a href="/<?php echo $controller.'/'.$discussion['seo_title'];?>"> <?php echo $discussion['title'];?></a></li>

    <?php } ?>
</ul>
