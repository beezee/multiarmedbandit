<?php

namespace MaBandit\Persistence;

interface Persistor
{
  public function saveLever(\MaBandit\Persistence\PersistedLever $lever);
  public function loadLever(\MaBandit\Persistence\PersistedLever $lever);
  public function loadLeversForExperiment(\MaBandit\Persistence\PersistedLever $lever);
}
