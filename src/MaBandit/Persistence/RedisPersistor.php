<?php

namespace MaBandit\Persistence;
use prettyArray\PrettyArray;

class RedisPersistor implements Persistor
{
  
  private static $_client;

  public static function getClient()
  {
    return self::$_client ?: self::$_client = new \Predis\Client();
  }

  public static function setClient(\Predis\Client $client)
  {
    self::$_client = $client;
  }
  
  public function saveLever(\MaBandit\Lever $lever)
  {
    RedisPersistor::getClient()->hset(
      $lever->experiment, $lever->getValue(), serialize($lever));
  }

  public function loadLever(\MaBandit\Persistence\PersistedLever $lever)
  {
    $stored = RedisPersistor::getClient()->hget(
      $lever->getExperiment(), $lever->getValue());
    if (!$stored)
      return null;
    return unserialize($stored);
  }

  public function loadLeversForExperiment(\MaBandit\Persistence\PersistedLever $lever)
  {
    $ex = new PrettyArray(RedisPersistor::getClient()
      ->hgetall($lever->getExperiment()));
    if ($ex->isEmpty())
      throw new \MaBandit\Exception\ExperimentNotFoundException();
    return $ex->map(function($k, $e) { return unserialize($e); })->to_a();
  }
}
