#Multi-Armed Bandit

This library provides a simple interface for using the 
[Multi-Armed Bandit](http://en.wikipedia.org/wiki/Multi-armed_bandit) 
algorithm to dynamically test and optimize between a set of possible options.

###Installation

Install composer

```bash
curl -s https://getcomposer.org/installer | php
```

Add a composer.json to your project root

```json
{
  "require": {
    "brianzeligson/mabandit": "dev-master"
  }
}
```

Install using composer

```bash
php composer.phar install
```

Add this line to your main application file:

```php
require 'vendor/autoload.php';
```

###Basic Use

All interaction is done through an instance of the MaBandit\MaBandit class.
In order to create an instance, you need to provide a 
[Strategy](http://en.wikipedia.org/wiki/Multi-armed_bandit#Bandit_strategies)
and a Persistor.

Included are EpsilonGreedy or EpsilonFirst strategies, and ArrayPersistor or
RedisPersistor for persistence. Note that persistence is required for the
algorithm to work, and ArrayPersistor is a simple in-memory array, suitable
only for long-running single process tasks, or testing. For anything requiring
maintenance of state, RedisPersistor is the way to go.

Create a bandit as follows:

```php
$strategy = \MaBandit\EpsilonGreedy::withExplorationEvery(3) //experiment every 3rd time
// OR
$strategy = \MaBandit\EpsilonFirst::withExploitationAfter(100) //only experiment til 100
$persistor = new \MaBandit\Persistence\RedisPersistor();
$bandit = \MaBandit\MaBandit::withStrategy($strategy)->withPersistor($persistor);
```

Now you will need an experiment to work with. You can create one as follows:

```php
$experiment = $bandit->createExperiment('cta-size', array('sm', 'med', 'lg'));
```

To load a persisted experiment, use getExperiment. This method throws an
ExperimentNotFoundException if your experiment is not found, so it's best to 
handle this. The following example checks for a persisted experiment and initializes
if it is not found:

```php
try {
  $ex = $bandit->getExperiment('cta-size')
} catch(\MaBandit\Exception\ExperimentNotFoundException $e) {
  $ex = $bandit->createExperiment('cta-size', array('sm', 'med', 'lg'));
}
```

With your experiment you can now let the bandit do it's job. To ask the bandit
for the next value for use in your experiment, call chooseLever as follows:

```php
$nextValue = $bandit->chooseLever($experiment)->getValue();
```

Note that this method mutates the "lever" (representation of a value in an
experiment) to keep track of the number of times it has been tested, so it
is important to call it only when you are going to use the value.

In order for the algorithm to successfully determine the winning choice, you
need to inform it of a conversion. When a value converts, use it to fetch
the corresponding lever and pass that to registerConversion, as follows:

```php
$lever = $bandit->getLeverByExperimentAndValue('cta-size', $convertedValue);
$bandit->registerConversion($lever);
```

A small sample app demonstrating use is available 
[here](https://github.com/beezee/mabanditdemo/blob/master/index.php)
