<?php

namespace MaBandit\Test;
use prettyArray\PrettyArray;

class EpsilonFirstTest extends \PHPUnit_Framework_TestCase
{
  use \MaBandit\Test\TestUtil;

  /**
   * @expectedException \MaBandit\Exception\InvalidExploitationLengthException
   */
  public function testWithExploitationAfterRaisesOnNonIntArgument()
  {
    \MaBandit\Strategy\EpsilonFirst::withExploitationAfter('foo');
  }

  /**
   * @expectedException \MaBandit\Exception\InvalidExploitationLengthException
   */
  public function testWithExploitationAfterRaisesOnArgumentLessThan1()
  {
    \MaBandit\Strategy\EpsilonFirst::withExploitationAfter(0);
  }

  public function testWinnerReturnsNullUntilExploitationIsReached()
  {
    $ex = $this->getTrafficExperiment();
    $ex->bandit
      ->setStrategy(\MaBandit\Strategy\EpsilonFirst::withExploitationAfter(30));
    for($i=0;$i<30;$i++)
    {
      $ex->bandit->chooseLever($ex->ex); //increment iterations
      $this->assertEquals(null, 
        $ex->bandit->getStrategy()->getWinner($ex->ex->getLevers()));
    }
    for($i=0;$i<100;$i++)
    {
      $ex->bandit->chooseLever($ex->ex); //increment iterations
      $this->assertInstanceOf('MaBandit\Lever', 
        $ex->bandit->getStrategy()->getWinner($ex->ex->getLevers()));
    }
  }

  public function testWinnerIsIncludedDuringExplorationPhase()
  {
    $ex = $this->getTrafficExperiment();
    $l = (new PrettyArray($ex->ex->getLevers()))->offsetGet(0);
    $l->incrementDenominator();
    $l->incrementNumerator();
    $ex->bandit->getPersistor()->saveLever($l);
    $ex->bandit
      ->setStrategy(\MaBandit\Strategy\EpsilonFirst::withExploitationAfter(30));
    $bag = array();
    for($i=0;$i<20;$i++)
    {
      $v = $ex->bandit->chooseLever($ex->ex)->getValue();
      if (!$bag[$v]) $bag[$v] = 0;
      $bag[$v]++;
    }
    $this->assertTrue($bag[$l->getValue()] > 0);
  }

  public function testWinnerIsAlwaysReturnedAfterExplorationPhase()
  {
    $ex = $this->getTrafficExperiment();
    $l = (new PrettyArray($ex->ex->getLevers()))->offsetGet(0);
    $l->incrementDenominator();
    $l->incrementNumerator();
    $ex->bandit->getPersistor()->saveLever($l);
    $ex->bandit
      ->setStrategy(\MaBandit\Strategy\EpsilonFirst::withExploitationAfter(30));
    for($i=0;$i<30;$i++) $ex->bandit->chooseLever($ex->ex);
    for($i=0;$i<100;$i++)
      $this->assertEquals($l, $ex->bandit->chooseLever($ex->ex));
  }
}
