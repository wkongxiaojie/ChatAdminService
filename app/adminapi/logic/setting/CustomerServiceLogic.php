<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\logic\setting;

use app\common\cache\AdminAuthCache;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\auth\Admin;
use app\common\model\auth\AdminDept;
use app\common\model\auth\AdminJobs;
use app\common\model\auth\AdminRole;
use app\common\model\auth\AdminSession;
use app\common\model\auth\CustomerServiceSession;
use app\common\model\CustomerService\CustomerService;
use app\common\service\ConfigService;
use app\common\service\FileService;
use think\facade\Db;

/**
 * 客服设置逻辑
 * Class CustomerServiceLogic
 * @package app\adminapi\logic\setting
 */
class CustomerServiceLogic extends BaseLogic
{
    /**
     * @notes 获取客服设置
     * @return array
     * @author ljj
     * @date 2022/2/15 12:05 下午
     */
    public static function getConfig()
    {
        $qrCode = ConfigService::get('customer_service', 'qr_code');
        $qrCode = empty($qrCode) ? '' : FileService::getFileUrl($qrCode);
        $config = [
            'qr_code' => $qrCode,
            'wechat' => ConfigService::get('customer_service', 'wechat', ''),
            'phone' => ConfigService::get('customer_service', 'phone', ''),
            'service_time' => ConfigService::get('customer_service', 'service_time', ''),
        ];
        return $config;
    }

    /**
     * @notes 设置客服设置
     * @param $params
     * @author ljj
     * @date 2022/2/15 12:11 下午
     */
    public static function setConfig($params)
    {
        $allowField = ['qr_code', 'wechat', 'phone', 'service_time'];
        foreach ($params as $key => $value) {
            if (in_array($key, $allowField)) {
                if ($key == 'qr_code') {
                    $value = FileService::setFileUrl($value);
                }
                ConfigService::set('customer_service', $key, $value);
            }
        }
    }

    /**
     * @notes 删除管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:45
     */
    public static function delete(array $params): bool
    {
        Db::startTrans();
        try {

            CustomerService::destroy($params['id']);
            $where = [];
            if (is_array($params['id'])) {
                $where[] = ['customer_service_id', 'in', $params['id']];
            } else {
                $where[] = ['customer_service_id', '=', $params['id']];
            }

            //设置token过期
            $tokenArr = CustomerServiceSession::where($where)->select()->toArray();

            foreach ($tokenArr as $token) {
                self::expireToken($token['token']);
            }


            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 过期token
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/12/29 10:46
     */
    public static function expireToken($token): bool
    {
        $CustomerServiceSession = CustomerServiceSession::where('token', '=', $token)
            ->with('CustomerService')
            ->find();

        if (empty($CustomerServiceSession)) {
            return false;
        }

        $time = time();
        $CustomerServiceSession->expire_time = $time;
        $CustomerServiceSession->update_time = $time;
        $CustomerServiceSession->save();

        return (new CustomerServiceTokenCache())->deleteCustomerServiceInfo($token);
    }
}