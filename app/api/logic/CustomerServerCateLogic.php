<?php

namespace app\api\logic;


use app\common\logic\BaseLogic;

use app\common\model\CustomerService\CustomerServiceCate;
use think\facade\Db;

class CustomerServerCateLogic extends BaseLogic
{
    /**
     * @notes 添加管理员
     * @param array $params
     * @author 段誉
     * @date 2021/12/29 10:23
     */
    public static function add(array $params): bool
    {

        Db::startTrans();
        try {

            CustomerServiceCate::create([
                'pid' => $params['pid'],
                'name' => $params['name'],
                'create_time' => time(),
                'is_disable' => $params['is_disable'],
                'is_show' => $params['is_show'],
                'sort' => $params['sort'],

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
     * @notes 查看客服分类详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public static function detail($params, $action = 'detail'): array
    {
        $customer_service_cate = CustomerServiceCate::findOrEmpty($params['id'])->toArray();

        if ($action == 'detail') {
            return $customer_service_cate;
        }

        $result['customer_service_cate'] = $customer_service_cate;


        return $result;
    }

    /**
     * @notes 编辑分类
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
                'name' => $params['name'],
                'pid' => $params['pid'],
                'is_disable' => $params['is_disable'],
                'is_show' => $params['is_show']
            ];

            CustomerServiceCate::update($data);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
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
            CustomerServiceCate::destroy($params['id']);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

}