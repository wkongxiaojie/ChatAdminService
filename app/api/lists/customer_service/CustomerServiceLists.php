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

namespace app\api\lists\customer_service;

use app\api\lists\BaseApiDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\CustomerService\CustomerService;
use app\common\model\CustomerService\CustomerServiceCollect;


/**
 * 文章列表
 * Class CustomerServiceLists
 * @package app\api\lists\kefu
 */
class CustomerServiceLists extends BaseApiDataLists implements ListsSearchInterface
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
            '=' => ['cid', 'account']
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

        $where[] = ['user_id', '=', $this->userId];
        if (!empty($this->params['nickname'])) {
            $where[] = ['nickname', 'like', '%' . $this->params['nickname'] . '%'];
        }

        if (!empty($this->params['mobile'])) {
            $where[] = ['mobile', 'like', '%' . $this->params['mobile'] . '%'];
        }
        if (!empty($this->params['account'])) {
            $where[] = ['account', 'like', '%' . $this->params['account'] . '%'];
        }
        if (!empty($this->params['is_disable'])) {
            $where[] = ['is_disable', '=', $this->params['is_disable']];
        } else {
            $where[] = ['is_disable', '=', 0];
        }

        return $where;
    }


    /**
     * @notes 获取文章列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 18:55
     */
    public function lists(): array
    {
        $orderRaw = 'sort desc, id asc';


        $field = 'id,cid,account,mobile,nickname,avatar,is_disable,login_time,login_ip,create_time';
        return CustomerService::field($field)
            ->where($this->queryWhere())
            ->where($this->searchWhere)
            ->orderRaw($orderRaw)
            ->limit($this->limitOffset, $this->limitLength)
            ->select()->toArray();


    }


    /**
     * @notes 获取文章数量
     * @return int
     * @author 段誉
     * @date 2022/9/16 18:55
     */
    public function count(): int
    {
        return CustomerService::where($this->searchWhere)
            ->where($this->queryWhere())
            ->count();
    }
}