<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Handlers\ImageUploadHandler;

use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 'except' => ['index', 'show'] —— 对除了 index() 和 show() 以外的方法使用 auth 中间件进行认证
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user, Link $link)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();

        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

    // 参数 TopicRequest $request 注入对应的表单验证类( app/Http/Requests/TopicRequest.php)
	public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题！');
    }

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
         $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '更新成功！');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '成功删除.');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader) {
        // 初始化返回数组 默认：失败
        $data = [
            'success'   => false,
            'msg'       => '上传失败！',
            'file_path' => '',
        ];

        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
               $data['success']     = true;
               $data['msg']         = "上传成功！";
               $data['file_path']   = $result['path'];
            }
        }
        return $data;
    }

}