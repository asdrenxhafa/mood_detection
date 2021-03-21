This is a neural network for text mood classification trained on 25,000 movie reviews. 
# 
Add the provider in ```app/Providers/AppServiceProvider.php```
```php
public function register()
{
    $this->app->register('asdrenxhafa\mooddetection\MoodDetectionCommandServiceProvider');
}
```
#
php train.php
#
php validate.php
# 
php predict.php
