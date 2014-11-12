<?php

namespace Mabandit\Test;

class MabanditTest extends \PHPUnit_Framework_TestCase
{

  public function testWithStrategyAssignsValidStrategy()
  {
    $strategy = new \MaBandit\Strategy\EpsilonGreedy();
    $bandit = \MaBandit\MaBandit::withStrategy($strategy);
    $this->assertEquals($strategy, $bandit->getStrategy());
  }

  /**
   * @expectedException \MaBandit\Exception\InvalidStrategyException
   */
  public function testSetStrategyRaisesOnInvalidStrategy()
  {
    $strategy = new \stdClass();
    $bandit = \MaBandit\MaBandit::withStrategy($strategy);
  }
}
