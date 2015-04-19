<?php

/*
 * This file is part of Laravel Navigation.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dandan\Question;

use Illuminate\Support\ServiceProvider;
use Dandan\Question\Question;

/**
 * This is the navigation service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class QuestionServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/../views'), 'question');
         $this->setupBlade();
    }
     protected function setupBlade()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

       
    
        $this->app['view']->share('__navtype', 'default'); 

        $blade->extend(function ($value, $compiler) {
            $pattern = $compiler->createPlainMatcher('questionnavbar');
            $replace = '$1<?php echo \Dandan\Question\Facades\Question::make();?>$2';
          
         
            return preg_replace($pattern, $replace, $value);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerQuestion();
    }

    /**
     * Register the navigation class.
     *
     * @return void
     */
    protected function registerQuestion()
    {
        $this->app->singleton('question', function ($app) {
           

            $question = new Question();
        
            $app->refresh('request', $question, 'setRequest');  // app refresh 加速解析
//$target->{$method}($instance); instance为 从app里解析出来的request;
            // 从依赖里注入的request
            return $question;
        });

        $this->app->alias('question', 'Dandan\Question\Question');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'question',
        ];
    }
}
