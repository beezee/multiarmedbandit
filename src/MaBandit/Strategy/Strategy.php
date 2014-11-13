<?php

namespace MaBandit\Strategy;

interface Strategy
{
  public function chooseLever(\MaBandit\Experiment $experiment);
}
