<?php

namespace MaBandit\Test;

class PersistedLeverTest extends \PHPUnit_Framework_TestCase
{
  
  public function testAllowsInstantiationIfAllValuesAreValid()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 1, 5, 'test');
    $this->assertEquals('value', $pl->getValue());
    $this->assertEquals(1, $pl->getNumerator());
    $this->assertEquals(5, $pl->getDenominator());
    $this->assertEquals('test', $pl->getExperiment());
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadNumerator()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', '1', 5, 'test');
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadDenominator()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 1, '5', 'test');
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnMorThan100Percent()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 5, 1, 'test');
  }
  
  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesOnBadExperiment()
  {
    $pl = new \MaBandit\Persistence\PersistedLever('value', 1, 5, 234);
  }
}
