<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Http\Requests\Admin\EmailAddressRequest;
use Elfcms\Elfcms\Models\EmailAddress;
use Illuminate\Http\Request;

class EmailAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return EmailAddress::all()->toJson();
        }
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $addresses = EmailAddress::orderBy($order, $trend)->paginate(30);
        //$addresses = EmailAddress::all();
        return view('elfcms::admin.email.addresses.index',[
            'page' => [
                'title' => __('elfcms::default.email_addresses'),
                'current' => url()->current(),
            ],
            'addresses' => $addresses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.email.addresses.create',[
            'page' => [
                'title' => __('elfcms::default.create_email_address'),
                'current' => url()->current(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailAddressRequest $request)
    {
        //dd($request);
        $validated = $request->validated();
        //dd($request->description);
        $validated['description'] = $request->description;
        //dd($validated);
        $address = EmailAddress::create($validated);

        if ($request->ajax()) {
            $result = 'error';
            $message = __('elfcms::default.error_of_email_address_created');
            $data = [];
            if ($address) {
                $result = 'success';
                $message = __('elfcms::default.email_address_created_successfully');
                $data = ['id'=> $address->id];
            }
            return json_encode(['result'=>$result,'message'=>$message,'data'=>$data]);
        }

        return redirect(route('admin.email.addresses.edit',$address->id))->with('eaddredited',__('elfcms::default.email_address_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\EmailAddress $address
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show(EmailAddress $address, EmailAddressRequest $request)
    {
        if ($request->ajax()) {
            return EmailAddress::find($address->id)->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\EmailAddress $address
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailAddress $address)
    {
        //dd($address);
        return view('elfcms::admin.email.addresses.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_email_address').' #' . $address->id,
                'current' => url()->current(),
            ],
            'address' => $address
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Elfcms\Elfcms\Http\Requests\Admin\EmailAddressRequest  $request
     * @param  \Elfcms\Elfcms\Models\EmailAddress $address
     * @return \Illuminate\Http\Response
     */
    public function update(EmailAddressRequest $request, EmailAddress $address)
    {
        $validated = $request->validated();

        $address->name = $validated['name'];
        $address->email = $validated['email'];
        $address->description = $request->description;

        $address->save();

        return redirect(route('admin.email.addresses.edit',$address->id))->with('eaddredited',__('elfcms::default.email_address_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Elfcms\Elfcms\Models\EmailAddress $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailAddress $address)
    {
        if (!$address->delete()) {
            return redirect(route('admin.email.addresses'))->withErrors(['eaddrdelerror'=>'Error of address deleting']);
        }

        return redirect(route('admin.email.addresses'))->with('eaddrdeleted','Address deleted successfully');
    }
}
