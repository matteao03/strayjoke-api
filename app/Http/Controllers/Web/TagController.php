<?php

namespace App\Http\Controllers\Web;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Transformers\TagTransformer;
use App\Transformers\LawyerTransformer;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return $this->response->collection($tags, new TagTransformer());
    }

    public function lawyers(Request $request, Tag $tag)
    {
        $size = $request->query('size') ?: 10;
        $page = $request->query('page') ?: 1;
        $lawyers = $tag->lawyers()
            ->orderBy($request->query('order') ?: 'updated_at', $request->query('sort') ?: 'asc')
            ->paginate($size, ['*'], 'page', $page);
        return $this->response->paginator($lawyers, new LawyerTransformer())->addMeta('tag', $tag->title);
    }
}
