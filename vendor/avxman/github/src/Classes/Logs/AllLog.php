<?php

namespace Avxman\Github\Classes\Logs;

use Avxman\Github\Messages\GithubMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class AllLog
{

    protected array $config = [];
    protected string $dir = '';
    protected string $name = 'github.log';
    protected string $full_name = '';
    protected int $size = 1024000;
    protected GithubMessage $message;
    protected string $date_format = 'd.m.Y H:i:s';

    protected function getDate() : string{
        return Carbon::now()->format($this->date_format);
    }

    protected function rewrite() : bool{
        return !File::exists($this->full_name) || File::size($this->full_name) >= $this->size;
    }

    public function __construct(array $config){

        $this->config = $config;
        $this->dir = storage_path('/logs');
        $this->full_name = $this->dir.'/'.$this->name;
        $this->message = new GithubMessage($this->config['IS_DEBUG']??false);

    }

    public function write(string $text) : void{
        $text = $this->getDate().': '.$text.PHP_EOL;
        $status = $this->rewrite() ? File::put($this->full_name, $text) : (bool)File::append($this->full_name, $text);
        if(!$status){
            $this->message->setMessage('Не удалось сохранить данные в лог файл')->error();
        }
    }

    public function read() : string{
        if(empty($file = File::get($this->full_name))){
            $this->message->setMessage('Не удалось открыть файл логов')->error();
        }
        return $file;
    }

}
