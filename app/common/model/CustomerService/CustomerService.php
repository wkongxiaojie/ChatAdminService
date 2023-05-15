<?php

namespace app\common\model\CustomerService;

use app\common\model\BaseModel;
use app\common\service\FileService;
use think\model\concern\SoftDelete;

class CustomerService extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    

    /**
     * @notes 头像获取器 - 头像路径添加域名
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getAvatarAttr($value)
    {
        return empty($value) ? FileService::getFileUrl(config('project.default_image.user_avatar')) : FileService::getFileUrl(trim($value, '/'));
    }

    /**
     * @notes 最后登录时间
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getLoginTimeAttr($value)
    {

        return $value?$value:"未登录";
    }

    /**
     * @notes 最后登录IP
     * @param $value
     * @return string
     * @author Tab
     * @date 2021/7/13 11:35
     */
    public function getLoginIpAttr($value)
    {
        if ($value == '') {
            return '未登录';
        }
        return $value;
    }
}