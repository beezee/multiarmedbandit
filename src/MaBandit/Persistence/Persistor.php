<?php

namespace MaBandit\Persistence;

interface Persistor
{
  public function saveLever(\MaBandit\Persistance\PersistedLever $lever);
  public function loadLever(\MaBandit\Persistance\PersistedLever $lever);
  public function loadExperiment(\MaBandit\Persistance\PersistedLever $lever);
}
