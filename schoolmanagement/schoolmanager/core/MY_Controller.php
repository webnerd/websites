<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 9/29/13
 * Time: 7:39 PM
 * To change this template use File | Settings | File Templates.
 */

class MY_Controller extends CI_Controller
{
    public $structure = array();
    public $data;
    function __construct()
    {
        parent::__construct();
        $this->structure['header'] = 'header';
        $this->structure['footer'] = 'footer';
    }

    public function load_structure($views)
    {
        $this->load->view(TEMPLATE.'/'.COMMON.'/'.$this->structure['header'],$this->data);
        foreach($views as $view){
            $this->load->view(TEMPLATE.'/'.$view,$this->data);
        }
        $this->load->view(TEMPLATE.'/'.COMMON.'/'.$this->structure['footer'],$this->data);
    }
}