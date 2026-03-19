<?php

namespace Modules\PaymentWithdraw\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Wallet\app\Models\WalletHistory;

class WithdrawMethodController extends Controller
{
    use GlobalMailTrait;

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawMethod::query();

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('description', 'like', '%' . $request->keyword . '%')
                ->orWhere('min_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('max_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%');
        });
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->filled('par-page')) {
            $methods = $query->paginate($request->get('par-page'))->withQueryString();
        } else {
            $methods = $query->paginate()->withQueryString();
        }

        // $methods = WithdrawMethod::all();

        return view('paymentwithdraw::admin.method.index', compact('methods'));
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        return view('paymentwithdraw::admin.method.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];
        $request->validate($rules, $customMessages);

        $method                  = new WithdrawMethod;
        $method->name            = $request->name;
        $method->min_amount      = $request->minimum_amount;
        $method->max_amount      = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description     = $request->description;
        $method->status          = $request->status;
        $method->save();

        $notification = __('Create Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');
        $method = WithdrawMethod::find($id);

        return view('paymentwithdraw::admin.method.edit', compact('method'));
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $method                  = WithdrawMethod::find($id);
        $method->name            = $request->name;
        $method->min_amount      = $request->minimum_amount;
        $method->max_amount      = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description     = $request->description;
        $method->status          = $request->status;
        $method->save();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $method = WithdrawMethod::find($id);
        $method->delete();

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param Request $request
     */
    public function withdraw_list(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawRequest::query();

        $query->with('user');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->filled('par-page')) {
            $withdraws = $query->paginate($request->get('par-page'))->withQueryString();
        } else {
            $withdraws = $query->paginate()->withQueryString();
        }

        $title = __('Withdraw request');

        $users = User::select('name', 'id')->whereHas('seller')->get();

        return view('paymentwithdraw::admin.index', compact('withdraws', 'title', 'users'));
    }

    /**
     * @param Request $request
     */
    public function pending_withdraw_list(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawRequest::query();

        $query->with('user')->where('status', 'pending');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->filled('par-page')) {
            $withdraws = $query->paginate($request->get('par-page'))->withQueryString();
        } else {
            $withdraws = $query->paginate()->withQueryString();
        }

        $title = __('Pending withdraw');
        $users = User::select('name', 'id')->whereHas('seller')->get();

        return view('paymentwithdraw::admin.index', compact('withdraws', 'title', 'users'));
    }

    /**
     * @param $id
     */
    public function show_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::find($id);

        return view('paymentwithdraw::admin.show', compact('withdraw'));
    }

    /**
     * @param $id
     */
    public function destroy_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::findOrFail($id);

        if ($withdraw->status == 'approved') {
            $notification = __('You can not delete approved withdraw request');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.withdraw-list')->with($notification);
        }

        $withdraw->delete();

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-list')->with($notification);
    }

    /**
     * @param $id
     */
    public function approved_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::find($id);
        $user     = User::findOrFail($withdraw->user_id);

        try {
            DB::beginTransaction();

            $withdraw->status        = 'approved';
            $withdraw->approved_date = now();
            $withdraw->save();

            if ($withdraw->status == 'approved') {
                // Cast to string for bcsub to maintain precision
                $userWalletBalance   = number_format((float) $user->wallet_balance, 2, '.', '');
                $walletHistoryAmount = number_format((float) $withdraw->total_amount, 2, '.', '');

                // Subtract using bcsub for accurate decimal arithmetic
                $userWalletNewBalance = bcsub($userWalletBalance, $walletHistoryAmount, 2);

                // Update the wallet balance
                $user->wallet_balance = $userWalletNewBalance;
                $user->save();

                $wallet                      = new WalletHistory();
                $wallet->user_id             = $withdraw->user_id;
                $wallet->vendor_id           = $withdraw->vendor_id;
                $wallet->withdraw_request_id = $withdraw->id;
                $wallet->amount              = $withdraw->total_amount;
                $wallet->transaction_id      = uniqid('withdraw_');
                $wallet->payment_gateway     = $withdraw->method;
                $wallet->payment_status      = PaymentStatus::COMPLETED->value;
                $wallet->transaction_type    = 'debit';
                $wallet->save();

                notifyAdmin(
                    'Withdraw request approval',
                    "A withdraw request has been approved for {$user->name} ({$user->email}). Please check the details.",
                    'success',
                    link: route('admin.show-withdraw', ['id' => $withdraw->id])
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logError("Unable to approve withdraw request", $e);

            return redirect()->route('admin.withdraw-list')->with([
                'message'    => __('Failed to approve withdraw request. Please try again later.'),
                'alert-type' => 'error',
            ]);
        }

        $amount = defaultCurrency($withdraw->withdraw_amount);

        [$subject, $message] = $this->fetchEmailTemplate('approved_withdraw', ['user_name' => $user->name, 'amount' => $amount, 'method' => $withdraw->method]);

        $this->sendMail($user->email, $subject, $message, [
            'Withdraw Details' => route('seller.my-withdraw.show', ['my_withdraw' => $withdraw->id]),
        ]);

        $notification = __('Withdraw request approval successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-list')->with($notification);

    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw_method = WithdrawMethod::find($id);
        $status          = $withdraw_method->status == 'active' ? 'inactive' : 'active';
        $withdraw_method->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
