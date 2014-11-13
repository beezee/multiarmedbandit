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

  public function getExperiment($experiment)
  {
    $lever = new \MaBandit\Persistence\PersistedLever('x', 0, 0, $experiment);
    if (!$levers = $this->getPersistor()->loadLeversForExperiment($lever))
      throw new \MaBandit\Exception\ExperimentNotFoundException();
    return \MaBandit\Experiment::withName($experiment)->forLevers($levers);
  }

  public function validateLever(\MaBandit\Lever $lever)
  {
    return $lever;
  }

  // TODO - needs test
  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $lever = $this->getStrategy()->chooseLever($experiment);
    $lever->incrementDenominator();
    return $this->validateLever($this->getPersistor()->saveLever($lever));
  }

  // TODO - needs test
  public function registerConversion(\MaBandit\Lever $lever)
  {
    $lever->incrementNumerator();
    return $this->validateLever($this->getPersistor()->saveLever($lever));
  }
}
