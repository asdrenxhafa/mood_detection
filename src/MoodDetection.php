<?php

include __DIR__ . '/vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

class MoodDetection
{
    public function predict($message){
        ini_set('memory_limit', '-1');

        $estimator = PersistentModel::load(new Filesystem('sentiment.model'));

        $prediction = $estimator->predictSample([$message]);

        echo "The sentiment is: $prediction" . PHP_EOL;
    }
}
