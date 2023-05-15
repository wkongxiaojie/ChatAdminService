<?php

namespace app\api\controller\customer_service;

use app\api\controller\BaseApiController;
use app\api\lists\customer_service\CustomerServiceCateLists;
use app\api\logic\CustomerServerCateLogic;
use app\api\validate\CustomerServiceCateValidate;


class CustomerServiceCateController extends BaseApiController
{
    public array $notNeedLogin = ['lists', 'cate', 'detail'];
    /**
     * @notes 文章列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 15:30
     */
    public function lists()
    {
        return $this->dataLists(new CustomerServiceCateLists());
    }

    /**
     * @notes 添加客服
     * @return \think\response\Json
     * @author 小黑
     * @date 2023/5/11
     */
    public function add()
    {
        $params = (new CustomerServiceCateValidate())->post()->goCheck('add');


        $result = CustomerServerCateLogic::add($params);

        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServerCateLogic::getError());
    }

    /**
     * @notes 查看管理员详情
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public function detail()
    {
        $params = (new CustomerServiceCateValidate())->goCheck('detail');
        $result = CustomerServerCateLogic::detail($params);
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
        $params = (new CustomerServiceCateValidate())->post()->goCheck('edit');

        $result = CustomerServerCateLogic::edit($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServerCateLogic::getError());
    }
    /**
     * @notes 删除管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 11:03
     */
    public function delete()
    {
        $params = (new CustomerServiceCateValidate())->post()->goCheck('delete');

        $result = CustomerServerCateLogic::delete($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(CustomerServerCateLogic::getError());
    }
}