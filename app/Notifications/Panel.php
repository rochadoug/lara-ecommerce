<?php

namespace App\Notifications;

class Panel
{
    /**
     * The panel text.
     *
     * @var string
     */
    public $text;

    /**
     * Create a new panel instance.
     *
     * @param  string  $text
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }
}
