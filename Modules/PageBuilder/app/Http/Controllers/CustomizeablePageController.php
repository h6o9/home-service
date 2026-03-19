<?php

namespace Modules\PageBuilder\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\PageBuilder\app\Http\Requests\PageRequest;
use Modules\PageBuilder\app\Models\CustomizeablePage;

class CustomizeablePageController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index()
    {
        checkAdminHasPermissionAndThrowException('page.view');

        $pages = CustomizeablePage::paginate(20);

        return view('pagebuilder::pages.index', ['pages' => $pages]);
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('page.create');

        return view('pagebuilder::pages.create');
    }

    /**
     * @param  PageRequest $request
     * @return mixed
     */
    public function store(PageRequest $request)
    {
        checkAdminHasPermissionAndThrowException('page.store');

        $page = CustomizeablePage::create($request->validated());

        $this->generateTranslations(
            TranslationModels::CustomizablePage,
            $page,
            'customizeable_page_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.custom-pages.edit', ['page' => $page->id, 'code' => allLanguages()->first()->code]);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('page.edit');
        $code = request('code') ?? getSessionLanguage();
        abort_unless(Language::where('code', $code)->exists(), 404);
        $languages = allLanguages();
        $page      = CustomizeablePage::findOrFail($id);

        return view('pagebuilder::pages.edit', compact('page', 'code', 'languages'));
    }

    /**
     * @param  PageRequest $request
     * @param  $id
     * @return mixed
     */
    public function update(PageRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('page.update');

        $code = request('code') ?? getSessionLanguage();

        abort_unless(Language::where('code', $code)->exists(), 404);

        $page = CustomizeablePage::findOrFail($id);

        $page->fill($request->validated());

        $validatedData = $request->validated();

        $this->updateTranslations(
            $page,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('page.delete');

        $page = CustomizeablePage::whereNotIn('slug', ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'])->find($id);
        if ($page) {
            $page->translations()->each(function ($translation) {
                $translation->customizeablePage()->dissociate();
                $translation->delete();
            });
            $page->delete();

            return $this->redirectWithMessage(RedirectType::DELETE->value);
        }

        return $this->redirectWithMessage(RedirectType::ERROR->value);
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        if (checkAdminHasPermission('page.update')) {
            $pageItem = CustomizeablePage::whereNotIn('slug', ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'])->find($id);

            if (!$pageItem) {
                return response()->json([
                    'success' => false,
                ], 403);
            }
            $status = $pageItem->status == 1 ? 0 : 1;
            $pageItem->update(['status' => $status]);

            $notification = __('Updated successfully');

            return response()->json([
                'success' => true,
                'message' => $notification,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 403);
    }
}
