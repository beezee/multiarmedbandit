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

  // TODO - needs test
  public function getExperiment($experiment)
  {
    $lever = \MaBandit\Persistence\PersistedLever('x', 0, 0, $experiment);
    if (!$persistedLevers = $this->_persistor->loadLeversForExperiment($lever))
      throw new \MaBandit\Exception\ExperimentNotFoundException();
    $levers = __::chain($persistedLevers)
      ->map(function($l) { return $l->getLever(); })
      ->value();
    return \MaBandit\Experiment::withNameAndLevers($experiment, $levers);
  }

  // TODO - needs test
  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $lever = $this->_strategy->chooseLever($experiment)->getLever();
    $lever->incrementDenominator();
    $this->_persistor->saveLever($lever->getPersistedLever());
    return $lever;
  }

  // TODO - needs test
  public function registerConversion(\MaBandit\Lever $lever)
  {
    $lever->incrementNumerator();
    $this->_persistor->saveLever($lever->getPersistedLever());
  }
}
