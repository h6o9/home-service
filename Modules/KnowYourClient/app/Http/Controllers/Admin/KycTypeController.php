<?php

namespace Modules\KnowYourClient\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\KnowYourClient\app\Http\Requests\KycTypeStoreRequest;
use Modules\KnowYourClient\app\Models\KycType;

class KycTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kycType = KycType::orderBy('id', 'desc')->get();

        return view('knowyourclient::admin.type.index', compact('kycType'));
    }

    /**
     * @param Request $request
     */
    public function store(KycTypeStoreRequest $request)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyctype              = new KycType();
        $kyctype->name        = $request->name;
        $kyctype->description = $request->description;
        $kyctype->status      = $request->status;
        $kyctype->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(KycTypeStoreRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc              = KycType::find($id);
        $kyc->name        = $request->name;
        $kyc->description = $request->description;
        $kyc->status      = $request->status;
        $kyc->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        return to_route('admin.kyc-list.show', $id);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('kyc.management');

        $kyc = KycType::find($id);

        if ($kyc->kycApplications->count() > 0) {
            return redirect()->back()->with(['message' => __('Unable to delete type associated with applications'), 'alert-type' => 'error']);
        }

        $kyc->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->back()->with($notification);

    }
}
