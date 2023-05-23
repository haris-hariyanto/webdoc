<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Validation\Rule;
use App\Helpers\Settings;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Documents') => '',
        ];

        return view('admin.documents.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $settings = new Settings('website');
        $slug = $settings->get('slug');

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $documentsCount = Document::count();

        $documents = Document::with(['disk'])
            ->when($querySearch, function ($query) use ($querySearch) {
                $query->where('title', 'like', '%' . $querySearch . '%');
            });
        $documentsCountFiltered = $documents->count();

        $documents = $documents->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
            'total' => $documentsCountFiltered,
            'totalNotFiltered' => $documentsCount,
            'rows' => $documents->map(function ($document) use ($slug) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'downloads' => $document->downloads,
                    'views' => $document->views,
                    'file_size' => \App\Helpers\Text::fileSize($document->file_size),
                    'author' => $document->author,
                    'disk' => $document->disk->name,
                    'file_type' => strtoupper($document->file_type),
                    'pages' => number_format($document->pages, 0, ',', '.'),
                    'language' => strtoupper($document->language),
                    'menu' => view('admin.documents._menu', ['document' => $document, 'slug' => $slug])->render(),
                ];
            }),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document ${{ modelInstance}}
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return redirect()->route('admin.documents.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Document $document)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Documents') => route('admin.documents.index'),
            __('Edit Document') => '',
        ];

        return view('admin.documents.edit', compact('document', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:512'],
            'slug' => ['required', 'string', 'max:512', 'regex:/^[a-zA-Z0-9\-]+$/', Rule::unique('documents')->ignore($document)],
            'author' => ['nullable', 'string', 'max:50'],
        ]);

        $document->update($validated);

        return redirect()->route('admin.documents.edit', ['document' => $document])->with('success', __('Document has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', __('Document has been deleted!'));
    }

    public function bulkDelete(Request $request)
    {
        $documentsIDToDelete = $request->post('documentsIDToDelete');
        $documentsIDToDelete = json_decode($documentsIDToDelete, true);

        Document::where(function ($query) use ($documentsIDToDelete) {
            foreach ($documentsIDToDelete as $documentIDToDelete) {
                $query->orWhere('id', $documentIDToDelete);
            }
        })->delete();

        return redirect()->back()->with('success', __(':count documents has been deleted!', ['count' => count($documentsIDToDelete)]));
    }
}