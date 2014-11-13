<?php

namespace MaBandit;

class Lever
{
  
  private $_value;
  private $_numerator = 0;
  private $_denominator = 0;
  private $_attrs = array();
  public $experiment;

  private function __construct($value)
  {
    $this->_value = $value;
  }

  public static function forValue($value)
  {
    if (!is_string($value))
      throw new \MaBandit\Exception\BadArgumentException();
    return new Lever($value);
  }

  // TODO - needs test
  public static function forPersistedLever(
    \Mabandit\Persistence\PersistedLever $lever)
  {
    $l = new Lever('x');
    $l->inflate($lever);
    return $l;
  }

  public static function createBatchFromValues($values)
  {
    $batch = array();
    foreach($values as $val) $batch[] = Lever::forValue($val);
    return $batch;
  }

  public function getValue()
  {
    return $this->_value;
  }

  public function getNumerator()
  {
    return $this->_numerator;
  }

  public function getDenominator()
  {
    return $this->_denominator;
  }

  public function getConversionRate()
  {
    if (!($this->getDenominator() > 0))
      return 0;
    return $this->getNumerator() / $this->getDenominator();
  }

  public function getAttrs()
  {
    return $this->_attrs;
  } 

  public function incrementDenominator()
  {
    $this->_denominator++;
  }

  public function incrementNumerator()
  {
    if ($this->_numerator >= $this->_denominator)
      throw new \MaBandit\Exception\LeverNumeratorTooHighException();
    $this->_numerator++;
  }

  public function inflate(\MaBandit\Persistence\PersistedLever $settings)
  {
    $this->_value = $settings->getValue();
    $this->_numerator = $settings->getNumerator();
    $this->_denominator = $settings->getDenominator();
    $this->experiment = $settings->getExperiment();
    $this->_attrs = $settings->getAttrs();
    return $this;
  }
}
