<?php

namespace MaBandit\Test;

class ExperimentTest extends \PHPUnit_Framework_TestCase
{
  
  public function testWithLeversReturnsNewExperimentWithSpecifiedLevers()
  {
    $levers = \MaBandit\Lever::createBatchFromValues(array('blue', 'green'));
    $experiment = \MaBandit\Experiment::withNameAndLevers('test', $levers);
    $taggedLevers = array();
    foreach($levers as $l)
    {
      $nl = clone($l);
      $nl->experiment = 'test';
      $taggedLevers[] = $nl;
    }
    $this->assertEquals($taggedLevers, $experiment->getLevers());
  }

  /**
   * @expectedException \MaBandit\Exception\BadArgumentException
   */
  public function testRaisesWhenNonLeverValuePassedToWithLevers()
  {
    $values = array('blue', 'green');
    \MaBandit\Experiment::withNameAndLevers('test', $values);
  }
}
