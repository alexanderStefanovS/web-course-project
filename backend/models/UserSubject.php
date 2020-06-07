<?php

class UserSubject {

    public $id;
    public $course;
    public $specialty;
    public $subjectName;

    function __construct($id, $course, $specialty, $subjectName) {
        $this->id = $id;
        $this->course = $course;
        $this->specialty = $specialty;
        $this->subjectName = $subjectName;
    }

}

?>