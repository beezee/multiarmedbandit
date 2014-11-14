<?php

namespace MaBandit\Persistence;

class PersistedLever
{

  private $_attrs = array();
  private $_allowedAttrs = array('numerator', 'denominator', 
                'experiment', 'value', 'attrs');

  public function __construct($value, $experiment, $numerator=0,
    $denominator=0, $attrs=array())
  {
    if (!is_int($numerator) or !is_int($denominator)
      or !is_string($experiment) or !($value)
        or ($numerator > $denominator))
      throw new \MaBandit\Exception\BadArgumentException();
    $this->_attrs['value'] = $value;
    $this->_attrs['numerator'] = $numerator;
    $this->_attrs['denominator'] = $denominator;
    $this->_attrs['experiment'] = $experiment;
    $this->_attrs['attrs'] = $attrs;
  }

  public function __call($name, $args)
  {
    if (!preg_match('/^get/', $name))
      throw new \Exception('Method does not exist '.$name);
    $key = strtolower(preg_replace('/^get/', '', $name));
    if (!in_array($key, $this->_allowedAttrs))
      throw new \Exception('Method does not exist '.$name);
    return $this->_attrs[$key];
  }

}
