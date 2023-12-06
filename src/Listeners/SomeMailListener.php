<?php

namespace Elfcms\Elfcms\Listeners;

use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Events\SomeMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SomeMailListener
{
    public $event;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Elfcms\Elfcms\Events\SomeMailEvent  $event
     * @return void
     */
    public function handle(SomeMailEvent $event)
    {
        Log::info('SomeMailEvent: ' . $event->eventCode);

        $emailEvent = EmailEvent ::where('code',$event->eventCode)->first();

        if (!empty($event->eventProps['text'])) {
            $emailEvent->text = $event->eventProps['text'];
        }

        if (!empty($event->eventProps['subject'])) {
            $emailEvent->subject = $event->eventProps['subject'];
        }

        if (!empty($event->eventProps['attach'])) {
            $emailEvent->attach = $event->eventProps['attach'];
        }

        if (!empty($event->eventProps['params'])) {
            $emailEvent->params = $event->eventProps['params'];
        }

        if (!empty($event->eventProps['to'])) {
            $emailEvent->custom['to'] = $event->eventProps['to'];
        }

        if (!empty($event->eventProps['cc'])) {
            $emailEvent->custom['cc'] = $event->eventProps['cc'];
        }

        if (!empty($event->eventProps['bcc'])) {
            $emailEvent->custom['bcc'] = $event->eventProps['bcc'];
        }

        if (!empty($event->eventProps['subject'])) {
            $emailEvent->custom['subject'] = $event->eventProps['subject'];
        }

        if (!empty($event->eventProps['from'])) {
            $emailEvent->custom['from'] = $event->eventProps['from'];
        }

        if (!empty($event->eventProps['view'])) {
            $emailEvent->custom['view'] = $event->eventProps['view'];
        }

        $emailEvent->eventUser = $event->eventUser;

        Mail::send(new \Elfcms\Elfcms\Mail\EmailEventSend($emailEvent));
    }
}
