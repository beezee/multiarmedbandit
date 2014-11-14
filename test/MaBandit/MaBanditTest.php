<?php

namespace MaBandit\Test;

class MaBanditTest extends \PHPUnit_Framework_TestCase
{
  use \MaBandit\Test\TestUtil;

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

  public function testRegisterConversionAddsToLeverNumeratorAndPersists()
  {
    $l = \MaBandit\Lever::forValue('test');   
    $l->incrementDenominator();
    $this->assertEquals(0, $l->getNumerator());
    $bandit = $this->getBandit();
    $bandit->registerConversion($l);
    $this->assertEquals(1, $l->getNumerator());
    $f = new \MaBandit\Persistence\PersistedLever('test', 0, 0, '');
    $this->assertEquals($l, $bandit->getPersistor()->loadLever($f));
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testRegisterConversionRaisesWhenPassedNonLeverValue()
  {
    $this->getBandit()->registerConversion('foo');
  }

  public function testCreateExperimentPersistsAndReturnsNewExperiment()
  {
    $bandit = $this->getBandit();
    $values = array('one', 'two', 'three');
    $ex = $bandit->createExperiment('test', $values);
    foreach($ex->getLevers() as $i => $l)
    {
      $this->assertEquals($values[$i], $l->getValue());
      $f = new \MaBandit\Persistence\PersistedLever(
        $l->getValue(), 0, 0, $ex->name);
      $this->assertEquals($l, $bandit->getPersistor()->loadLever($f));
    }
  }

  public function testChooseLeverAddsToDenominatorPersistsAndReturnsLever()
  {
    $bandit = $this->getBandit();
    $levers = \MaBandit\Lever::createBatchFromValues(array('yes', 'no'));
    $ex = \MaBandit\Experiment::withName('testchoosepersist')
      ->forLevers($levers);
    $chosen = $bandit->chooseLever($ex);
    $this->assertEquals(1, $chosen->getDenominator());
    $f = new \MaBandit\Persistence\PersistedLever(
      $chosen->getValue(), 0, 0, $ex->name);
    $this->assertEquals($chosen, $bandit->getPersistor()->loadLever($f));
  }
}
