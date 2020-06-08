<?php

class Schedule {

    public $id;
    public $date;
    public $hour;
    public $hallNumber;
    public $subjectName;
    public $teacherFirstname;
    public $teacherLastname;
    public $course;
    public $specialty;

    function __construct($id, $date, $hour, $hallNumber, $subjectName, $teacherFirstname, $teacherLastname,
    $course, $specialty) {
        $this->id = $id;
        $this->date = $date;
        $this->hour = $hour;
        $this->hallNumber = $hallNumber;
        $this->subjectName = $subjectName;
        $this->teacherFirstname = $teacherFirstname;
        $this->teacherLastname = $teacherLastname;
        $this->course = $course;
        $this->specialty = $specialty;
    }

}

?>