<?php

namespace app\api\validate;

use app\common\validate\BaseValidate;

class MerchantSettingValidate extends BaseValidate
{

//web_name: '', // 网站名称
//web_favicon: '', // 网站图标
//web_logo: '', // 网站logo
//login_image: '', // 登录页广告图
//customer_service_login_image: '', // 登录页广告图
//auto_reply: 1,
//welcome: '欢迎光临~~~',

    protected $rule = [
        'web_name' => 'require',
        'customer_service_login_image' => 'require',
        'auto_reply' => 'require|in:0,1',
        'welcome' => 'require',
    ];
    protected $message = [
        'web_name.require' => '网站名称必填',
        'customer_service_login_image.require' => '客服登录广告图必填',
        'auto_reply.require' => '自动回复必选',
        'auto_reply.in' => '自动回复参数值错误',
        'welcome.require' => '欢迎语称必填',
    ];
}