<?php

namespace App\Services;

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Storage;

class BaseService
{
    /**
     * ---------------------------------------------------------------
     * 二维转一维
     * ---------------------------------------------------------------
     * @param array $arr
     *
     * @return arrar $result
     */
    public function getarray($arr)
    {
        static $result = array();
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                self::getarray($val);
            } else {
                $result[] = $val;
            }
        }
        return $result;
    }

    /**
     * ---------------------------------------------------------------
     * 数据库不为空的值添加默认值
     * ---------------------------------------------------------------
     * @param Model $model 传入的模型数据
     *
     * @return string|bool|int
     */
    public function addNull($model)
    {
        if (empty($model->user_id)) {
            $model->user_id = Admin::user()->id;
        }

        if (empty($model->cate_id)) {
            $model->cate_id = 0;
        }

        if (empty($model->sort)) {
            $model->sort = 0;
        }

        if (empty($model->status)) {
            $model->status = 1;
        }

        if ($model->file_url) {
            if ($model->file_type == config('api.type.file_type')) {
                $model->file_url = $model->file_url;
            } else {
                $icon = json_decode($model->file_url);
                $model->file_url = $icon[0] ?? '';
            }
        }
    }

    /**
     * ---------------------------------------------------------------
     * 编辑上传图片的时候删除旧图片
     * ---------------------------------------------------------------
     * @param Model $fiel
     *
     * @return string|boole
     */
    public function editIcons($file, $icon)
    {
        if (!$file->file_url) {
            $file->file_url = $icon;
        } else {
            Storage::disk('admin')->delete($icon);
        }
    }

    /**
     * ---------------------------------------------------------------
     * 删除数据时，同时删除图片
     * ---------------------------------------------------------------
     * @param Model $file 传入的模型数据
     *
     * @return boole
     */
    public function delIcons($file)
    {
        if ($file->file_url) {
            Storage::disk('admin')->delete($file->file_url);
        }
    }
}
