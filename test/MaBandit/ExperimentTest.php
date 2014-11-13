<?php

namespace MaBandit\Test;

class ExperimentTest extends \PHPUnit_Framework_TestCase
{

  public function testWithNameReturnsNewExperimentWithNameSet()
  {
    $experiment = \MaBandit\Experiment::withName('test');
    $this->assertEquals('test', $experiment->name);
  }
  
  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesWhenNonLeverValuePassedToForLevers()
  {
    $values = array('blue', 'green');
    \MaBandit\Experiment::withName('test')->forLevers($values);
  }

  public function testForLeversReturnsExperimentWithSpecifiedLeversSet()
  {
    $levers = \MaBandit\Lever::createBatchFromValues(array('blue', 'green'));
    $experiment = \MaBandit\Experiment::withName('test')->forLevers($levers);
    $taggedLevers = array();
    foreach($levers as $l)
    {
      $nl = clone($l);
      $nl->experiment = 'test';
      $taggedLevers[] = $nl;
    }
    $this->assertEquals($taggedLevers, $experiment->getLevers());
    $this->assertEquals('test', $experiment->name);
  }
}
