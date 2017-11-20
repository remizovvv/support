<?php

namespace Omadonex\Support\Traits;

trait CustomizeMailable
{
    public $content = [];
    public $actions = [];
    public $greeting = false;
    public $greetingText = null;
    public $username = null;

    public function addLine($text)
    {
        $this->content[] = [
            'type' => 'line',
            'text' => $text,
        ];

        return $this;
    }

    public function addAction($url, $text)
    {
        $action = [
            'type' => 'action',
            'url' => $url,
            'text' => $text,
        ];

        $this->content[] = $action;
        $this->actions[] = $action;

        return $this;
    }

    public function addGreeting($text)
    {
        $this->greeting = true;
        $this->greetingText = $text;

        return $this;
    }

    public function useDefaultGreeting($username)
    {
        $this->greeting = true;
        $this->username = $username;

        return $this;
    }
}
