<?php

namespace app\api\logic;


use app\common\cache\CustomerServiceTokenCache;
use app\common\logic\BaseLogic;
use app\common\model\auth\CustomerServiceSession;
use app\common\model\CustomerService\CustomerService;

use app\common\service\FileService;
use think\facade\Config;
use think\facade\Db;

class CustomerServerLogic extends BaseLogic
{
    /**
     * @notes 添加客服
     * @param array $params
     * @author 段誉
     * @date 2021/12/29 10:23
     */
    public static function add(array $params): bool
    {

        Db::startTrans();
        try {
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $defaultAvatar = config('project.default_image.user_avatar');

            $avatar = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : $defaultAvatar;
            $result = CustomerService::create([
                'sn' => self::createCustomerServiceSn(),
                'nickname' => $params['nickname'],
                'mobile' => $params['mobile'],
                'real_name' => $params['real_name'],
                'account' => $params['account'],
                'avatar' => $avatar,
                'password' => $password,
                'create_time' => time(),
                'is_disable' => $params['is_disable'],
                'user_id' => $params['user_id'],
            ]);


            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 生成客服编码
     * @param string $prefix
     * @param int $length
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 10:33
     */
    public static function createCustomerServiceSn(string $prefix = '', int $length = 8): string
    {
        $rand_str = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_str .= mt_rand(0, 9);
        }
        $sn = $prefix . $rand_str;

        if (CustomerService::where(['sn' => $sn])->count() > 0) {
            return self::createCustomerServiceSn($prefix, $length);
        }
        return $sn;
    }

    /**
     * @notes 查看管理员详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public static function detail($params, $action = 'detail'): array
    {
        $customer_service = CustomerService::field([
            'id', 'account', 'real_name', 'mobile', 'is_disable', 'nickname',
            'avatar',
        ])->findOrEmpty($params['id'])->toArray();

        if ($action == 'detail') {
            return $customer_service;
        }

        $result['user'] = $customer_service;


        return $result;
    }

    /**
     * @notes 编辑管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:43
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            // 基础信息
            $data = [
                'id' => $params['id'],
                'nickname' => $params['nickname'],
                'mobile' => $params['mobile'],
                'is_disable' => $params['is_disable']
            ];

            // 头像
            $data['avatar'] = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : '';

            // 密码
            if (!empty($params['password'])) {
                $passwordSalt = Config::get('project.unique_identification');
                $data['password'] = create_password($params['password'], $passwordSalt);
            }


            if ($params['is_disable'] == 1) {

                $tokenArr = CustomerServiceSession::where('customer_service_id', $params['id'])->select()->toArray();

                foreach ($tokenArr as $token) {
                    self::expireToken($token['token']);
                }


            }

            CustomerService::update($data);

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