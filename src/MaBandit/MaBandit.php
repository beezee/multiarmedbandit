<?php

namespace MaBandit;

class MaBandit
{
  
  private $_strategy;


  private function __construct() {}

  public static function withStrategy($strategy)
  {
    $i = new MaBandit();
    $i->setStrategy($strategy);
    return $i;
  }

  public function setStrategy($strategy)
  {
    if (!is_a($strategy, '\MaBandit\Strategy\Strategy'))
      throw new \MaBandit\Exception\BadArgumentException();
    $this->_strategy = $strategy;
  }

  public function getStrategy()
  {
    return $this->_strategy;
  }
}
