<?php

namespace MaBandit\Persistence;

/* 
 * This class exists to provide type safety around updating values on a Lever
 * Currently the only persistors defined are storing Levers serialized or intact
 * however if you were to store them say in a relational database, retrieval 
 * would require update a new instace of a Lever with values taken from
 * the database.
 *
 * Since we typically do not want to directly assign numerator, denominator
 * value, or experiment to an existing Lever, this can only be done by passing
 * a PersistedLever with the desired values to the inflate method on a Lever
 * instance
 *
 * As long as this class has to exist, it is also serving as a "query builder"
 * for looking up existing Levers. At least this way it gets some use until a
 * future Persistor needs to take advantage of Lever->inflate()
 */
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
