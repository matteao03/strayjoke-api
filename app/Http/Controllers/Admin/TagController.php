<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Transformers\Admin\TagTransformer;


class TagController extends Controller
{
    public function index(Request $request)
    {
        if ($title = $request->query('title')) {
            $tags = Tag::where('title', 'like', '%' . $title . '%')->paginate($request->query('size'));
        } else {
            $tags = Tag::paginate($request->query('size'));
        }

        return $this->response->paginator($tags, new TagTransformer());
    }


    //创建
    public function store(Request $request)
    {
        Tag::create([
            'title' => $request->title,
            'icon' =>  $request->icon ?: 'document'
        ]);

        return $this->response->noContent();
    }

    //更新
    public function update(Request $request,  Tag $tag)
    {
        $tag->update([
            'title' => $request->title,
            'icon' => $request->icon ?: 'document',

        ]);
        return $this->response->noContent();
    }

    //删除
    public function delete($id)
    {
        Tag::destroy($id);
        return $this->response->noContent();
    }
}
