<?php

namespace MaBandit\Strategy;
use \prettyArray\PrettyArray;

abstract class Strategy
{

  abstract public function shouldExplore($levers);

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
  
  public function chooseExploratoryLever($levers)
  {
    $exploratoryLevers = $this->getExplorationLevers($levers);
    $k = array_rand($exploratoryLevers);
    return $exploratoryLevers[$k];
  }

  public function chooseLever(\MaBandit\Experiment $experiment)
  {
    $levers = $experiment->getLevers();
    return ($this->shouldExplore($levers))
      ? $this->chooseExploratoryLever($levers)
      : $this->getWinner($levers);
  }
}
