<?php

namespace app\api\validate;


use app\common\model\CustomerService\CustomerService;
use app\common\model\CustomerService\CustomerServiceCate;
use app\common\validate\BaseValidate;

class CustomerServiceCateValidate extends BaseValidate
{

    protected $rule = [
        'id' => 'require|checkCustomerServiceCate',
        'pid' => 'require',
        'name' => 'require|length:1,16|unique:' . CustomerServiceCate::class,
        'is_show' => 'require|in:0,1',
        'is_disable' => 'require|in:0,1|checkAbleDisable',

    ];

    protected $message = [
        'id.require' => 'id不能为空',
        'pid.require' => '上级分类不能为空',
        'name.require' => '分类名称不能为空',
        'name.length' => '分类名称必须1到16个字符',
        'name.unique' => '分类名称重复',
        'is_show.require' => '请选择是否显示',
        'is_show.in' => '是否显示值错误',
        'is_disable.require' => '请选择状态',
        'is_disable.in' => '状态值错误',

    ];

    /**
     * @notes 添加场景
     * @return CustomerServiceValidate
     * @author 段誉
     * @date 2021/12/29 15:46
     */
    public function sceneAdd(): CustomerServiceCateValidate
    {
        return $this->remove(['password', 'edit'])
            ->remove('id', 'require|checkCustomerServiceCate')
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
            ->remove('real_name', 'require|length:1,16|unique')
            ->remove('account', 'require|length:1,32|unique')
            ->append('id', 'require|checkCustomerServiceCate');
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
    public function checkCustomerServiceCate($value)
    {
        $customerServiceCate = CustomerServiceCate::findOrEmpty($value);
        if ($customerServiceCate->isEmpty()) {
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
        $customer_service_cate = CustomerServiceCate::findOrEmpty($data['id']);
        if ($customer_service_cate->isEmpty()) {
            return '分类不存在';
        }

        if ($value && $customer_service_cate['id'] == 1) {
            return '第一个分类不允许被禁用';
        }
        return true;
    }
}