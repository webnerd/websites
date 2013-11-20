<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Database');
        $this->load->helper('utility');
        //$this->output->cache(1440);
        $this->output->enable_profiler(TRUE);
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index($usernameId)
    {
        if(isset($_SESSION['username-userid']) && $_SESSION['username-userid'] == ($usernameId))
        {
            switch($_SESSION['roleId'])
            {
                case ADMIN: break;
                case STUDENT : $this->student();break;
                case PARENT: $this->parent();break;
                case TEACHER: $this->teacher();break;
                default: echo 'Login failed'; break;
            }
        }
        else
        {
             //header('location: /login');
            echo 'ji';exit();
        }

    }

    public function login()
    {

        if(!isset($_SESSION['encodedUserId']))
        {
            $this->data = $this->input->get();

            if(!empty($this->data))
            {
                $result = $this->Database->getUserInfo($this->data);
                $_SESSION['encodedUserId']=$this->data['encodedUserId'] = $result['encoded_id'];
                $_SESSION['roleId']=$this->data['roleId'] = $result['role_id'];
                $_SESSION['userId']=$this->data['id'] = $result['id'];
                $_SESSION['username'] = $this->data['username'] = $result['username'];
                $_SESSION['username-userid'] = $_SESSION['username'].'-'.$_SESSION['userId'];
                header('location: /'.$_SESSION['username-userid']);
                exit();
            }
            else
            {
                echo 'Please Login';
            }
        }
        else
        {
            header('location: /'.$_SESSION['username'].'-'.$_SESSION['userId']);
            exit();
        }
    }

    public function setStudentSessionData($userId)
    {
        $studentInfo = $this->Database->getStudentInfo($userId);
        $studentId = $_SESSION['studentId'] = $studentInfo['id'];
        $_SESSION['schoolId'] = $studentInfo['school_id'];

        if(!isset($_SESSION['classId']))
        {
            $userDetails = $this->Database->getClassAndSectionOfStudent($studentId);
            $_SESSION['classId'] = $userDetails['classId'];
        }

    }

    public function student($userId=false)
    {
        if(!$userId)
        $userId = $_SESSION['userId'];
        $this->setStudentSessionData($userId);
        $examSeriesDetails = $this->Database->getExamSeriesForeStudent($_SESSION['schoolId'],$_SESSION['classId']);

        $this->data['examSeriesDetails'] = $examSeriesDetails;
        $this->data['subjects'] = $this->Database->getAllSubject($_SESSION['schoolId'],$_SESSION['classId']);

        $structure['content'] = 'welcome_message';
        $this->load_structure($structure);
    }

    public function ward($userId)
    {

    }
    private function parent()
    {
        $parentInfo = $this->Database->getParentInfo($_SESSION['userId']);
        $this->data['studentInfo'] = $this->Database->getStudentsForParent($parentInfo['id']);
        $structure['content'] = 'parent';
        $this->load_structure($structure);
    }

    private function teacher()
    {
        $this->data['teacherInfo'] = $this->Database->getTeacherClassAssociation($_SESSION['userId']);
        $this->data['controller'] = 'students';
        $structure['content'] = 'teacher';
        $this->load_structure($structure);
    }

    public function studentsInClassSection($classSection)
    {
        list($className,$sectionName) = explode('-',$classSection);
        $this->data['class'] = $this->Database->getStudentsByClassSectionId($className,$sectionName);
        $this->data['classSection'] = $classSection;
        $structure['content'] = 'class';
        $this->load_structure($structure);
    }
    public function logout()
    {
        session_destroy();
    }

    public function examSeries($examSeriesId){

        $examDetails = $this->Database->getExamsForStudent($_SESSION['schoolId'] ,$_SESSION['classId'] );
        $exams = array();
        foreach($examDetails as $exam){
            $exams[] = $exam['id'];
        }

        $marks = $this->Database->getMarksScoredByStudent($_SESSION['studentId'],$exams);

        $this->data['marksInExamSeries'] = $this->Database->getMarksScoredByStudentInExamSeries($_SESSION['studentId'], $examSeriesId);

        $structure['content'] = 'marks';
        $this->load_structure($structure);
    }

    public function subject($subjectId)
    {
        $structure = array();
        if(strtolower($subjectId) == 'all'){
            $this->data['marksInAllsubjects'] = $this->Database->getMarksScoredInAllSubject($_SESSION['studentId']);
            $structure['content'] = 'subject';
        }
        elseif(is_numeric($subjectId))
        {
            $this->data['marksInsubject'] = $this->Database->getMarksScoredInSubject($_SESSION['studentId'],$subjectId);
            $structure['content'] = 'subject';
        }

        $this->load_structure($structure);
    }


    public function attendance()
    {
        $this->data['teacherInfo'] = $this->Database->getTeacherClassAssociation($_SESSION['userId']);
        $this->data['controller'] = 'attendance/class';
        $structure['content'] = 'teacher';
        $this->load_structure($structure);
    }

    public function classAttendance($classSection)
    {
        list($className,$sectionName) = explode('-',$classSection);
        $this->data['class'] = $this->Database->getStudentsByClassSectionId($className,$sectionName);
        $studentList = array();
        foreach($this->data['class'] as $student)
        {
            $studentList[]=$student['id'];
        }

        if(!empty($_GET['date']))
        {
            $date = $_GET['date'];
        }
        else
        {
            $date = date('Y-m-d');
        }

        $this->data['classAttendance'] = $this->Database->getClassAttendance($studentList,$_SESSION['userId'],$date);
        $this->data['date'] = $date;
        $structure['content'] = 'classAttendance';
        $this->load_structure($structure);

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */