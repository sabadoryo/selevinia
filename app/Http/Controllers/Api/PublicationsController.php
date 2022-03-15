<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StorePublicationRequest;
use App\Http\Requests\Publication\UpdatePublicationRequest;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PublicationsController extends Controller
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
        $publications = QueryBuilder::for(Publication::class)
            ->allowedFilters(
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::scope('search', 'global_search')
            )
            ->allowedSorts(['id', 'title', 'created_at', 'updated_at'])
            ->paginate(request('itemsPerPage') ?? 0);

        return $this->apiResponse($publications);
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
    public function store(StorePublicationRequest $request)
    {
        $data = $request->all();

        $documentPath = Storage::disk('public')->put('/publications_files/', $request->document);
        $bigImage = Storage::disk('public')->put('/publications_images/', $request->image);
        
        $publication = new Publication();

        $publication->image_path = $bigImage;
        $publication->document_path  = $documentPath;
        $publication->title = $data['title'];
        $publication->description = $data['description'];
        $publication->save();

        return $this->apiResponse($publication);
    }

    /**
     * Display the specified resource.
     *
     * @param  Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        return $this->apiResponse($publication);
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
     * @param  Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePublicationRequest $request, Publication $publication)
    {
        $data = $request->all();

        $documentPath = Storage::disk('public')->put('/publication_files/', $request->document);

        if (isset($data['image'])) {
            Storage::disk('public')->delete($publication->image_path);
            $bigImage = Storage::disk('public')->put('/publications_images/', $request->image);
            $publication->image_path = $bigImage;
        }

        $publication->document_path  = $documentPath;
        $publication->title = $data['title'];
        $publication->description = $data['description'];
        $publication->save();

        return $this->apiResponse($publication);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        Storage::disk('public')->delete($publication->document_path);
        Storage::disk('public')->delete($publication->image_path);

        $publication->delete();

        return $this->apiResponse(null);
    }

    public function downloadDocument(Publication $publication) {
        $pdf = Storage::disk('public')->path($publication->document_path);

        return response()->download($pdf);
    }
}
