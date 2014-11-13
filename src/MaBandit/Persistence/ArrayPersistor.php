<?php

namespace MaBandit\Persistence;

class ArrayPersistor implements Persistor
{

  private $_levers = array();
  
  public function saveLever(\MaBandit\Persistance\PersistedLever $lever)
  {
    if (!is_array($this->_levers[$lever->experiment]))
      $this->_levers[$lever->experiment] = array();
    $this->_levers[$lever->experiment][$lever->value] = $lever;
  }

  public function loadLever(\MaBandit\Persistance\PersistedLever $lever)
  {
    if (!is_array($this->_levers[$lever->experiment]))
      return null;
    return $this->_levers[$lever->experiment][$lever->value];
  }

  public function loadExperiment(\MaBandit\Persistance\PersistedLever $lever)
  {
    return $this->_levers[$lever->experiment] ?: array();
  }
}
