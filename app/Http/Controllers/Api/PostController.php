<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
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
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters(
                AllowedFilter::partial('name'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('search', 'global_search'),
            )
            ->allowedIncludes([
                'category'
            ])
            ->allowedSorts(['id', 'name', 'created_at', 'updated_at'])
            ->paginate(request('itemsPerPage'));

        return $this->apiResponse($posts);
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
    public function store(StorePostRequest $request)
    {
        $data = $request->all();

        $bigImage = Storage::disk('public')->put('/posts/', $request->preview_big_image);
        
        $post = Post::create([
            'name' => $data['name'],
            'content' => $data['content'],
            'preview_small_image_path' => "none",
            'preview_big_image_path' => $bigImage,
            'category_id' => $data['category_id']
        ]);

        return $this->apiResponse($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $this->apiResponse($post);
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
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->all();
        
        if (isset($data['preview_big_image'])) {
            Storage::disk('public')->delete($post->preview_big_image_path);
            $bigImage = Storage::disk('public')->put('/posts/', $request->preview_big_image);
            $post->preview_big_image_path = $bigImage;
        }
        
        $post->name = $data['name'];
        $post->content = $data['content'];
        $post->preview_small_image_path = "none";
        $post->category_id = $data['category_id'];
        $post->save();

        return $this->apiResponse($post);
    }

    public function uploadTempImage(Request $request) {
        $file = $request->upload;
        $image = Storage::disk('public')->put("/contentimages/", $file);

        return response()->json([
            'url' => Storage::disk('public')->url($image),
            'uploaded' => 1,
            'filename' => $file->getClientOriginalName()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->apiResponse(null);
    }
}
