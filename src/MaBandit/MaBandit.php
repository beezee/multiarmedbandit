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

  public function createExperiment($name, $values)
  {
    $levers = \MaBandit\Lever::createBatchFromValues($values);
    $ex = \MaBandit\Experiment::withName($name)->forLevers($levers);
    foreach($ex->getLevers() as $lever)
      $this->getPersistor()->saveLever($lever);
    return $ex;
  }

  public function getExperiment($experiment)
  {
    $lever = new \MaBandit\Persistence\PersistedLever('x', $experiment);
    if (!$levers = $this->getPersistor()->loadLeversForExperiment($lever))
      throw new \MaBandit\Exception\ExperimentNotFoundException();
    return \MaBandit\Experiment::withName($experiment)->forLevers($levers);
  }

  public function getLeverByExperimentAndValue($experiment, $value)
  {
    $f = new \MaBandit\Persistence\PersistedLever($experiment, $value);
    return $this->getPersistor()->loadLever($f);
  }

  public function validateLever(\MaBandit\Lever $lever)
  {
    return $lever;
  }

  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $lever = $this->getStrategy()->chooseLever($experiment);
    $lever->incrementDenominator();
    return $this->validateLever($this->getPersistor()->saveLever($lever));
  }

  public function registerConversion(\MaBandit\Lever $lever)
  {
    $lever->incrementNumerator();
    return $this->validateLever($this->getPersistor()->saveLever($lever));
  }
}
