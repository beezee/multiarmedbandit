<?php

namespace MaBandit\Persistence;

class ArrayPersistor implements Persistor
{

  private $_levers = array();
  
  public function saveLever(\MaBandit\Persistence\PersistedLever $lever)
  {
    if (!is_array($this->_levers[$lever->experiment]))
      $this->_levers[$lever->experiment] = array();
    $this->_levers[$lever->experiment][$lever->getValue()] = $lever;
  }

  public function loadLever(\MaBandit\Persistence\PersistedLever $lever)
  {
    if (!is_array($this->_levers[$lever->experiment]))
      return null;
    return $this->_levers[$lever->experiment][$lever->getValue()];
  }

  public function loadLeversForExperiment(\MaBandit\Persistence\PersistedLever $lever)
  {
    return $this->_levers[$lever->experiment] ?: array();
  }
}
