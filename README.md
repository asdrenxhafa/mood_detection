This is a neural network for text mood classification trained on 25,000 movie reviews. 
# 
Add the provider in ```app/Providers/AppServiceProvider.php```
```php
public function register()
{
    $this->app->register('asdrenxhafa\mooddetection\Providers\MoodDetectionCommandServiceProvider');
}
```
#
Training the Model
```bash 
$ php artisan train:model
```

or run programmaticly
```php
$moodDetection = new MoodDetection();
$moodDetection->train();
```
#
Validating the Model
```bash 
$ php artisan validate:model
```

or run programmaticly
```php
$moodDetection = new MoodDetection();
$moodDetection->validate();
```
# 
To run a prediction
```php
$moodDetection = new MoodDetection();

$message = 'I am very happy today';
    
$prediction = $moodDetection->predict($message);
```
