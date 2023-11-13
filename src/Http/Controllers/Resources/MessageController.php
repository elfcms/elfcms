<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::all();
        return view('elfcms::admin.messages.index',[
            'page' => [
                'title' => __('elfcms::default.messages'),
                'current' => url()->current(),
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $message = (object)$request->old();
        if (!isset($message->active)) {
            $message->active = 1;
        }
        return view('elfcms::admin.messages.create',[
            'page' => [
                'title' => __('elfcms::default.create_message'),
                'current' => url()->current(),
            ],
            'message' => $message,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $validated['text'] = $request->text;
        $validated['theme'] = $request->theme;
        $validated['date_from'] = $request->date_from;
        $validated['date_to'] = $request->date_to;
        $validated['is_popup'] = empty($request->is_popup) ? 0 : 1;
        $validated['close_remember'] = empty($request->close_remember) ? 0 : 1;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $message = Message::create($request->all());

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.messages.index'))->with('success',__('elfcms::default.message_created_successfully'));
        }
        return redirect(route('admin.messages.edit',$message))->with('success',__('elfcms::default.message_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        return view('elfcms::admin.messages.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_message').' #' . $message->id,
                'current' => url()->current(),
            ],
            'message' => $message
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required'
        ]);

        $message->name = $validated['name'];
        $message->code = $validated['code'];
        $message->text = $request->text;
        $message->theme = $request->theme;
        $message->date_from = $request->date_from;
        $message->date_to = $request->date_to;
        $message->is_popup = empty($request->is_popup) ? 0 : 1;
        $message->close_remember = empty($request->close_remember) ? 0 : 1;
        $message->active = empty($request->active) ? 0 : 1;

        $message->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.messages.index'))->with('success',__('elfcms::default.message_created_successfully'));
        }
        return redirect(route('admin.messages.edit',$message))->with('success',__('elfcms::default.message_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        if (!$message->delete()) {
            return redirect(route('admin.messages.index'))->withErrors(['messagedelerror'=>__('elfcms::default.message_delete_error')]);
        }

        return redirect(route('admin.messages.index'))->with('success',__('elfcms::default.message_deleted_successfully'));
    }
}
