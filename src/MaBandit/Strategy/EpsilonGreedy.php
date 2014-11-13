<?php

namespace MaBandit\Strategy;

// TODO - needs tests
class EpsilonGreedy implements Strategy
{
  
  private $_percentExploration;

  private function __construct($percentExploration)
  {
    $this->_percentExploration = $percentExploration;
  }

  public static function withPercentExploration($percentExploration)
  {
    if (!is_int($percentExploration) or $percentExploration > 99
      or $percentExploration < 1)
        throw new \MaBandit\Exception\InvalidPercentExplorationException();
    $i = new EpsilonGreedy($percentExploration);
    return $i;
  }

  public function getWinner($levers)
  {
    return __::chain($levers)
      ->sortBy(function($l) { return $l->getConversionRate() * -1; })
      ->first()->value();
  }

  public function getExplorationLevers($levers)
  {
    return __::chain($levers)
      ->reject(function($l) use($winner) { return $winner == $l; })
      ->value();
  }
  
  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $levers = $experiment->getLevers();
    if (rand(1, 100) > $this->_percentExploration)
      return $this->getWinner($levers)
    $rest = $this->getExplorationLevers($levers);
    $k = array_rand($rest);
    return $rest[$k];
  }
}
