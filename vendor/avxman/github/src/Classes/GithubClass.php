<?php

namespace Avxman\Github\Classes;

use Avxman\Github\Classes\Connections\BaseConnection;
use Avxman\Github\Classes\Connections\GithubConnection;
use Avxman\Github\Classes\Connections\SiteConnection;
use Avxman\Github\Classes\Events\BaseEvent;
use Avxman\Github\Classes\Events\GithubEvent;
use Avxman\Github\Classes\Events\SiteEvent;
use Avxman\Github\Messages\GithubMessage;

class GithubClass
{

    protected array $config = [];
    protected array $server = [];
    protected bool $post_is_github = true;
    protected GithubMessage $message;

    protected function fromGithub() : GithubConnection{
        return new GithubConnection($this->server, $this->config);
    }

    protected function fromSite() : SiteConnection{
        return new SiteConnection($this->server, $this->config);
    }

    protected function eventGithub() : BaseEvent{
        return new GithubEvent($this->server, $this->config);
    }

    protected function eventSite() : BaseEvent{
        return new SiteEvent($this->server, $this->config);
    }

    public function __construct(){
        $this->server = request()->server->all();
        $this->config = config()->get('github');
        $this->post_is_github = request()->has('type', $this->config['IS_GITHUB']);
        $this->message = new GithubMessage($this->config['IS_DEBUG']);
        $errorMessage = [];
        if(!count($this->config)) {
            $isError = true;
            $errorMessage[] = "Не найден конфигурационный файл.";
        }
        elseif (!$this->config['GITHUB_TOKEN'] || empty($this->config['GITHUB_TOKEN'])){
            $isError = true;
            $errorMessage[] = empty($this->config['GITHUB_TOKEN'])
                ? "Токен пустой"
                : "Не найден токен";
        }
        elseif (!$this->config['IS_DEBUG'] || empty($this->config['IS_DEBUG'])){
            $isError = true;
            $errorMessage[] = empty($this->config['IS_DEBUG'])
                ? "Дебагер пуст"
                : "Не найден ключ дебагер";
        }
        else{$isError = false;}
        if($isError){
            $this->message->setMessages($errorMessage)->errors();
        }
    }

    public function instance() : void{

        $instance = $this->post_is_github
            ? $this->fromGithub()
            : $this->fromSite();

        if(!$instance->isConnect()){
            $messages = array_merge(['Не удалось соеденится с адресом'], $instance->errorMessage());
            $this->message->setMessages($messages)->errors();
        }

        if (!count($result = $instance->getData())){
            $messages = array_merge(['Данные не получены'], $instance->errorMessage());
            $this->message->setMessages($messages)->errors();
        }

        $event = $this->post_is_github
            ? $this->eventGithub()
            : $this->eventSite();

        if(!$event->events($result)){
            header('HTTP/1.0 404 Not Found');
            echo "Event:$this->server[HTTP_X_GITHUB_EVENT]";
            die();
            // Payload:\n //print_r($result);
        }

    }

}
