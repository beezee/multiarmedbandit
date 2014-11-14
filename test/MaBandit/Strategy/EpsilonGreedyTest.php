<?php

namespace MaBandit\Test;

class EpsilonGreedyTest extends \PHPUnit_Framework_TestCase
{
  use \MaBandit\Test\TestUtil;
  
  public function testShouldExploreIsTrueEvery3rdIterationWithExplorationEvery3()
  {
    $ex = $this->getTrafficExperiment();
    $ex->bandit
      ->setStrategy(\MaBandit\Strategy\EpsilonGreedy::withExplorationEvery(3));
    $l = $ex->bandit->chooseLever($ex->ex);
    $ex->bandit->registerConversion($l);
    $this->assertEquals($l, $ex->bandit->chooseLever($ex->ex));
    $this->assertNotEquals($l, $ex->bandit->chooseLever($ex->ex));
    for($i=1;$i<100;$i++)
      if ($i % 3 === 0)
        $this->assertNotEquals($l, $ex->bandit->chooseLever($ex->ex));
      else
        $this->assertEquals($l, $ex->bandit->chooseLever($ex->ex));
  }
}
