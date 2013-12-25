<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 12/17/13
 * Time: 10:41 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php $this->load->view('/template/teacherHeader');?>

<ul>
    <?php if(isset($showAll)) {?>
        <li><a href="/<?php echo $controller.'/all';?>"> All (Related to you.) </a></li>
    <?php } ?>


    <?php if(isset($teacherInfo) && !empty($teacherInfo)) { foreach($teacherInfo as $teacher){ ?>
    <li><a href="/<?php echo $controller.'/'.$teacher['c_name'].'-'.$teacher['s_name'];?>"> <?php echo $teacher['c_name'] .' ' . $teacher['s_name'];?></a></li>
    <?php } } ?>
</ul>