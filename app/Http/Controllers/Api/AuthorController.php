<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Requests\Author\UploadAuthorsRequest;
use App\Models\Author;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AuthorsImport;


class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = QueryBuilder::for(Author::class)
            ->allowedFilters(
                AllowedFilter::partial('full_name'),
                AllowedFilter::exact('id'),
                AllowedFilter::scope('search', 'global_search'),
            )
            ->allowedSorts(['id', 'full_name', 'created_at', 'updated_at'])
            ->paginate(request('itemsPerPage'));

        return $this->apiResponse($authors);
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
    public function store(StoreAuthorRequest $request)
    {
        $data = $request->all();

        $author = Author::create($data);

        return $this->apiResponse($author);
    }

    /**
     * Display the specified resource.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return $this->apiResponse($author);
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
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $data = $request->all();

        $author->full_name = $data['full_name'];
        $author->about = $data['about'];
        $author->articles = $data['articles'];
        $author->save();

        return $this->apiResponse($author);
    }

    public function uploadAuthorsFromExcel(UploadAuthorsRequest $request) {
        $file = Storage::disk('public')->put('/authors-excel/', $request->file);

        Author::truncate();

        $collection = Excel::import(new AuthorsImport, $file, 'public');
        
        return $this->apiResponse($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return $this->apiResponse(null);
    }
}
