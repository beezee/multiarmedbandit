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
}
