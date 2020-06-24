<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileService extends BaseService
{
    /**
     * ---------------------------------------------------------------
     * 数据库不为空的值添加默认值
     * ---------------------------------------------------------------
     * @param File $file 传入的模型数据
     *
     * @return int|string|boole
     */
    public function addNullFiles($file)
    {
        parent::addNull($file);
    }

    /**
     * ---------------------------------------------------------------
     * 新增图片
     * ---------------------------------------------------------------
     * @param array $request
     *
     * @return boole
     */
    public function storeFiles($request, $uploader)
    {
        $user = $request->user();
        $file =  new File();
        $size = $request->code == 'avatar' ? 416 : 1024;
        $result = $uploader->save($request->file, $user->id, $size);
        $file->file_url  =  $result['file_url'];
        //判断前端传入
        $file->file_type =  config('api.type.file_type');
        $file->code      = $request->code;
        $file->user_id   = $user->id;
        if ($file->save()) {
            return true;
        }

        return false;
    }

    /**
     * ---------------------------------------------------------------
     * 新增后更改file_type字段 前台后台数据一致性
     * ---------------------------------------------------------------
     * @param File $fiel
     *
     * @return string
     */
    public function updateType($file)
    {
        $file = File::select('id', 'file_type', 'code')->find($file->id);

        if ($file->file_type == config('api.type.file_type') && $file->code == 'avatar') {
            $type = config('api.type.file_type_admin');
        } else {
            $type = $file->file_type;
        }

        DB::table('files')
            ->where('id', $file->id)
            ->update(['file_type' => $type]);
    }

    /**
     * ---------------------------------------------------------------
     * 编辑上传图片的时候 删除旧图片
     * ---------------------------------------------------------------
     * @param File $fiel
     *
     * @return string|boole
     */
    public function editFiles($file, $icon)
    {
        parent::editIcons($file, $icon);
    }

    /**
     * ---------------------------------------------------------------
     * 删除数据时，同时删除图片
     * ---------------------------------------------------------------
     * @param File $file 传入的模型数据
     *
     * @return boole
     */
    public function delFiles($file)
    {
        parent::delIcons($file);
    }
}
