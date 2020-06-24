<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * ---------------------------------------------------------------
     * 设置数据返回开关
     * ---------------------------------------------------------------
     * @var boole
     */
    protected $showSensitiveFields = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        //返回额外参数隐藏字段
        if (!$this->showSensitiveFields) {
            $data['phone'] = substrReplace($this->resource->phone);

            $data['email'] = substrReplace($this->resource->email, '*', 1, 0, '@');
        }

        $data['bound_phone'] = $this->resource->phone ? true : false;

        $data['bound_wechat'] = ($this->resource->weixin_unionid || $this->resource->weixin_openid) ? true : false;

        return $data;
    }

    /**
     * ---------------------------------------------------------------
     * 显示字段开关
     * ---------------------------------------------------------------
     * @return boole
     */
    public function showSensitiveFields()
    {
        $this->showSensitiveFields = true;

        return $this;
    }
}
