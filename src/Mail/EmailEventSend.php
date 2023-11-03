<?php

namespace Elfcms\Elfcms\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmailEventSend extends Mailable
{
    use Queueable, SerializesModels;

    public  $emailEvent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailEvent)
    {
        $this->emailEvent = $emailEvent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fields = $this->emailEvent->fields();

        if(!empty($this->emailEvent->custom['from'])) {
            $this->from($this->emailEvent->custom['from']);
        }
        elseif(!empty($fields['from']) && !empty($fields['from']->email)) {
            $this->from($fields['from']->email,$fields['from']->name);
        }
        if(!empty($this->emailEvent->custom['cc'])) {
            $this->cc($this->emailEvent->custom['cc']);
        }
        elseif(!empty($fields['cc']) && !empty($fields['cc']->email)) {
            $this->cc($fields['cc']->email,$fields['cc']->name);
        }
        if(!empty($this->emailEvent->custom['bcc'])) {
            $this->bcc($this->emailEvent->custom['bcc']);
        }
        elseif(!empty($fields['bcc']) && !empty($fields['bcc']->email)) {
            $this->bcc($fields['bcc']->email,$fields['bcc']->name);
        }

        if(!empty($this->emailEvent->custom['to'])) {
            $this->to($this->emailEvent->custom['to']);
        }
        elseif(!empty($fields['to']) && !empty($fields['to']->email)) {
            $this->to($fields['to']->email,$fields['to']->name);
        }

        if(!empty($this->emailEvent->custom['subject'])) {
            $this->subject($this->emailEvent->custom['subject']);
        }
        else {
            $this->subject($this->emailEvent['subject']); // $this->emailEvent->subject not working! Why?
        }
        if(!empty($this->emailEvent->attach) && file_exists($this->emailEvent->attach)) {
            $this->attach($this->emailEvent->attach);
        }
        elseif (!empty($this->emailEvent->attachData) && !empty($this->emailEvent->attachData->data) && !empty($this->emailEvent->attachData->name) && isset($this->emailEvent->attachData->option)) {
            $this->attachData($this->emailEvent->attachData->data,$this->emailEvent->attachData->name,$this->emailEvent->attachData->option);
        }

        $view = 'basic::emails.events.default';
        if(!empty($this->emailEvent->custom['view'])) {
            $view = $this->emailEvent->custom['view'];
        }
        elseif (!empty($this->emailEvent->view)) {
            $view = $this->emailEvent->view;
        }

        if (!empty($this->emailEvent->contentparams)) {
            $this->emailEvent->params = array_merge($this->emailEvent->contentparams,$this->emailEvent->params);
        }
        Log::info('content: ' . $this->emailEvent->content);
        Log::info('event: ' . $this->emailEvent);
        return $this->view($view,['content'=>$this->emailEvent->content, 'params'=>$this->emailEvent->params]);
    }
}
