<?php

namespace App\Http\Controllers\Datatable\Content;

use App\Http\Controllers\Controller;
use App\Service\Content\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    /**
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->newsService->getAll();
    }
}
