<?php

namespace Avxman\Github\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Avxman\Github\Classes\GithubClass;

class GithubServiceProvider extends ServiceProvider
{

    public function boot(Filesystem $filesystem){
        if(App()->runningInConsole()){
            $this->publishes($this->getFilesNameAll($filesystem), 'avxman-github-config');
        }
    }

    /**
     * @throws \Exception
     */
    public function register(array $server = [], array $config = []) : void
    {
        $this->app->singleton(self::class, GithubClass::class);
    }

    /**
     * Create specified files in folders
     * @param Filesystem $filesystem
     * @param int $index
     * @param bool $all
     * @return array
     */
    protected function getFilesNameAll(Filesystem $filesystem, int $index = 0, bool $all = false) : array{
        $collect = collect()->push(
            [
                dirname(__DIR__, 2).'/config/' => base_path('config').DIRECTORY_SEPARATOR,
            ]
        );
        return $all
            ? collect()->merge($collect->get(0))->toArray()
            : $collect->get($index);
    }

}
