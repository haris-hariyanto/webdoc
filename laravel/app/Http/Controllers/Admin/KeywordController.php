<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keyword;

class KeywordController extends Controller
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
            __('Keywords') => '',
        ];

        return view('admin.keywords.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 25);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'priority');
        $queryOrder = $request->query('order', 'DESC');
        $querySearch = $request->query('search');
        $queryStatus = $request->query('status', 'READY');

        $keywordsCount = Keyword::count();

        $keywords = Keyword::when($querySearch, function ($query) use ($querySearch) {
                $query->where('keyword', 'like', '%' . $querySearch . '%');
            })
            ->when($queryStatus, function ($query) use ($queryStatus) {
                if ($queryStatus == 'READY') {
                    $query->where('status', 'READY');
                }
                if ($queryStatus == 'DONE') {
                    $query->where('status', 'DONE');
                }
                if ($queryStatus == 'FAILED') {
                    $query->where('status', 'FAILED');
                }
                if ($queryStatus == 'PROCESS') {
                    $query->where('status', 'PROCESS');
                }
            });
        $keywordsCountFiltered = $keywords->count();

        $keywords = $keywords->orderBy($querySort, $queryOrder)
            ->orderBy('id', 'ASC')
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
            'total' => $keywordsCountFiltered,
            'totalNotFiltered' => $keywordsCount,
            'rows' => $keywords->map(function ($keyword) {
                return [
                    'id' => $keyword->id,
                    'keyword' => $keyword->keyword,
                    'status' => $this->status($keyword->status),
                    'priority' => $keyword->priority,
                ];
            }),
        ];
    }

    public function status($status)
    {
        switch ($status) {
            case 'READY':
                return '<span class="badge badge-info">' . __('Waiting') . '</span>';
                break;
            case 'PROCESS':
                return '<span class="badge badge-warning">' . __('Processing') . '</span>';
                break;
            case 'DONE':
                return '<span class="badge badge-success">' . __('Done') . '</span>';
                break;
            case 'FAILED':
                return '<span class="badge badge-danger">' . __('Failed') . '</span>';
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Keywords') => route('admin.keywords.index'),
            __('Create Keyword') => '',
        ];

        return view('admin.keywords.create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in a storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string'],
            'high_priority' => ['nullable'],
        ]);

        $keywords = $validated['keyword'];
        $keywords = explode(',', $keywords);

        foreach ($keywords as $keyword) {
            $keyword = strtolower(trim($keyword));
            if (!empty($keyword)) {
                Keyword::create([
                    'keyword' => $keyword,
                    'priority' => !empty($validated['high_priority']) && $validated['high_priority'] == 'Y' ? time() : 0,
                ]);
            }
        }

        return redirect()->route('admin.keywords.index')->with('success', __('Keyword has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keyword ${{ modelInstance}}
     * @return \Illuminate\Http\Response
     */
    public function show(Keyword $keyword)
    {
        return redirect()->route('admin.keywords.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\Keyword $keyword
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Keyword $keyword)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Keywords') => route('admin.keywords.index'),
            __('Edit Keyword') => '',
        ];

        return view('admin.keywords.edit', compact('keyword', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Keyword $keyword
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string'],

        ]);

        $keyword->update($validated);

        return redirect()->route('admin.keywords.edit', ['keyword' => $keyword])->with('success', __('Keyword has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keyword $keyword
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Keyword $keyword)
    {
        $keyword->delete();

        return redirect()->back()->with('success', __('Keyword has been deleted!'));
    }

    public function bulkDelete(Request $request)
    {
        $keywordsIDToDelete = $request->post('bulkID');
        $keywordsIDToDelete = json_decode($keywordsIDToDelete, true);

        Keyword::where(function ($query) use ($keywordsIDToDelete) {
            foreach ($keywordsIDToDelete as $keywordIDToDelete) {
                $query->orWhere('id', $keywordIDToDelete);
            }
        })->delete();

        return redirect()->back()->with('success', __(':count keywords has been deleted!', ['count' => count($keywordsIDToDelete)]));
    }

    public function addPriority(Request $request)
    {
        $currentTimestamp = time();

        $scrapeQueuesID = $request->post('bulkID');
        $scrapeQueuesID = json_decode($scrapeQueuesID, true);

        Keyword::where(function ($query) use ($scrapeQueuesID) {
            foreach ($scrapeQueuesID as $scrapeQueueID) {
                $query->orWhere('id', $scrapeQueueID);
            }
        })->update(['priority' => $currentTimestamp]);

        return redirect()->back()->with('success', __(':count Scrape Queues has been updated!', ['count' => count($scrapeQueuesID)]));
    }

    public function removePriority(Request $request)
    {
        $scrapeQueuesID = $request->post('bulkID');
        $scrapeQueuesID = json_decode($scrapeQueuesID, true);

        Keyword::where(function ($query) use ($scrapeQueuesID) {
            foreach ($scrapeQueuesID as $scrapeQueueID) {
                $query->orWhere('id', $scrapeQueueID);
            }
        })->update(['priority' => 0]);

        return redirect()->back()->with('success', __(':count Scrape Queues has been updated!', ['count' => count($scrapeQueuesID)]));
    }

    public function resetFailed(Request $request)
    {
        $total = Keyword::where('status', 'FAILED')->orWhere('status', 'PROCESS')->update([
            'status' => 'READY',
        ]);

        return redirect()->back()->with('success', __(':count Scrape Queues has been updated!', ['count' => $total]));
    }
}