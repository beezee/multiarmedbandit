<?php

namespace MaBandit\Test;

class PersistedLeverTest extends \PHPUnit_Framework_TestCase
{
  
  public function testAllowsInstantiationIfAllValuesAreValid()
  {
    $attrs = array('foo' => 'bar');
    $pl = new \MaBandit\Persistence\PersistedLever('value', 'test', 1, 5, $attrs);
    $this->assertEquals('value', $pl->getValue());
    $this->assertEquals(1, $pl->getNumerator());
    $this->assertEquals(5, $pl->getDenominator());
    $this->assertEquals('test', $pl->getExperiment());
    $this->assertEquals($attrs, $pl->getAttrs());
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadNumerator()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 'test', '1', 5);
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadDenominator()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 'test', 1, '5');
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnMoreThan100Percent()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 'test', 5, 1);
  }
  
  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadExperiment()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 234, 1, 5);
  }
}
