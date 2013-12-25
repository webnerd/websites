<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Prasad
 * Date: 9/29/13
 * Time: 3:16 PM
 * To change this template use File | Settings | File Templates.
 * echo $this->db->last_query();
 */
class Database extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getResultArray($query)
    {
        $result = array();
        foreach($query->result_array() as $row){
            $result[] = $row;
        }
        return $result;
    }
// Start USER related queries
    public function getUserInfo($user_info)
    {
        $this->db->select('id,encoded_id,role_id,username')
                 ->where(array('email_id' => $user_info['email'] , 'password'=>md5($user_info['pass'])));
        return $this->db->get('user')->row_array();
    }

    public function getUserDataByUsername($username)
    {
        $this->db->select('id,encoded_id,role_id')
            ->where(array('username' =>$username));
        return $this->db->get('user')->row_array();
    }

    public function getUserTypeInfo($userType,$userId)
    {
        return $this->db->get_where($userType,array('user_id'=>$userId))->row_array();
    }
// END USER related queries

// Start STUDENT related queries

    public function getStudentInfo($userId)
    {
        return $this->getUserTypeInfo('student',$userId);
    }

    public function getClassAndSectionOfStudent($studentId)
    {
        $this->db->select('section.name as section , class.name as class, class.id as classId, section.id as sectionId')
                        ->from('css_lookup')
                        ->join('cs_lookup', 'cs_lookup.id = css_lookup.cs_lookup_id')
                        ->join('class', 'cs_lookup.class_id = class.id')
                        ->join('section', 'cs_lookup.section_id = section.id')
                        ->where('css_lookup.student_id',$studentId);
        return $this->db->get()->row_array();
    }

    /*
     * To get al valid exams applicable for a student
     */
    public function getExamsForStudent($schoolId,$classId)
    {
        $this->db->select('exam.id')->from('exam')
                 ->join('exam_series','exam.exam_series_id = exam_series.id')
                 ->join('sc_lookup', 'exam_series.sc_lookup_id = sc_lookup.id')
                 ->where(array('sc_lookup.school_id' => $schoolId,'sc_lookup.class_id' => $classId));
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getExamSeriesForeStudent($schoolId,$classId)
    {
        $this->db->select('exam_series.*')->from('exam_series')
            ->join('sc_lookup', 'exam_series.sc_lookup_id = sc_lookup.id')
            ->where(array('sc_lookup.school_id' => $schoolId,'sc_lookup.class_id' => $classId));
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    /*
     * to get marks scored by student in all exams
     */
    public function getMarksScoredByStudent($studentId,$examId = array())
    {

        $this->db->select('*')->from('marks')
            ->where('student_id',$studentId)
            ->where_in('exam_id',$examId);
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

//-start
    /*
    * to get subjects Applicable for a student
    */
    public function getAllSubject($schoolId,$classId)
    {
        $this->db->select('subject.*')->from('subject')
        ->join('scs_lookup','subject.id = scs_lookup.subject_id')
        ->join('sc_lookup','sc_lookup.id = scs_lookup.sc_lookup_id')
        ->where(array('sc_lookup.school_id' => $schoolId,'sc_lookup.class_id' => $classId));
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    /*
     * to get marks scored by student in a subject
     */
    public function getMarksScoredInSubject($studentId,$subjectId)
    {

        $this->db->select('*')->from('marks')
            ->join('exam','exam.id = marks.exam_id')
            ->join('scs_lookup','scs_lookup.id = exam.scs_lookup_id')
            ->join('subject','subject.id = scs_lookup.subject_id')
            ->where('marks.student_id',$studentId)
            ->where('subject.id',$subjectId)
            ->order_by('exam.scs_lookup_id');
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    /*
    * to get all marks scored by student in all subject
    */
    public function getMarksScoredInAllSubject($studentId)
    {

        $this->db->select('*')->from('marks')
            ->join('exam','exam.id = marks.exam_id')
            ->join('scs_lookup','scs_lookup.id = exam.scs_lookup_id')
            ->join('subject','subject.id = scs_lookup.subject_id')
            ->where('marks.student_id',$studentId)
            ->order_by('exam.scs_lookup_id');
        $query = $this->db->get();
        return $this->getResultArray($query);
    }
//end

    public function  getMarksScoredByStudentInExamSeries($studentId,$examSeriesId)
    {
        $whereArray = array();

        if(is_numeric($examSeriesId))
        {
            $whereArray['exam_series.id'] = (int)$examSeriesId;
        }
        $whereArray['marks.student_id'] = (int)$studentId;

        $this->db->select('*')->from('marks')
                ->join('exam','marks.exam_id = exam.id')
                ->join('exam_series','exam.exam_series_id =exam_series.id ')
                ->join('scs_lookup','exam.scs_lookup_id =scs_lookup.id ')
                ->join('subject','scs_lookup.subject_id =subject.id ')
                ->where($whereArray);
        $query = $this->db->get();
        return $this->getResultArray($query);
    }
// End STUDENT related queries

// PARENT related queries

    public function getParentInfo($userId)
    {
        return $this->getUserTypeInfo('parent',$userId);
    }

    public function getStudentsForParent($parentId)
    {
        $this->db->select('user.id,user.username,student.fname')->from('parent_student_lookup')
                ->join('student','student.id = parent_student_lookup.student_id')
                ->join('user','student.user_id = user.id')
                ->where('parent_id = '.$parentId);
        $query = $this->db->get();
        return $this->getResultArray($query);
    }


    public function getTeacherClassAssociation($userId)
    {
        $this->db->select('teacher.id as teacherId,cs_lookup.id as cs_lookup_id, class.id as c_id,class.name as c_name,section.id as s_id,section.name as s_name,tcs_lookup.school_id as schoolId')->from('tcs_lookup')
            ->join('cs_lookup', 'cs_lookup.id = tcs_lookup.cs_lookup_id')
            ->join('teacher','teacher.id = tcs_lookup.teacher_id')
            ->join('class', 'cs_lookup.class_id = class.id')
            ->join('section', 'cs_lookup.section_id = section.id')
            ->where('teacher.user_id = '.$userId);
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getStudentsByClassSectionId($className,$sectionName)
    {
        $this->db->select('user.username,student.*,cs_lookup.id as cs_lookup_id')->from('css_lookup')
                    ->join('cs_lookup','cs_lookup.id = css_lookup.cs_lookup_id')
                    ->join('section','section.id = cs_lookup.section_id')
                    ->join('class','class.id = cs_lookup.class_id')
                    ->join('student','student.id = css_lookup.student_id')
                    ->join('user', 'student.user_id = user.id')
                    ->where(array('css_lookup.status'=>'1','section.name'=>$sectionName,'class.name' => $className))
                    ->order_by('student.roll_no');
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getClassAttendance($studentList,$teacherUserId,$date)
    {
        $whereCondition = array('teacher.user_id' => $teacherUserId,'scs_lookup.subject_id'=>$_SESSION['subjectId']);

        if( isset($date['endDate']) && !empty($date['endDate']) )
        {
            $whereCondition['DATE(attendance_master.date) <='] = $date['endDate'];

            if( isset($date['startDate']) && !empty($date['startDate']) )
            {
                $whereCondition['DATE(attendance_master.date) >='] = $date['startDate'];
            }
        }
        elseif( !empty($date['startDate']) && isset($date['startDate']) )
        {
            $whereCondition['DATE(attendance_master.date)'] = $date['startDate'];
        }

        $this->db->select('user.username,student.fname,student.lname,student.roll_no,student.id,attendance_data.status,attendance_data.reason,attendance_master.date')->from('attendance_master')
            ->join('teacher','teacher.id = attendance_master.teacher_id')
            ->join('attendance_data','attendance_master.id = attendance_data.attendance_master_id')
            ->join('student','student.id = attendance_data.student_id')
            ->join('user', 'student.user_id = user.id')
            ->join('scs_lookup','scs_lookup.id = attendance_master.scs_lookup_id')
            ->where_in('attendance_data.student_id',$studentList)
            ->where( $whereCondition )
            ->order_by('student.roll_no');
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getTeacherInfo($userId)
    {
        return $this->getUserTypeInfo('teacher',$userId);
    }

    public function getTeacherFromSubject($subjectId)
    {
        $this->db->select('teacher.*')->from('tcs_lookup')
            ->join('cs_lookup','cs_lookup.id = tcs_lookup.cs_lookup_id')
            ->join('teacher','teacher.id = tcs_lookup.teacher_id')
            ->join('sc_lookup','sc_lookup.school_id = teacher.school_id')
            ->join('scs_lookup','sc_lookup.id = scs_lookup.sc_lookup_id')
            ->where(array('scs_lookup.subject_id'=>$subjectId,'sc_lookup.class_id'=>$_SESSION['classId'],'sc_lookup.school_id'=>$_SESSION['schoolId'],'tcs_lookup.school_id'=>$_SESSION['schoolId'],'cs_lookup.class_id'=>$_SESSION['classId'],'cs_lookup.section_id'=>$_SESSION['sectionId']));
            return $this->db->get()->row_array();
    }

    public function getSubjectByTeacherId($teacherId)
    {
        $this->db->select('scs_lookup.subject_id')->from('scs_lookup,tcs_lookup')
            ->join('sc_lookup','scs_lookup.sc_lookup_id = sc_lookup.id')
            ->where(array('tcs_lookup.cs_lookup_id'=>$_SESSION['cs_lookup_id'],'tcs_lookup.teacher_id'=>$teacherId,'tcs_lookup.school_id'=>$_SESSION['schoolId']));
            return $this->db->get()->row_array();

    }

    public function getClassSectionLookupIdByName($className,$sectionName)
    {
        $this->db->select('cs_lookup.id')->from('cs_lookup')
            ->join('class','class.id = cs_lookup.class_id')
            ->join('section','section.id = cs_lookup.section_id')
            ->where(array('class.name'=>$className,'section.name'=>$sectionName));
        return $this->db->get()->row_array();
    }

    public function getClassSectionLookupIdById($classId,$sectionId)
    {
        $this->db->select('id')->from('cs_lookup')
            ->where(array('class_id'=>$classId,'section_id'=>$sectionId));
        return $this->db->get()->row_array();
    }

    public function getDiscussionListingForDiscussionGroup($discussionGroup)
    {
        $whereCondition = array('discussion_forum.creator_user_id'=>$_SESSION['loggedInUserId'],'discussion_forum_user_mapping.user_id'=>$_SESSION['loggedInUserId']);

        if($discussionGroup != 'all')
        {
           $this->db->join('discussion_forum_cs_lookup_mapping','discussion_forum_cs_lookup_mapping.discussion_forum_id = discussion_forum.id');
           $whereCondition['discussion_forum_cs_lookup_mapping.cs_lookup_id'] = $discussionGroup;
        }

        $this->db->select('discussion_forum.*, count(discussion_comment.id) as commnet_count')->from('discussion_forum')
            ->join('discussion_comment','discussion_forum.id = discussion_comment.discussion_forum_id','left')
            ->join('discussion_forum_user_mapping','discussion_forum.id = discussion_forum_user_mapping.discussion_forum_id')
            ->or_where($whereCondition)
            ->group_by('discussion_comment.discussion_forum_id')
            ->order_by('discussion_forum.update_date');
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getDiscussionData($discussionSeoTitle)
    {
        $this->db->select('discussion_forum.*,user.username')->from('discussion_forum')
            ->join('user','user.id = discussion_forum.creator_user_id')
            ->where(array('discussion_forum.seo_title'=>$discussionSeoTitle));
        return $this->db->get()->row_array();
    }

    public function getDiscussionCommentData($discussionTopicId)
    {
        $this->db->select('discussion_comment.*,user.username')->from('discussion_comment')
            ->join('user','user.id = discussion_comment.creator_user_id')
            ->where(array('discussion_comment.discussion_forum_id'=>$discussionTopicId));
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getDiscussionData1($discussionId)
    {

    }
}
