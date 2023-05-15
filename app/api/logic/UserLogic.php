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

namespace app\api\logic;


use app\common\{enum\notice\NoticeEnum,
    enum\user\UserTerminalEnum,
    enum\YesNoEnum,
    logic\BaseLogic,
    model\user\User,
    model\user\UserAuth,
    service\sms\SmsDriver,
    service\wechat\WeChatMnpService
};
use think\facade\Config;

/**
 * 会员逻辑层
 * Class UserLogic
 * @package app\shopapi\logic
 */
class UserLogic extends BaseLogic
{

    /**
     * @notes 个人中心
     * @param array $userInfo
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 18:04
     */
    public static function center(array $userInfo): array
    {
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,nickname,real_name,avatar,mobile,create_time,is_new_user,user_money,password')
            ->findOrEmpty();

        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth = UserAuth::where(['user_id' => $userInfo['user_id'], 'terminal' => $userInfo['terminal']])->find();
            $user['is_auth'] = $auth ? YesNoEnum::YES : YesNoEnum::NO;
        }

        $user['has_password'] = !empty($user['password']);
        $user->hidden(['password']);
        return $user->toArray();
    }


    /**
     * @notes 个人信息
     * @param $userId
     * @return array
     * @author 段誉
     * @date 2022/9/20 19:45
     */
    public static function info(int $userId)
    {
        $user = User::where(['id' => $userId])
            ->field('id,sn,sex,account,password,nickname,real_name,avatar,mobile,create_time,user_money')
            ->findOrEmpty();
        $user['has_password'] = !empty($user['password']);
        $user['has_auth'] = self::hasWechatAuth($userId);
        $user['version'] = config('project.version');
        $menu = [
            [
                'component' => "workbench/index",
                'create_time' => "2022-07-01 16:39:53",
                'icon' => "el-icon-Monitor",
                'id' => 1,
                'is_cache' => 0,
                'is_disable' => 0,
                'is_show' => 1,
                'name' => "工作台",
                'params' => "",
                'paths' => "workbench",
                'perms' => "workbench/index",
                'pid' => 0,
                'selected' => "",
                'sort' => 1000,
                'type' => "C",
                'update_time' => "2022-09-28 16:49:41"
            ],
            [
                'component' => "",
                'create_time' => "2022-09-23 11:39:11",
                'icon' => "el-icon-User",
                'id' => 2,
                'is_cache' => 0,
                'is_disable' => 0,
                'is_show' => 1,
                'name' => "客服管理",
                'params' => "",
                'paths' => "customer_service",
                'perms' => "",
                'pid' => 0,
                'selected' => "",
                'sort' => 800,
                'type' => "M",
                'update_time' => "2022-09-28 16:45:32",
                'children' => [
                    [
                        'component' => "customer_service/lists/index",
                        'create_time' => "2022-09-23 11:39:52",
                        'icon' => "local-icon-user_guanli",
                        'id' => 3,
                        'is_cache' => 0,
                        'is_disable' => 0,
                        'is_show' => 1,
                        'name' => "客服列表",
                        'params' => "",
                        'paths' => "lists",
                        'perms' => "",
                        'pid' => 2,
                        'selected' => "",
                        'sort' => 0,
                        'type' => "C",
                        'update_time' => "2022-09-28 16:51:32"
                    ],
                    [
                        'component' => "customer_service/cate/index",
                        'create_time' => "2022-09-23 11:39:52",
                        'icon' => "local-icon-user_guanli",
                        'id' => 4,
                        'is_cache' => 0,
                        'is_disable' => 0,
                        'is_show' => 1,
                        'name' => "客服分类",
                        'params' => "",
                        'paths' => "cates",
                        'perms' => "",
                        'pid' => 2,
                        'selected' => "",
                        'sort' => 0,
                        'type' => "C",
                        'update_time' => "2022-09-28 16:51:32"
                    ]
                ]
            ],
            [
                'component' => "setting/merchant/index",
                'create_time' => "2022-07-01 16:39:53",
                'icon' => "el-icon-Monitor",
                'id' => 5,
                'is_cache' => 0,
                'is_disable' => 0,
                'is_show' => 1,
                'name' => "系统设置",
                'params' => "",
                'paths' => "setting",
                'perms' => "",
                'pid' => 0,
                'selected' => "",
                'sort' => 1000,
                'type' => "C",
                'update_time' => "2022-09-28 16:49:41"
            ],
        ];
        $user->hidden(['password']);
        $data = ['user' => $user, 'menu' => $menu];
        return $data;
    }


    /**
     * @notes 设置用户信息
     * @param int $userId
     * @param array $params
     * @return User|false
     * @author 段誉
     * @date 2022/9/21 16:53
     */
    public static function setInfo(int $userId, array $params)
    {
        try {
            return User::update([
                    'id' => $userId,
                    $params['field'] => $params['value']]
            );
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 是否有微信授权信息
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:36
     */
    public static function hasWechatAuth(int $userId)
    {
        //是否有微信授权登录
        $terminal = [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA, UserTerminalEnum::PC];
        $auth = UserAuth::where(['user_id' => $userId])
            ->whereIn('terminal', $terminal)
            ->findOrEmpty();
        return !$auth->isEmpty();
    }


    /**
     * @notes 重置登录密码
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/16 18:06
     */
    public static function resetPassword(array $params)
    {
        try {
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['mobile'], $params['code'], NoticeEnum::FIND_LOGIN_PASSWORD_CAPTCHA)) {
                throw new \Exception('验证码错误');
            }

            // 重置密码
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);

            // 更新
            User::where('mobile', $params['mobile'])->update([
                'password' => $password
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 修稿密码
     * @param $params
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:13
     */
    public static function changePassword(array $params, int $userId)
    {
        try {
            $user = User::findOrEmpty($userId);
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            // 密码盐
            $passwordSalt = Config::get('project.unique_identification');

            if (!empty($user['password'])) {
                if (empty($params['old_password'])) {
                    throw new \Exception('请填写旧密码');
                }
                $oldPassword = create_password($params['old_password'], $passwordSalt);
                if ($oldPassword != $user['password']) {
                    throw new \Exception('原密码不正确');
                }
            }

            // 保存密码
            $password = create_password($params['password'], $passwordSalt);
            $user->password = $password;
            $user->save();

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 获取小程序手机号
     * @param array $params
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 11:49
     */
    public static function getMobileByMnp(array $params)
    {
        try {
            $response = (new WeChatMnpService())->getUserPhoneNumber($params['code']);
            $phoneNumber = $response['phone_info']['purePhoneNumber'] ?? '';
            if (empty($phoneNumber)) {
                throw new \Exception('获取手机号码失败');
            }

            $user = User::where([
                ['mobile', '=', $phoneNumber],
                ['id', '<>', $params['user_id']]
            ])->findOrEmpty();

            if (!$user->isEmpty()) {
                throw new \Exception('手机号已被其他账号绑定');
            }

            // 绑定手机号
            User::update([
                'id' => $params['user_id'],
                'mobile' => $phoneNumber
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 绑定手机号
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/21 17:28
     */
    public static function bindMobile(array $params)
    {
        try {
            // 变更手机号场景
            $sceneId = NoticeEnum::CHANGE_MOBILE_CAPTCHA;
            $where = [
                ['id', '=', $params['user_id']],
                ['mobile', '=', $params['mobile']]
            ];

            // 绑定手机号场景
            if ($params['type'] == 'bind') {
                $sceneId = NoticeEnum::BIND_MOBILE_CAPTCHA;
                $where = [
                    ['mobile', '=', $params['mobile']]
                ];
            }

            // 校验短信
            $checkSmsCode = (new SmsDriver())->verify($params['mobile'], $params['code'], $sceneId);
            if (!$checkSmsCode) {
                throw new \Exception('验证码错误');
            }

            $user = User::where($where)->findOrEmpty();
            if (!$user->isEmpty()) {
                throw new \Exception('该手机号已被使用');
            }

            User::update([
                'id' => $params['user_id'],
                'mobile' => $params['mobile'],
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

}