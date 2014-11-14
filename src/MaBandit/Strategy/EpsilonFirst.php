<?php

namespace MaBandit\Strategy;

class EpsilonFirst extends Strategy
{
  
  private $_exploreFor;

  private function __construct($exploreFor)
  {
    $this->_exploreFor = $exploreFor;
  }

  public static function withExploitationAfter($n)
  {
    if (!is_int($n) or $n < 1)
      throw new \MaBandit\Exception\InvalidExploitationLengthException();
    return new EpsilonFirst($n);
  }

  public function shouldExplore($levers)
  {
    return ($this->getTotalIterations($levers)) <= $this->_exploreFor;
  }

  public function getWinner($levers)
  {
    return ($this->shouldExplore($levers))
      ? null
      : parent::getWinner($levers);
  }
}
