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
}  
