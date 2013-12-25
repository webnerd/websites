<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Database');
        $this->load->helper('utility');
        //$this->output->cache(1440);
        //$this->output->enable_profiler(TRUE);
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
    public function index($username)
    {
        $roleId = '';
        if(isset($_SESSION['username']) && $_SESSION['username'] == ($username))
        {
            $roleId = $_SESSION['roleId'];
            $_SESSION['userId'] = $_SESSION['loggedInUserId'];
        }
        else
        {
            $userData = $this->Database->getUserDataByUsername($username);
            $roleId = $userData['role_id'];
            $_SESSION['userId'] = $userData['id'];
        }

        switch($roleId)
        {
            case ADMIN: break;
            case STUDENT : $this->student();break;
            case PARENT: $this->parent();break;
            case TEACHER: $this->teacher();break;
            default: echo 'Login failed'; break;
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
                $_SESSION['loggedInUserId']=$this->data['id'] = $result['id'];
                $_SESSION['username'] = $this->data['username'] = $result['username'];
                $_SESSION['username-userid'] = $_SESSION['username'].'-'.$_SESSION['userId'];
                header('location: /'.$_SESSION['username']);
                exit();
            }
            else
            {
                echo 'Please Login';
            }
        }
        else
        {
            header('location: /'.$_SESSION['username']);
            exit();
        }
    }

    public function setStudentSessionData($userId)
    {

        $studentInfo = $this->Database->getStudentInfo($userId);
        $_SESSION['studentId'] = $studentInfo['id'];
        $_SESSION['schoolId'] = $studentInfo['school_id'];
        $userDetails = $this->Database->getClassAndSectionOfStudent($_SESSION['studentId']);
        $_SESSION['classId'] = $userDetails['classId'];
        $_SESSION['sectionId'] = $userDetails['sectionId'];

    }

    public function student($userId=false)
    {

        if(!$userId)
        $userId = $_SESSION['userId'];
        $this->setStudentSessionData($userId);
        $examSeriesDetails = $this->Database->getExamSeriesForeStudent($_SESSION['schoolId'],$_SESSION['classId']);

        $this->data['examSeriesDetails'] = $examSeriesDetails;
        $this->data['subjects'] = $this->Database->getAllSubject($_SESSION['schoolId'],$_SESSION['classId']);

        $date['endDate'] = date('Y-m-d');
        $date['startDate'] = date('Y-m-d',strtotime('-1 month'));
        $this->data['date'] = $date;

        $structure['content'] = 'welcome_message';
        $this->load_structure($structure);
    }

    public function ward($userId)
    {

    }
    private function parent()
    {
        $parentInfo = $this->Database->getParentInfo($_SESSION['loggedInUserId']);
        $this->data['studentInfo'] = $this->Database->getStudentsForParent($parentInfo['id']);
        $structure['content'] = 'parent';
        $this->load_structure($structure);
    }

    private function teacher()
    {
        $teacherInfo = $this->Database->getTeacherInfo($_SESSION['loggedInUserId']);
        $_SESSION['teacherUserId'] = $_SESSION['loggedInUserId'];
        $_SESSION['schoolId'] = $teacherInfo['school_id'];
        $_SESSION['teacherId'] = $teacherInfo['id'];
        $structure['content'] = 'teacher';
        $this->load_structure($structure);
    }

    public function studentsInClassSection($classSection)
    {
        list($className,$sectionName) = explode('-',$classSection);
        $this->data['class'] = $this->Database->getStudentsByClassSectionId($className,$sectionName);
        $this->data['classSection'] = $classSection;
        $structure['content'] = 'students';
        $this->load_structure($structure);
    }

    public function logout()
    {
        session_destroy();
    }

    public function examSeries($examSeriesId)
    {

       /*
       $examDetails = $this->Database->getExamsForStudent($_SESSION['schoolId'] ,$_SESSION['classId'] );
        $exams = array();
        foreach($examDetails as $exam){
            $exams[] = $exam['id'];
        }

        $marks = $this->Database->getMarksScoredByStudent($_SESSION['studentId'],$exams);
      */
        $this->data['marksInExamSeries'] = $this->Database->getMarksScoredByStudentInExamSeries($_SESSION['studentId'], $examSeriesId);

        $structure['content'] = 'marks';
        $this->load_structure($structure);
    }

    public function subject($subjectId)
    {
        $_SESSION['subjectId'] = $subjectId;
        $structure = array();
        if(strtolower($subjectId) == 'all'){
            $this->data['marksInAllsubjects'] = $this->Database->getMarksScoredInAllSubject($_SESSION['studentId']);
            $structure['content'] = 'subject';
        }
        elseif(is_numeric($subjectId))
        {
            $this->data['marksInsubject'] = $this->Database->getMarksScoredInSubject($_SESSION['studentId'],$subjectId);
            $structure['content'] = 'subject';
            $teacherData = $this->Database->getTeacherFromSubject($subjectId);
            $_SESSION['teacherUserId'] = $teacherData['user_id'];
        }

        $this->load_structure($structure);
    }


    public function score()
    {
        $this->data['teacherInfo'] = $this->Database->getTeacherClassAssociation($_SESSION['loggedInUserId']);
        $this->data['controller'] = 'students';
        $structure['content'] = 'allClass';
        $this->load_structure($structure);
    }

    public function discussionGroup()
    {
        switch($_SESSION['roleId'])
        {
            case ADMIN: break;
            case STUDENT : break;
            case PARENT: break;
            case TEACHER: $this->data['teacherInfo'] = $this->Database->getTeacherClassAssociation($_SESSION['loggedInUserId']);break;
            default: echo 'Login failed'; break;
        }

        $this->data['controller'] = 'discussion-listing';
        $this->data['showAll'] = true;
        $structure['content'] = 'allClass';
        $this->load_structure($structure);
    }

    public function discussionListing($discussionGroup = false)
    {
        if($discussionGroup && $discussionGroup !='all')
        {
            list($className,$sectionName) = explode('-',$discussionGroup);
            $csLookup = $this->Database->getClassSectionLookupIdByName($className,$sectionName);
            $discussionGroup = $csLookup['id'];
        }

        $this->data['discussionListing'] = $this->Database->getDiscussionListingForDiscussionGroup($discussionGroup);
        $this->data['controller'] = 'discussion';
        $structure['content'] = 'discussionListing';
        $this->load_structure($structure);
    }

    public function discussion($discussionSeoTitle)
    {
        $discussionTopicData = $this->Database->getDiscussionData($discussionSeoTitle);
        $this->data['discussionCommentData'] = $this->Database->getDiscussionCommentData($discussionTopicData['id']);
        $this->data['discussionTopicData'] = $discussionTopicData;
        $structure['content'] = 'discussion';
        $this->load_structure($structure);
    }
    public function attendance()
    {
        $this->data['teacherInfo'] = $this->Database->getTeacherClassAssociation($_SESSION['loggedInUserId']);
        $this->data['controller'] = 'attendance/class';
        $structure['content'] = 'allClass';
        $this->load_structure($structure);
    }

    public function classAttendance($classSection)
    {
        list($className,$sectionName) = explode('-',$classSection);
        $_SESSION['className'] = $className;
        $_SESSION['sectionName'] = $sectionName;
        $this->data['class'] = $this->Database->getStudentsByClassSectionId($className,$sectionName);
        $_SESSION['cs_lookup_id'] = $this->data['class'][0]['cs_lookup_id'];
        $studentList = array();
        foreach($this->data['class'] as $student)
        {
            $studentList[]=$student['id'];
        }

        if(!empty($_GET['startDate']))
        {
            $date['startDate'] = $_GET['startDate'];
        }
        else
        {
            $date['startDate'] = date('Y-m-d');
        }

        $teacherData = $this->Database->getSubjectByTeacherId($_SESSION['teacherId']);
        $_SESSION['subjectId'] = $teacherData['subject_id'];
        $this->data['classAttendance'] = $this->Database->getClassAttendance($studentList,$_SESSION['teacherUserId'],$date);
        $this->data['date'] = $date;
        $structure['content'] = 'classAttendance';
        $this->load_structure($structure);
    }

    public function studentAttendance($studentId = false)
    {
        $date = array();

        if(!empty($_GET['startDate']))
        {
            $date['startDate'] = $_GET['startDate'];
        }
        else
        {
            $date['startDate'] = date('Y-m-d',strtotime('-1 month'));
        }

        if(!empty($_GET['endDate']))
        {
            $date['endDate'] = $_GET['endDate'];
        }
        else
        {
            $date['endDate'] = date('Y-m-d');
        }

        if(false !== $studentId)
        {
            $studentList[] = $studentId;
        }
        else{
            $studentList[] = $_SESSION['studentId'];
        }

        $this->data['studentAttendanceData'] = $this->Database->getClassAttendance($studentList,$_SESSION['teacherUserId'],$date);
        $this->data['date'] = $date;
        $structure['content'] = 'studentAttendance';
        $this->load_structure($structure);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */