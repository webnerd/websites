<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Database');
        $this->load->helper('utility');
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
    public function index()
    {
        if(!isset($_SESSION['encodedUserId']))
        {
            $this->data = $this->input->get();
            $result = $this->Database->getUserInfo($this->data);

            $_SESSION['encodedUserId']=$this->data['encodedUserId'] = $result['encoded_id'];
            $_SESSION['roleId']=$this->data['roleId'] = $result['role_id'];
            $_SESSION['userId']=$this->data['id'] = $result['id'];
            $_SESSION['username'] = $this->data['username'] = $result['username'];
        }


        switch($_SESSION['roleId'])
        {
            case ADMIN: break;
            case STUDENT : $this->student();break;
            case PARENT: $this->parent();break;
            case TEACHER: $this->teacher();break;
            default: echo 'Login failed'; break;
        }


    }

    public function student()
    {
        header('location: student/'.$_SESSION['userId']);
    }


    public function setStudentSessionData($userId)
    {
        $studentInfo = $this->Database->getStudentInfo($userId);
        $studentId = $_SESSION['studentId'] = $studentInfo['id'];
        $_SESSION['schoolId'] = $studentInfo['school_id'];

        $userDetails = $this->Database->getClassAndSectionOfStudent($studentId);
        $_SESSION['classId'] = $userDetails['classId'];
    }

    public function studentInfo($userId)
    {
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
        $structure['content'] = 'teacher';
        $this->load_structure($structure);
    }

    public function studentsInClassSection($classSection)
    {
        list($classId,$sectionName) = explode('-',$classSection);
        $this->data['students'] = $this->Database->getStudentsByClassSectionId($classId,$sectionName);
        var_dump($this->data['students']);
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */