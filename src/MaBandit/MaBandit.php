<?php

namespace MaBandit;

class MaBandit
{
  
  private $_strategy;
  private $_persistor;

  private function __construct() {}

  public static function withStrategy($strategy)
  {
    $i = new MaBandit();
    $i->setStrategy($strategy);
    return $i;
  }

  public function setStrategy(\MaBandit\Strategy\Strategy $strategy)
  {
    $this->_strategy = $strategy;
  }

  public function getStrategy()
  {
    return $this->_strategy;
  }

  public function withPersistor(\MaBandit\Persistence\Persistor $persistor)
  {
    $this->_persistor = $persistor;
    return $this;
  }

  public function getPersistor()
  {
    return $this->_persistor;
  }
}
