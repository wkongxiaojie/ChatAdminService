<?php

namespace app\api\controller\customer_service;


use app\adminapi\logic\setting\CustomerServiceLogic;
use app\api\controller\BaseApiController;
use app\api\lists\customer_service\CustomerServiceLists;
use app\api\logic\CustomerServerLogic;
use app\api\validate\CustomerServiceValidate;
use app\common\model\CustomerService\CustomerService;


class CustomerServiceController extends BaseApiController
{
    public array $notNeedLogin = ['lists', 'cate', 'detail'];


    /**
     * @notes 客服列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 15:30
     */
    public function lists()
    {
        return $this->dataLists(new CustomerServiceLists());
    }

    /**
     * @notes 添加客服
     * @return \think\response\Json
     * @author 小黑
     * @date 2023/5/11
     */
    public function add()
    {
        $params = (new CustomerServiceValidate())->post()->goCheck('add');
        $userInfo = $this->userInfo;
        $params['user_id'] = $this->userId;
        $user_customer_service_count = CustomerService::where('user_id', $this->userId)->count();
        if ($user_customer_service_count > $userInfo['customer_service_count']) {
            return $this->fail('你最多拥有' . $userInfo['customer_service_count'] . '个客服,如有需要请购买~~');
        }
        $result = CustomerServerLogic::add($params);

        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServerLogic::getError());
    }
    /**
     * @notes 查看管理员详情
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public function detail()
    {
        $params = (new CustomerServiceValidate())->goCheck('detail');
        $result = CustomerServerLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 编辑管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:03
     */
    public function edit()
    {
        $params = (new CustomerServiceValidate())->post()->goCheck('edit');

        $result = CustomerServerLogic::edit($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServerLogic::getError());
    }

    /**
     * @notes 删除管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:03
     */
    public function delete()
    {
        $params = (new CustomerServiceValidate())->post()->goCheck('delete');

        $result = CustomerServiceLogic::delete($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServiceLogic::getError());
    }

}