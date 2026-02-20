<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        // Tags are global reference data — no user scoping needed
        // Paginated to protect against unbounded result sets
        return TagResource::collection(Tag::orderBy('name')->paginate(50));
    }
}