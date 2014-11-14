<?php

namespace MaBandit\Test;

trait TestUtil
{

  public function getBandit()
  {
    $strategy = \MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(10);
    $persistor = new \MaBandit\Persistence\ArrayPersistor();
    return \MaBandit\MaBandit::withStrategy($strategy)
      ->withPersistor($persistor);
   }

  public function getTrafficExperiment()
  {
    $r = new \stdClass();
    $r->bandit = $this->getBandit();
    $r->values = array('red', 'green', 'yellow');
    $r->ex = $r->bandit->createExperiment('traffic', $r->values);
    return $r;
  }

}  
