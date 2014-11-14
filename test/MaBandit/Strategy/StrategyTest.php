<?php

namespace MaBandit\Test\Strategy;
use prettyArray\PrettyArray;

class AlwaysExploreStrategy extends \MaBandit\Strategy\Strategy
{

  public function shouldExplore($levers)
  {
    return true;
  }
}

class NeverExploreStrategy extends \MaBandit\Strategy\Strategy
{

  public function shouldExplore($levers)
  {
    return false;
  }
}

class StrategyTest extends \PHPUnit_Framework_TestCase
{
  use \MaBandit\Test\TestUtil;

  public function testGetWinnerReturnsTheLeverWithHighestConversionRate()
  {
    $ex = $this->getTrafficExperiment();
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    for($i=0;$i<100;$i++)
      $this->assertEquals($l, $ex->bandit->getStrategy()
        ->getWinner($ex->ex->getLevers()));
  }

  public function testGetExplorationLeversReturnsAllButWinner()
  {
    $ex = $this->getTrafficExperiment();
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    $losers = (new PrettyArray($ex->ex->getLevers()))
      ->reject(function($k, $e) use($l) { return $l->getValue() == $e->getValue(); })
      ->sort_by(function($e) { return $e->getValue(); })
      ->to_a();
    for($i=0;$i<100;$i++)
    {
      $eLevers = (new PrettyArray($ex->bandit->getStrategy()
        ->getExplorationLevers($ex->ex->getLevers())))
          ->sort_by(function($e) { return $e->getValue(); })
          ->to_a();
      $this->assertEquals($losers, $eLevers);
    }
  }

  public function testGetTotalIterationsSumsDenominatorsOfAllLevers()
  {
    $ex = $this->getTrafficExperiment();
    for($i=0;$i<100;$i++)
      $ex->bandit->chooseLever($ex->ex);
    $this->assertEquals(100, $ex->bandit->getStrategy()
      ->getTotalIterations($ex->ex->getLevers()));
    $this->assertEquals(100, $ex->bandit->getStrategy()
      ->getTotalIterations(
        $ex->bandit->getExperiment('traffic')
          ->getLevers()));
  }

  public function testChooseExploratoryLeverRandomlySelectsNonWinner()
  {
    $ex = $this->getTrafficExperiment();
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    $expectedValues = (new PrettyArray($ex->values))
      ->reject(function($k, $v) use($l) { return $v == $l->getValue(); })
      ->sort()
      ->to_a();
    $seenValues = array();
    for($i=0;$i<100;$i++)
    {
      $el = $ex->bandit->getStrategy()
        ->chooseExploratoryLever($ex->ex->getLevers());
      $this->assertNotEquals($l, $el);
      $seenValues[] = $el->getValue();
    }
    $this->assertEquals($expectedValues, (new PrettyArray($seenValues))
      ->uniq()->sort()->to_a());
  }

  public function testChooseLeverReturnsWinnerWhenShouldNotExplore()
  {
    $ex = $this->getTrafficExperiment();
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    $ex->bandit->setStrategy(new NeverExploreStrategy);
    for($i=0;$i<100;$i++)
      $this->assertEquals($l, $ex->bandit->chooseLever($ex->ex));
  }

  public function testChooseLeverReturnsNonWinnerWhenShouldExplore()
  {
    $ex = $this->getTrafficExperiment();
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    $ex->bandit->setStrategy(new AlwaysExploreStrategy);
    for($i=0;$i<100;$i++)
      $this->assertNotEquals($l, $ex->bandit->chooseLever($ex->ex));
  }
}
