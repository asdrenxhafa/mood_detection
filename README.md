This is a neural network for text mood classification trained on 25,000 movie reviews. 
# 
Add the provider in ```app/Providers/AppServiceProvider.php```
```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('asdrenxhafa\mooddetection\LaravelCommandServiceProvider');
    }
}
```
#
php train.php
#
php validate.php
# 
php predict.php
