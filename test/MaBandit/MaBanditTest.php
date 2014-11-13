<?php

namespace MaBandit\Test;

class MaBanditTest extends \PHPUnit_Framework_TestCase
{

  public function getBandit()
  {
    $strategy = \MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(10);
    $persistor = new \MaBandit\Persistence\ArrayPersistor();
    return \MaBandit\MaBandit::withStrategy($strategy)
      ->withPersistor($persistor);
   }
    
  public function testWithStrategyAssignsValidStrategy()
  {
    $strategy = \MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(10);
    $bandit = \MaBandit\MaBandit::withStrategy($strategy);
    $this->assertEquals($strategy, $bandit->getStrategy());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testWithStrategyRaisesOnInvalidStrategy()
  {
    $strategy = new \stdClass();
    $bandit = \MaBandit\MaBandit::withStrategy($strategy);
  }

  public function testWithPersistorAssignsValidPersistor()
  {
    $s = \MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(10);
    $p = new \MaBandit\Persistence\ArrayPersistor();
    $bandit = \MaBandit\MaBandit::withStrategy($s)->withPersistor($p);
    $this->assertEquals($p, $bandit->getPersistor());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testWithPersistorRaisesOnInvalidStrategy()
  {
    $s = \MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(10);
    $p = new \stdClass();
    $bandit = \MaBandit\MaBandit::withStrategy($s)->withPersistor($p);
  }

  public function testGetExperimentLoadsPersistedExperimentAndReturns()
  {
    $levers = \MaBandit\Lever::createBatchFromValues(array('blue', 'green'));
    $ex = \Mabandit\Experiment::withName('testGetExperiment')
      ->forLevers($levers);
    $bandit = $this->getBandit();
    foreach($levers as $l) $bandit->getPersistor()->saveLever($l);
    $this->assertEquals($bandit->getExperiment('testGetExperiment')->getLevers(),
      $ex->getLevers());
  }

  /**
   * @expectedException \MaBandit\Exception\ExperimentNotFoundException
   */
  public function testGetExperimentRaisesWhenExperimentNotFound()
  {
    $this->getBandit()->getExperiment('fake'); 
  }
}
