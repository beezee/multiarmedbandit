<?php

namespace MaBandit\Test;

class LeverTest extends \PHPUnit_Framework_TestCase
{
  
  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testCreateBatchForValuesRaisesWhenPassedNonStringValue()
  {
    $values = array('foo', 2);
    \MaBandit\Lever::createBatchFromValues($values);
  }

  public function testCreateBatchForValuesTakesStringOfValuesAndReturnsLevers()
  {
    $values = array('blue', 'green');
    $levers = \MaBandit\Lever::createBatchFromValues($values);
    $this->assertTrue(is_array($levers));
    $this->assertEquals(2, count($levers));
    foreach($levers as $l)
    {
      $this->assertEquals('MaBandit\Lever', get_class($l));
      $this->assertTrue(in_array($l->getValue(), $values));
      $this->assertEquals(0, $l->getDenominator());
      $this->assertEquals(0, $l->getNumerator());
      $this->assertEquals(0, $l->getConversionRate());
    }
    $this->assertNotEquals($levers[0]->getValue(), $levers[1]->getValue());
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testInflateRaisesOnBadArgument()
  {
    $l = \MaBandit\Lever::forValue('value');
    $l->inflate('foo');
  }

  public function testInflateAssignsValuesFromPersistedLever()
  {
    $l = \MaBandit\Lever::forValue('value');
    $pl = new \MaBandit\Persistence\PersistedLever( 'val2', 3, 4, 'ex');
    $l->inflate($pl);
    $this->assertEquals('val2', $l->getValue());
    $this->assertEquals(3, $l->getNumerator());
    $this->assertEquals(4, $l->getDenominator());
    $this->assertEquals(.75, $l->getConversionRate());
    $this->assertEquals('ex', $l->experiment);
  }

  public function testIncrementDenominator()
  {
    $l = \MaBandit\Lever::forValue('foo');
    $this->assertEquals(0, $l->getDenominator());
    $l->incrementDenominator();
    $this->assertEquals(1, $l->getDenominator());
  } 

  /**
   * @expectedException \MaBandit\Exception\LeverNumeratorTooHighException
   */
  public function testIncrementNumeratorRaisesIf100PercentConversionRate()
  {
    $l = \MaBandit\Lever::forValue('foo');
    $l->incrementNumerator();
  }
}
