<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DiskController extends Controller
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
            __('Disks') => '',
        ];

        return view('admin.disks.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $disksCount = Disk::count();

        $disks = Disk::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        $disksCountFiltered = $disks->count();

        $disks = $disks->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
            'total' => $disksCountFiltered,
            'totalNotFiltered' => $disksCount,
            'rows' => $disks->map(function ($disk) {
                return [
                    'id' => $disk->id,
                    'name' => $disk->name,
                    'total_size' => \App\Helpers\Text::fileSize($disk->total_size),
                    'total_files' => number_format($disk->total_files, 0, ',', '.'),
                    'endpoint' => $disk->endpoint,
                    'bucket' => $disk->bucket,
                    'is_active' => $disk->is_active == 'Y' ? __('Yes') : __('No'),
                    'menu' => view('admin.disks._menu', ['disk' => $disk])->render(),
                ];
            }),
        ];
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
            __('Disks') => route('admin.disks.index'),
            __('Create Disk') => '',
        ];

        return view('admin.disks.create', compact('breadcrumb'));
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
            'name' => ['required', 'string', 'max:255'],
            'endpoint' => ['required', 'string', 'max:255', 'url'],
            'region' => ['nullable', 'string', 'max:255'],
            'access_key' => ['required', 'string', 'max:255'],
            'secret_key' => ['required', 'string', 'max:255'],
            'bucket' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'in:Y,N'],
        ]);

        // Check disk
        if ($this->checkDisk($validated['endpoint'], $validated['access_key'], $validated['secret_key'], $validated['bucket'], $validated['region']) == false) {
            return redirect()->back()->with('error', __('Unable to access disk.'))->withInput();
        }
        // [END] Check disk

        Disk::create($validated);

        return redirect()->route('admin.disks.index')->with('success', __('Disk has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disk ${{ modelInstance}}
     * @return \Illuminate\Http\Response
     */
    public function show(Disk $disk)
    {
        return redirect()->route('admin.disks.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\Disk $disk
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Disk $disk)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Disks') => route('admin.disks.index'),
            __('Edit Disk') => '',
        ];

        return view('admin.disks.edit', compact('disk', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Disk $disk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disk $disk)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'endpoint' => ['required', 'string', 'max:255', 'url'],
            'region' => ['nullable', 'string', 'max:255'],
            'access_key' => ['required', 'string', 'max:255'],
            'secret_key' => ['required', 'string', 'max:255'],
            'bucket' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'in:Y,N'],
        ]);

        // Check disk
        if ($this->checkDisk($validated['endpoint'], $validated['access_key'], $validated['secret_key'], $validated['bucket'], $validated['region']) == false) {
            return redirect()->back()->with('error', __('Unable to access disk.'))->withInput();
        }
        // [END] Check disk

        $disk->update($validated);

        return redirect()->route('admin.disks.edit', ['disk' => $disk])->with('success', __('Disk has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disk $disk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Disk $disk)
    {
        $disk->delete();

        return redirect()->back()->with('success', __('Disk has been deleted!'));
    }

    public function bulkDelete(Request $request)
    {
        $disksIDToDelete = $request->post('disksIDToDelete');
        $disksIDToDelete = json_decode($disksIDToDelete, true);

        Disk::where(function ($query) use ($disksIDToDelete) {
            foreach ($disksIDToDelete as $diskIDToDelete) {
                $query->orWhere('id', $diskIDToDelete);
            }
        })->delete();

        return redirect()->back()->with('success', __(':count disks has been deleted!', ['count' => count($disksIDToDelete)]));
    }

    private function checkDisk($endpoint, $accessKey, $secretKey, $bucket, $region = null)
    {
        $disk = Storage::build([
            'driver' => 's3',
            'key' => $accessKey,
            'secret' => $secretKey,
            'region' => $region ? $region : 'us-east-1',
            'bucket' => $bucket,
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => false,
            'throw' => false,
            'visibility' => 'public',
        ]);

        $randomText = Str::random(10);
        $disk->put('test.txt', $randomText);
        $fileURL = $disk->url('test.txt');

        $getFile = file_get_contents($fileURL);
        
        if ($getFile == $randomText) {
            return true;
        }
        else {
            return false;
        }
    }
}