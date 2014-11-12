<?php

namespace MaBandit;

class Experiment
{

  private function __construct() {}
  private $_levers;

  public static function withNameAndLevers($name, $levers)
  {
    $i = new Experiment();
    $i->name = $name;
    $i->setLevers($levers);
    return $i;
  }

  public function getLevers()
  {
    return $this->_levers;
  }

  public function addLever($lever)
  {
    if (!(is_object($lever) and get_class($lever) === 'MaBandit\Lever'))
      throw new \MaBandit\Exception\BadArgumentException();
    $lever->experiment = $this->name;
    $this->_levers[] = $lever;
  }

  public function setLevers($levers)
  {
    $this->_levers = array();
    foreach($levers as $lever)
      $this->addLever($lever);
  }
}
