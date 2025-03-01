<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Views;
use Elfcms\Elfcms\Http\Requests\Admin\EmailEventRequest;
use Elfcms\Elfcms\Models\EmailAddress;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Models\EmailEventAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class EmailEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return EmailEvent::all()->toJson();
        }
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $events = EmailEvent::orderBy($order, $trend)->paginate(60);

        $events = EmailEvent::all();

        $event = new EmailEvent();
        $strings = $event->getProperty('strings');
        $protected = [];
        if (!empty($strings)) {
            foreach ($strings as $string) {
                $protected[] = $string['code'];
            }
        }

        return view('elfcms::admin.email.events.index',[
            'page' => [
                'title' => __('elfcms::default.email_events'),
                'current' => url()->current(),
            ],
            'events' => $events,
            'protected' => $protected,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $event = new EmailEvent;
        //dd($el->emailFields);
        $addresses = EmailAddress::all();
        //dd(config('filesystems'));
        /* $views = Views::list('emails/events');
        if (empty($views)) {
            $views = array_merge($views,Views::list('resources/views/emails/events','elfcmsdev'));
        }
        $views = array_merge($views,Views::list('emails/events','publicviews')); */
        $views = Views::list('elfcms/emails/events','publicviews');
        if (empty($views)) {
            $views = array_merge($views,Views::list('emails/events','elfcmsviews','elfcms'));
        }
        if (empty($views)) {
            $views = array_merge($views,Views::list('resources/views/emails/events','elfcmsdev','elfcms'));
        }
        $views = array_merge($views,Views::list('emails/events','publicviews'));
        return view('elfcms::admin.email.events.create',[
            'page' => [
                'title' => __('elfcms::default.create_email_event'),
                'current' => url()->current(),
            ],
            'fields' => $event->emailFields,
            'addresses' => $addresses,
            'views' => $views
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailEventRequest $request)
    {

        $params = [];
        foreach ($request->params_new as $param) {
            if (!empty($param['name'])) {
                $params[$param['name']] = $param['value'];
            }
        }

        $validated = $request->validated();

        /* $validated['description'] = $request->description;
        $validated['subject'] = $request->subject;
        $validated['content'] = $request->content;
        $validated['contentparams'] = $params;
        $validated['view'] = $request->view; */

        $event = EmailEvent::create($validated);

        if (!empty($request->from)) {
            EmailEventAddress::create([
                'field' => 'from',
                'email_event_id' => $event->id,
                'email_address_id' => $request->from
            ]);
        }

        if (!empty($request->to)) {
            EmailEventAddress::create([
                'field' => 'to',
                'email_event_id' => $event->id,
                'email_address_id' => $request->to
            ]);
        }

        if (!empty($request->cc)) {
            EmailEventAddress::create([
                'field' => 'cc',
                'email_event_id' => $event->id,
                'email_address_id' => $request->cc
            ]);
        }

        if (!empty($request->bcc)) {
            EmailEventAddress::create([
                'field' => 'bcc',
                'email_event_id' => $event->id,
                'email_address_id' => $request->bcc
            ]);
        }

        if ($request->ajax()) {
            $result = 'error';
            $message = __('elfcms::default.error_of_email_event_created');
            $data = [];
            if ($event) {
                $result = 'success';
                $message = __('elfcms::default.email_event_created_successfully');
                $data = ['id'=> $event->id];
            }
            return json_encode(['result'=>$result,'message'=>$message,'data'=>$data]);
        }

        return redirect(route('admin.email.events.edit',$event->id))->with('success',__('elfcms::default.email_event_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\EmailEvent $event
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show(EmailEvent $event, EmailEventRequest $request)
    {
        if ($request->ajax()) {
            return EmailEvent::find($event->id)->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailEvent $event)
    {
        $addresses = EmailAddress::all();

        $params = $event->contentparams ?? [];

        $views = Views::list('elfcms/emails/events','publicviews');
        if (empty($views)) {
            $views = array_merge($views,Views::list('emails/events','elfcmsviews','elfcms'));
        }
        if (empty($views)) {
            $views = array_merge($views,Views::list('resources/views/emails/events','elfcmsdev','elfcms'));
        }
        $views = array_merge($views,Views::list('emails/events','publicviews'));

        return view('elfcms::admin.email.events.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_email_event').' #' . $event->id,
                'current' => url()->current(),
            ],
            'event' => $event,
            'fields' => $event->fields(),
            'addresses' => $addresses,
            'params' => $params,
            'views' => $views,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailEventRequest  $request
     * @param  \Elfcms\Elfcms\Models\EmailEvent $event
     * @return \Illuminate\Http\Response
     */
    public function update(EmailEventRequest $request, EmailEvent $event)
    {

        $params = [];
        foreach ($request->params_new as $param) {
            if (!empty($param['name'])) {
                $params[$param['name']] = $param['value'];
            }
        }

        $validated = $request->validated();

        $event->code = $validated['code'];
        $event->name = $validated['name'];
        $event['subject'] = $request->subject; // ??? $event->subject not working! Why?
        $event->description = $request->description;
        $event->content = $request->content;
        $event->view = $request->view;
        $event->contentparams = $params;


        $existFields = $event->fields();

        //from
        if (empty($existFields['from'])) {
            if (!empty($request->from)) {
                EmailEventAddress::create([
                    'field' => 'from',
                    'email_event_id' => $event->id,
                    'email_address_id' => $request->from
                ]);
            }
        }
        else {
            $eventAddress = EmailEventAddress::where('email_event_id',$event->id)->where('field','from')->first();
            if (empty($request->from)) {
                $eventAddress->delete();
            }
            else {
                $eventAddress->email_address_id = $request->from;
                $eventAddress->save();
            }
        }

        //to
        if (empty($existFields['to'])) {
            if (!empty($request->to)) {
                EmailEventAddress::create([
                    'field' => 'to',
                    'email_event_id' => $event->id,
                    'email_address_id' => $request->to
                ]);
            }
        }
        else {
            $eventAddress = EmailEventAddress::where('email_event_id',$event->id)->where('field','to')->first();
            if (empty($request->to)) {
                $eventAddress->delete();
            }
            else {
                $eventAddress->email_address_id = $request->to;
                $eventAddress->save();
            }
        }

        //cc
        if (empty($existFields['cc'])) {
            if (!empty($request->cc)) {
                EmailEventAddress::create([
                    'field' => 'cc',
                    'email_event_id' => $event->id,
                    'email_address_id' => $request->cc
                ]);
            }
        }
        else {
            $eventAddress = EmailEventAddress::where('email_event_id',$event->id)->where('field','cc')->first();
            if (empty($request->cc)) {
                $eventAddress->delete();
            }
            else {
                $eventAddress->email_address_id = $request->cc;
                $eventAddress->save();
            }
        }

        //bcc
        if (empty($existFields['bcc'])) {
            if (!empty($request->bcc)) {
                EmailEventAddress::create([
                    'field' => 'bcc',
                    'email_event_id' => $event->id,
                    'email_address_id' => $request->bcc
                ]);
            }
        }
        else {
            $eventAddress = EmailEventAddress::where('email_event_id',$event->id)->where('field','bcc')->first();
            if (empty($request->bcc)) {
                $eventAddress->delete();
            }
            else {
                $eventAddress->email_address_id = $request->bcc;
                $eventAddress->save();
            }
        }


        $event->save($validated);

        return redirect(route('admin.email.events.edit',$event->id))->with('success',__('elfcms::default.email_event_created_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Elfcms\Elfcms\Models\EmailEvent $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailEvent $event)
    {
        if (!$event->delete()) {
            return redirect(route('admin.email.events'))->withErrors(['eeventdelerror'=>'Error of event deleting']);
        }

        return redirect(route('admin.email.events'))->with('success','Event deleted successfully');
    }
}
