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


namespace app\common\cache;


use app\common\model\auth\Admin;
use app\common\model\auth\AdminSession;
use app\common\model\auth\CustomerServiceSession;
use app\common\model\auth\SystemRole;
use app\common\model\CustomerService\CustomerService;

/**
 * 管理员token缓存
 * Class AdminTokenCache
 * @package app\common\cache
 */
class CustomerServiceTokenCache extends BaseCache
{

    private $prefix = 'token_customer_service_';

    /**
     * @notes 通过token获取缓存管理员信息
     * @param $token
     * @return false|mixed
     * @author 小黑
     * @date 2023/5/12 16:57
     */
    public function getCustomerServiceInfo($token)
    {
        //直接从缓存获取
        $CustomerServiceInfo = $this->get($this->prefix . $token);
        if ($CustomerServiceInfo) {
            return $CustomerServiceInfo;
        }

        //从数据获取信息被设置缓存(可能后台清除缓存）
        $CustomerServiceInfo = $this->setCustomerServiceInfo($token);
        if ($CustomerServiceInfo) {
            return $CustomerServiceInfo;
        }

        return false;
    }

    /**
     * @notes 通过有效token设置管理信息缓存
     * @param $token
     * @return array|false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 小黑
     * @date 2023/5/12 16:57
     */
    public function setCustomerServiceInfo($token)
    {
        $CustomerServiceSession = CustomerServiceSession::where([['token', '=', $token], ['expire_time', '>', time()]])
            ->find();
        if (empty($CustomerServiceSession)) {
            return [];
        }
        $CustomerService = CustomerService::where('id', '=', $CustomerServiceSession->customer_service_id)
            ->find();




        $CustomerServiceInfo = [
            'customer_service_id' => $CustomerService->id,
            'real_name' => $CustomerService->real_name,
            'nickname' => $CustomerService->nickname,
            'account' => $CustomerService->account,
            'token' => $token,
            'terminal' => $CustomerServiceSession->terminal,
            'expire_time' => $CustomerServiceSession->expire_time,
        ];
        $this->set($this->prefix . $token, $CustomerServiceInfo, new \DateTime(Date('Y-m-d H:i:s', $CustomerServiceSession->expire_time)));
        return $this->getCustomerServiceInfo($token);
    }

    /**
     * @notes 删除缓存
     * @param $token
     * @return bool
     * @author 令狐冲
     * @date 2021/7/3 16:57
     */
    public function deleteCustomerServiceInfo($token)
    {
        return $this->delete($this->prefix . $token);
    }


}