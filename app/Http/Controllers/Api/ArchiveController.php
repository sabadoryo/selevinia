<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Archive\StoreArchiveRequest;
use App\Http\Requests\Archive\UpdateArchiveRequest;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store','update','edit','create', 'delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archives = QueryBuilder::for(Archive::class)
            ->allowedFilters(
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::scope('search', 'global_search'),
                AllowedFilter::exact('year')
            )
            ->allowedSorts(['id', 'title', 'created_at', 'updated_at', 'year'])
            ->paginate(request('itemsPerPage') ?? 0);

        return $this->apiResponse($archives);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArchiveRequest $request)
    {
        $data = $request->all();

        $documentPath = Storage::disk('public')->put('/archives_files/', $request->document);
        $bigImage = Storage::disk('public')->put('/archives_images/', $request->preview_big_image);
        
        $archive = new Archive();

        $archive->preview_big_image_path = $bigImage;
        $archive->preview_small_image_path = $bigImage;
        $archive->document_path  = $documentPath;
        $archive->original_document_name = $request->document->getClientOriginalName();
        $archive->title = $data['title'];
        $archive->year = (int)$data['year'];
        $archive->tome = (int)$data['tome'];
        $archive->description = $data['description'];
        $archive->save();

        return $this->apiResponse($archive);
    }

    /**
     * Display the specified resource.
     *
     * @param  Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function show(Archive $archive)
    {
        return $this->apiResponse($archive);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArchiveRequest $request, Archive $archive)
    {
        $data = $request->all();

        $documentPath = Storage::disk('public')->put('/archives_files/', $request->document);

        if (isset($data['preview_big_image'])) {
            Storage::disk('public')->delete($archive->preview_big_image_path);
            $bigImage = Storage::disk('public')->put('/archives_images/', $request->preview_big_image);
            $archive->preview_big_image_path = $bigImage;
        }

        $archive->document_path  = $documentPath;
        $archive->title = $data['title'];
        $archive->year = (int)$data['year'];
        $archive->tome = (int)$data['tome'];
        $archive->description = $data['description'];
        $archive->original_document_name = $request->document->getClientOriginalName();
        $archive->save();

        return $this->apiResponse($archive);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archive $archive)
    {
        Storage::disk('public')->delete($archive->document_path);
        Storage::disk('public')->delete($archive->preview_big_image_path);

        $archive->delete();

        return $this->apiResponse(null);
    }

    public function downloadDocument(Archive $archive) {
        $pdf = Storage::disk('public')->path($archive->document_path);

        return response()->download($pdf, $archive->original_document_name);
    }
}
