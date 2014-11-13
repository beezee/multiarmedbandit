<?php

namespace MaBandit\Strategy;
use \prettyArray\PrettyArray;

// TODO - needs tests
class EpsilonGreedy implements Strategy
{
  
  private $_exploreEvery;

  private function __construct($exploreEvery)
  {
    $this->_exploreEvery = $exploreEvery;
  }

  public static function withExplorationEvery($exploreEvery)
  {
    if (!is_int($exploreEvery) or $exploreEvery < 1)
        throw new \MaBandit\Exception\InvalidExploitationLengthException();
    $i = new EpsilonGreedy($exploreEvery);
    return $i;
  }

  public function getWinner($levers)
  {
    return (new PrettyArray($levers))
      ->max_by(function($l) { return $l->getConversionRate(); });
  }

  // TODO - make selection non deterministic during a tie and test
  public function getExplorationLevers($levers)
  {
    $winner = $this->getWinner($levers);
    return (new PrettyArray($levers))
      ->reject(function($key, $l) use($winner) { return $winner == $l; })
      ->to_a();
  }

  public function getTotalIterations($levers)
  {
    return (new PrettyArray($levers))
      ->inject(function($k, &$l, &$m) { $m += $l->getDenominator(); });
  }

  public function shouldExplore($levers)
  {
    return (($this->getTotalIterations($levers) + 1) % $this->_exploreEvery) 
      == 0;
  }
  
  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $levers = $experiment->getLevers();
    if (!$this->shouldExplore($levers))
      return $this->getWinner($levers);
    $rest = $this->getExplorationLevers($levers);
    $k = array_rand($rest);
    return $rest[$k];
  }
}
