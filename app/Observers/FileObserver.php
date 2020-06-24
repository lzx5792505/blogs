<?php

namespace App\Observers;

use App\Models\File;
use App\Services\FileService;

class FileObserver
{
    /**
     * 监听数据即将更新的事件。
     *
     * @param  File $file
     * @return void
     */
    public function updating(File $file)
    {
        (new FileService())->editFiles($file, $file->getOriginal('file_url'));
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param  File $file
     * @return void
     */
    public function saving(File $file)
    {
        (new FileService())->addNullFiles($file);
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param  File $file
     * @return void
     */
    public function saved(File $file)
    {
        (new FileService())->updateType($file);
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param  File $file
     * @return void
     */
    public function deleting(File $file)
    {
        (new FileService())->delFiles($file);
    }
}
