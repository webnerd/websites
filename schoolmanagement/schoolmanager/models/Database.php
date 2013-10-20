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

    public function getResultArray($query){
        $result = array();
        foreach($query->result_array() as $row){
            $result[] = $row;
        }
        return $result;
    }
// Start USER related queries
    public function getUserInfo($user_info){
        $this->db->select('id,encoded_id,role_id,username')
                 ->where(array('email_id' => $user_info['email'] , 'password'=>md5($user_info['pass'])));
        return $this->db->get('user')->row_array();
    }

    public function getUserTypeInfo($userType,$userId)
    {
        return $this->db->get_where($userType,array('user_id'=>$userId))->row_array();
    }
// END USER related queries

// Start STUDENT related queries

    public function getStudentInfo($userId){
        return $this->getUserTypeInfo('student',$userId);
    }

    public function getClassAndSectionOfStudent($studentId){
        $this->db->select('section.name as section , class.name as class, class.id as classId')
                        ->from('cs_lookup')
                        ->join('class', 'cs_lookup.class_id = class.id')
                        ->join('section', 'cs_lookup.section_id = section.id')
                        ->where('cs_lookup.student_id',$studentId);
        return $this->db->get()->row_array();
    }

    /*
     * To get al valid exams applicable for a student
     */
    public function getExamsForStudent($schoolId,$classId){
        $this->db->select('exam.*')->from('exam')
                 ->join('exam_series','exam.exam_series_id = exam_series.id')
                 ->join('sc_lookup', 'exam_series.sc_lookup_id = sc_lookup.id')
                 ->where(array('sc_lookup.school_id' => $schoolId,'sc_lookup.class_id' => $classId));
        $query = $this->db->get();
        return $this->getResultArray($query);
    }

    public function getExamSeriesForeStudent($schoolId,$classId){
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
        $this->db->select('*')->from('parent_student_lookup')
                ->join('student','student.id = parent_student_lookup.student_id')
                ->where('parent_id = '.$parentId);
        $query = $this->db->get();
        return $this->getResultArray($query);
    }


}
