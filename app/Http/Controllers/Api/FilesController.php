<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Queries\FileQuery;
use App\Handlers\FileUploadHandler;
use App\Http\Resources\FileResource;
use App\Http\Requests\Api\FileRequest;

class FilesController extends Controller
{

    /**
     * ---------------------------------------------------------------
     * 获取图片列表
     * ---------------------------------------------------------------
     * @param Http $request
     * @param string File $files 模型数据
     * @param string $code 分类标识
     *
     * @return array
     */
    public function index(Request $request, FileQuery $query)
    {
        $code = $request->input('code');

        if ($code) {
            $query->where('code', $code);
        }

        $file = $query
            ->select('file_name', 'file_url', 'user_id', 'cate_id', 'sort', 'code', 'file_type', 'Keywords', 'describe')
            ->paginate();

        return FileResource::collection($file);
    }

    /**
     * ---------------------------------------------------------------
     * 新增图片
     * ---------------------------------------------------------------
     * @param array    $request  验证后的数据
     * @param string   $uploader 上传文件
     *
     * @return json
     */
    public function store(FileRequest $request, FileUploadHandler $uploader, FileService $service)
    {
        $file = $service->storeFiles($request, $uploader);

        if (!$file) {
            abort(500, '创建图片失败');
        }

        return response()->json([
            'message' => 'Successfully created file!'
        ], 201);
    }
}
