<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PagesController extends Controller
{
    public function index() {
        $pages = QueryBuilder::for(Page::class)
            ->allowedFilters(
                AllowedFilter::exact('key')
            )
            ->get();

        return $this->apiResponse($pages);
    }

    public function store(Request $request) {
        $pages = $request->all();

        Page::truncate();

        foreach($pages as $key=>$value) {
            Page::create([
                'key' => $key,
                'value' => $value
            ]);
        }

        return $this->apiResponse(null);
    }
}
