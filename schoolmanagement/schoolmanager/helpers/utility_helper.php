<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 10/13/13
 * Time: 7:11 PM
 * To change this template use File | Settings | File Templates.
 */

function getMaxScoreCountFromSubject($array)
{
    if(is_array($array)){
        $result = array('count'=>0);
        foreach($array as $key=>$value){
            $count = count($value);
            if( $count >= $result['count']){
                $result['count'] = $count;
                $result['index'] = $key;
            }
        }
        return $result;
    }else{
        return 0;
    }
}