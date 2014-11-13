<?php

namespace MaBandit\Test;

class MaBanditTest extends \PHPUnit_Framework_TestCase
{

  public function testWithStrategyAssignsValidStrategy()
  {
    $strategy = \MaBandit\Strategy\EpsilonGreedy::withPercentExploration(10);
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
    $s = \MaBandit\Strategy\EpsilonGreedy::withPercentExploration(10);
    $p = new \MaBandit\Persistence\ArrayPersistor();
    $bandit = \MaBandit\MaBandit::withStrategy($s)->withPersistor($p);
    $this->assertEquals($p, $bandit->getPersistor());
  }

  /**
   * @expectedException \PHPUnit_Framework_Error
   */
  public function testWithPersistorRaisesOnInvalidStrategy()
  {
    $s = \MaBandit\Strategy\EpsilonGreedy::withPercentExploration(10);
    $p = new \stdClass();
    $bandit = \MaBandit\MaBandit::withStrategy($s)->withPersistor($p);
  }
}
