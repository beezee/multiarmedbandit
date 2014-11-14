<?php

namespace MaBandit\Test\Persistence;

class ArrayPersistorTest extends \PHPUnit_Framework_TestCase
{

  public function testSavesAndLoadsLevers()
  {
    $p = new \MaBandit\Persistence\ArrayPersistor();
    $l = \MaBandit\Lever::forValue('x')->inflate(
      new \MaBandit\Persistence\PersistedLever('foo', 1, 2, 'x'));
    $p->saveLever($l);
    $f = new \MaBandit\Persistence\PersistedLever('foo', 0, 0, 'x');
    $this->assertNotEquals($l, $f);
    $this->assertEquals($l, $p->loadLever($f));
  }

  public function testLoadsLeversForExperiment()
  {
    $p = new \MaBandit\Persistence\ArrayPersistor();
    $l = \MaBandit\Lever::forValue('x')->inflate(
      new \MaBandit\Persistence\PersistedLever('foo', 1, 2, 'y'));
    $l1 = \MaBandit\Lever::forValue('x')->inflate(
      new \MaBandit\Persistence\PersistedLever('bar', 3, 4, 'y'));
    $p->saveLever($l);
    $p->saveLever($l1);
    $f = new \MaBandit\Persistence\PersistedLever('we', 0, 0, 'y');
    $actual = $p->loadLeversForExperiment($f);
    $expected = array('foo' => $l, 'bar' => $l1);
    $this->assertEquals($expected, $actual);
  }
}
