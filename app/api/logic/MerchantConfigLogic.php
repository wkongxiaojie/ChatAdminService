<?php

namespace app\api\logic;


use app\common\service\FileService;
use app\common\service\MerchantConfigService;

class MerchantConfigLogic
{
    /**
     * @notes 获取配置
     * @return array
     * @author 段誉
     * @date 2021/12/31 11:03
     */
    public static function getConfig(): array
    {
        $config = [
            // 网站名称
            'web_name' => MerchantConfigService::get('website', 'name', '客服系统'),
            // 网站图标
            'web_favicon' => FileService::getFileUrl(MerchantConfigService::get('website', 'web_favicon')),
            // 网站logo
            'web_logo' => FileService::getFileUrl(MerchantConfigService::get('website', 'web_logo')),
            // 登录页
            'login_image' => FileService::getFileUrl(MerchantConfigService::get('website', 'login_image')),
            'customer_service_login_image' => FileService::getFileUrl(MerchantConfigService::get('customer_service', 'customer_service_login_image')),
            'auto_reply' => MerchantConfigService::get('customer_service', 'auto_reply', 1),
            'welcome' => MerchantConfigService::get('customer_service', 'welcome', '欢迎您'),
            'ad_welcome' => MerchantConfigService::get('customer_service', 'ad_welcome', '欢迎您'),
            // 版权信息
            'copyright' => MerchantConfigService::get('website', 'copyright', '版权信息'),
            'icp' => MerchantConfigService::get('website', 'icp', '粤ICP88888888'),
        ];
        return $config;
    }

    public static function setConfig($params)
    {
        $favicon = FileService::setFileUrl($params['web_favicon']);
        $logo = FileService::setFileUrl($params['web_logo']);
        $login = FileService::setFileUrl($params['login_image']);
        $customer_service_login_image = FileService::setFileUrl($params['customer_service_login_image']);


        MerchantConfigService::set('website', 'web_name', $params['web_name']);
        MerchantConfigService::set('website', 'web_favicon', $favicon);
        MerchantConfigService::set('website', 'web_logo', $logo);
        MerchantConfigService::set('website', 'login_image', $login);
        MerchantConfigService::set('customer_service', 'customer_service_login_image', $customer_service_login_image);
        MerchantConfigService::set('customer_service', 'auto_reply', $params['auto_reply']);
        MerchantConfigService::set('customer_service', 'welcome', $params['welcome']);
        MerchantConfigService::set('customer_service', 'ad_welcome', $params['ad_welcome']);
        MerchantConfigService::set('website', 'icp', $params['icp']);

    }
}