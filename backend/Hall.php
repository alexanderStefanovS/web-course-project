<?php

class Hall {

  public $id;
  public $number;
  public $floor;
  public $type;

  function __construct($id, $number, $floor, $type) {
    $this->id = $id;
    $this->number = $number;
    $this->floor = $floor;
    $this->type = $type;
  }

}

?>