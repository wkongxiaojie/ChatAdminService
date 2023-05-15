<?php

namespace app\api\validate;


use app\common\model\CustomerService\CustomerService;
use app\common\validate\BaseValidate;

class CustomerServiceValidate extends BaseValidate
{

    protected $rule = [
        'id' => 'require|checkCustomerService',
        'mobile' => 'require|mobile|unique:customer_service,mobile',
        'account' => 'require|length:1,32|unique:' . CustomerService::class,
        'nickname' => 'require|length:1,16|unique:' . CustomerService::class,
        'real_name' => 'require|length:1,16|unique:' . CustomerService::class,
        'password' => 'require|length:6,32|edit',
        'password_confirm' => 'requireWith:password|confirm',
        'is_disable' => 'require|in:0,1|checkAbleDisable',

    ];

    protected $message = [
        'id.require' => 'id不能为空',
        'account.require' => '账号不能为空',
        'account.length' => '账号长度须在1-32位字符',
        'account.unique' => '账号已存在',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度须在6-32位字符',
        'password_confirm.requireWith' => '确认密码不能为空',
        'password_confirm.confirm' => '两次输入的密码不一致',
        'mobile.unique' => '手机号已被绑定',
        'mobile.require' => '手机号必填',
        'mobile.mobile' => '手机号格式不正确',
        'nickname.require' => '昵称不能为空',
        'nickname.length' => '昵称须在1-16位字符',
        'nickname.unique' => '昵称已存在',
        'real_name.require' => '姓名不能为空',
        'real_name.length' => '姓名须在1-16位字符',
        'real_name.unique' => '姓名已存在',
        'is_disable.require' => '请选择状态',
        'is_disable.in' => '状态值错误',

    ];

    /**
     * @notes 添加场景
     * @return CustomerServiceValidate
     * @author 段誉
     * @date 2021/12/29 15:46
     */
    public function sceneAdd(): CustomerServiceValidate
    {
        return $this->remove(['password', 'edit'])
            ->remove('id', 'require|checkCustomerService')
            ->remove('is_disable', 'checkAbleDisable');
    }

    /**
     * @notes 详情场景
     * @return CustomerServiceValidate
     * @author 段誉
     * @date 2021/12/29 15:46
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 编辑场景
     * @return CustomerServiceValidate
     * @author 段誉
     * @date 2021/12/29 15:47
     */
    public function sceneEdit()
    {
        return $this->remove('password', 'require|length')
            ->remove('real_name','require|length:1,16|unique')
            ->remove('account','require|length:1,32|unique')
            ->append('id', 'require|checkCustomerService');
    }


    /**
     * @notes 删除场景
     * @return CustomerServiceValidate
     * @author 段誉
     * @date 2021/12/29 15:47
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 编辑情况下，检查是否填密码
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2021/12/29 10:19
     */
    public function edit($value, $rule, $data)
    {
        if (empty($data['password']) && empty($data['password_confirm'])) {
            return true;
        }
        $len = strlen($value);
        if ($len < 6 || $len > 32) {
            return '密码长度须在6-32位字符';
        }
        return true;
    }


    /**
     * @notes 检查指定客服是否存在
     * @param $value
     * @return bool|string
     * @author 段誉
     * @date 2021/12/29 10:19
     */
    public function checkCustomerService($value)
    {
        $customerService = CustomerService::findOrEmpty($value);
        if ($customerService->isEmpty()) {
            return '客服不存在';
        }
        return true;
    }


    /**
     * @notes 禁用校验
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author 段誉
     * @date 2022/8/11 9:59
     */
    public function checkAbleDisable($value, $rule, $data)
    {
        $customer_service = CustomerService::findOrEmpty($data['id']);
        if ($customer_service->isEmpty()) {
            return '客服不存在';
        }

        if ($value && $customer_service['id'] == 1) {
            return '第一个账号不允许被禁用';
        }
        return true;
    }
}