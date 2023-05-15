<?php

namespace app\api\lists\customer_service;

use app\api\lists\BaseApiDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\CustomerService\CustomerServiceCate;

class CustomerServiceCateLists extends BaseApiDataLists implements ListsSearchInterface
{
    /**
     * @notes 搜索条件
     * @return \string[][]
     * @author 小黑 Q 713042132
     * @date 2022/9/16 18:54
     */
    public function setSearch(): array
    {
        return [
            '=' => ['name', 'pid']
        ];
    }


    /**
     * @notes 自定查询条件
     * @return array
     * @author 段誉
     * @date 2022/10/25 16:53
     */
    public function queryWhere()
    {

        $where[] = ['pid', '=', 0];
        if (!empty($this->params['name'])) {
            $where[] = ['name', 'like', '%' . $this->params['name'] . '%'];
        }

        return $where;
    }


    /**
     * @notes 获取菜单列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/6/29 16:41
     */
    public function lists(): array
    {
        $lists = CustomerServiceCate::order(['sort' => 'desc', 'id' => 'asc'])
            ->select()
            ->toArray();
        return linear_to_tree($lists, 'children');
    }
    /**
     * @notes 获取文章数量
     * @return int
     * @author 段誉
     * @date 2022/9/16 18:55
     */
    public function count(): int
    {
        return CustomerServiceCate::where($this->searchWhere)
            ->where($this->queryWhere())
            ->count();
    }

}