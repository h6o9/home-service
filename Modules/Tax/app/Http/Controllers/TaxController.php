<?php

namespace Modules\Tax\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Tax\app\Http\Requests\TaxRequest;
use Modules\Tax\app\Models\Tax;

class TaxController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index()
    {
        checkAdminHasPermissionAndThrowException('tax.view');

        Paginator::useBootstrap();

        $taxes = Tax::paginate(15);

        return view('tax::index', ['taxes' => $taxes]);
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('tax.create');

        return view('tax::create');
    }

    /**
     * @param  TaxRequest $request
     * @return mixed
     */
    public function store(TaxRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('tax.store');
        $tax = Tax::create($request->validated());

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::Tax,
            $tax,
            'tax_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.tax.edit', ['tax' => $tax->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('tax.edit');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $tax       = Tax::findOrFail($id);
        $languages = allLanguages();

        return view('tax::edit', compact('tax', 'code', 'languages'));
    }

    /**
     * @param  TaxRequest $request
     * @param  Tax        $tax
     * @return mixed
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        checkAdminHasPermissionAndThrowException('tax.update');
        $validatedData = $request->validated();

        $tax->update($validatedData);

        $this->updateTranslations(
            $tax,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.tax.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        checkAdminHasPermissionAndThrowException('tax.delete');

        if ($tax && $tax?->products?->count() > 0) {
            return $this->redirectWithMessage(RedirectType::DELETE->value, notification: [
                'message'    => __('Tax delete failed, Associted with products!'),
                'alert-type' => 'error',
            ]);
        }

        $tax->translations()->each(function ($translation) {
            $translation->tax()->dissociate();
            $translation->delete();
        });

        $tax->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.tax.index');
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('tax.update');
        $tax    = Tax::find($id);
        $status = $tax->status == 1 ? 0 : 1;
        $tax->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
