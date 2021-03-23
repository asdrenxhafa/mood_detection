<?php

namespace asdrenxhafa\mooddetection;

include __DIR__ . '/vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Other\Loggers\Screen;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Other\Tokenizers\NGram;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\ZScaleStandardizer;
use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\Layers\BatchNorm;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\Optimizers\AdaMax;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;

use function Rubix\ML\array_transpose;

ini_set('memory_limit', '-1');

class MoodDetection
{

    public function predict($message)
    {
        ini_set('memory_limit', '-1');

        $estimator = PersistentModel::load(new Filesystem('sentiment.model'));

        $prediction = $estimator->predictSample([$message]);

        return "The sentiment is: $prediction" . PHP_EOL;

    }

    function train()
    {
        ini_set('memory_limit', '-1');

        $logger = new Screen();

        $logger->info('Loading data into memory');

        $samples = $labels = [];

        $dir = __DIR__ . '\train\positive';
        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if ($file !== '.' && $file !== '..') {
                    $samples[] = [file_get_contents($dir . "\\" . $file)];
                    $labels[] = 'positive';
                }
            }
        }

        $dir = __DIR__ . '\train\negative';
        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if ($file !== '.' && $file !== '..') {
                    $samples[] = [file_get_contents($file)];
                    $labels[] = 'negative';
                }
            }
        }

//        foreach (['positive', 'negative'] as $label) {
//            foreach (glob("train/$label/*.txt") as $file) {
//                $samples[] = [file_get_contents($file)];
//                $labels[] = $label;
//            }
//        }

        $dataset = new Labeled($samples, $labels);

        $estimator = new PersistentModel(
            new Pipeline([
                new TextNormalizer(),
                new WordCountVectorizer(10000, 2, 10000, new NGram(1, 2)),
                new TfIdfTransformer(),
                new ZScaleStandardizer(),
            ], new MultilayerPerceptron([
                new Dense(100),
                new Activation(new LeakyReLU()),
                new Dense(100),
                new Activation(new LeakyReLU()),
                new Dense(100, 0.0, false),
                new BatchNorm(),
                new Activation(new LeakyReLU()),
                new Dense(50),
                new PReLU(),
                new Dense(50),
                new PReLU(),
            ], 256, new AdaMax(0.0001))),
            new Filesystem('sentiment.model', true)
        );

        $estimator->setLogger($logger);

        $estimator->train($dataset);

        $scores = $estimator->scores();
        $losses = $estimator->steps();

        Unlabeled::build(array_transpose([$scores, $losses]))
            ->toCSV(['scores', 'losses'])
            ->write('progress.csv');

        $logger->info('Progress saved to progress.csv');

        $estimator->save();

    }

    function validate()
    {
        ini_set('memory_limit', '-1');

        $logger = new Screen();

        $logger->info('Loading data into memory');

        $samples = $labels = [];

        foreach (['positive', 'negative'] as $label) {
            foreach (glob("test/$label/*.txt") as $file) {
                $samples[] = [file_get_contents($file)];
                $labels[] = $label;
            }
        }

        $dataset = Labeled::build($samples, $labels)->randomize()->take(10000);

        $estimator = PersistentModel::load(new Filesystem('sentiment.model'));

        $logger->info('Making predictions');

        $predictions = $estimator->predict($dataset);

        $report = new AggregateReport([
            new MulticlassBreakdown(),
            new ConfusionMatrix(),
        ]);

        $results = $report->generate($predictions, $dataset->labels());

        echo $results;

        $results->toJSON()->write('report.json');

        $logger->info('Report saved to report.json');

    }
}
