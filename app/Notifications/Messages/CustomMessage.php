<?php

namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Panel;

class CustomMessage extends MailMessage
{
    /**
     * The "intro" lines of the notification.
     *
     * @var array
     */
    public $panel;

    /**
     * Add a panel text to the notification.
     *
     * @param  mixed  $panel
     * @return $this
     */
    public function panel($panel)
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * Overloaded method
     *
     * Format the given line of text.
     *
     * @param  \Illuminate\Contracts\Support\Htmlable|string|array  $line
     * @return \Illuminate\Contracts\Support\Htmlable|string
     */
    protected function formatLine($line)
    {
        if ($line instanceof Htmlable) {
            return $line;
        }

        if ($line instanceof Panel) {

            if (is_array($line->text)) {
                $line->text = implode(' ', array_map('trim', $line->text));
            }

            $line->text = trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line->text))));

            return $line;
        }

        if (is_array($line)) {
            return implode(' ', array_map('trim', $line));
        }

        return trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line))));
    }

    /**
     * Overloaded method
     *
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'level' => $this->level,
            'subject' => $this->subject,
            'greeting' => $this->greeting,
            'salutation' => $this->salutation,
            'introLines' => $this->introLines,
            'outroLines' => $this->outroLines,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
            'panel' => $this->panel,
        ];
    }

}