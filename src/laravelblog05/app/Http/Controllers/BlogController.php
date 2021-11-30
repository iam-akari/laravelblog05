<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
    /**
     * @return view
     */
    public function showList()
    {
       $blogs = Blog::all();
       return view('blog.list',['blogs' => $blogs]);
    }

    /**
     * @param int $id
     * @return view
     */
    public function showDetail($id)
    {
        $blog =  Blog::find($id);
        return view('blog.detail',['blog'=>$blog]);
    }

    /**
     * @return view
     */
    public function showCreate()
    {
        return view('blog.form');
    }

    /**
     * @return view
     */
    public function exeStore(BlogRequest $request)
    {
        $inputs = $request->all();

        \DB::beginTransaction();
        try {
            $blog = Blog::create($inputs);
            \DB::commit();
        } catch (\Throwable $e) {
            abort(500);
            DB::rollback();
        }

        \Session::flash('err_msg','ブログを投稿しました。');
        return redirect(route('blogs'));
        
    }

    /**
     * @param int $id
     * @return view
     */
    public function showEdit($id)
    {
        $blog = Blog::find($id);
        return view('blog.edit',['blog'=>$blog]);
    }

    /**
     * @return view
     */
    public function exeUpdate(BlogRequest $request)
    {
        $inputs = $request->all();
        
        \DB::beginTransaction();
        try {
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content']
            ]);
            $blog->save();
            \DB::commit();
        } catch (\Throwable $e) {
            abort(500);
            \DB::rollback();
        }
        \Session::flash('err_msg','ブログを更新しました。');
        return redirect(route('blogs'));
    }

    /**
     * @return view
     * @param int $id
     */
    public function exeDelete($id)
    {
        if(empty($id)){
            \Session::flash('err_msg','データがありません。');
            return redirect(route('blogs'));
        }

        try {
            Blog::destroy($id);
        } catch (\Throwable $e) {
            about(500);
        }
      
      \Session::flash('err_msg','削除しました。');  
      return redirect(route('blogs'));
    }

}
