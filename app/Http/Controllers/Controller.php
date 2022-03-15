<?php

namespace App\Http\Controllers;

use App\Http\Requests\GlobalSearchRequest;
use App\Models\Archive;
use App\Models\Author;
use App\Models\Post;
use App\Models\Publication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\ApiResponse\DefaultApiResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, DefaultApiResponse;

    public function globalSearch(GlobalSearchRequest $request)
    {
        $search = $request->search;

        $archives = Archive::globalSearch($search)->get();
        $authors = Author::globalSearch($search)->get();
        $posts = Post::globalSearch($search)->get();
        $publications = Publication::globalSearch($search)->get();

        return $this->apiResponse([
            'archives' => $archives,
            'authors' => $authors,
            'posts' => $posts,
            'publications' => $publications
        ]);
    }
}
