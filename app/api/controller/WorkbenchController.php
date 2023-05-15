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

namespace app\api\controller;


use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 工作台
 * Class WorkbenchCotroller
 * @package app\adminapi\controller
 */
class WorkbenchController extends BaseApiController
{

    /**
     * @notes 工作台
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 17:01
     */
    public function index()
    {
        $result = [

            // 版本信息
            'version' => [
                'version' => config('project.version'),
                'website' => config('project.website.url'),
                'name' => ConfigService::get('website', 'name'),
                'based' => 'vue3.x、ElementUI、MySQL',
                'channel' => [
                    'website' => '/',
                    'gitee' => '/',
                ],
            ],
            // 今日数据
            'today' => [
                'time' => date('Y-m-d H:i:s'),
                // 今日销售额
                'today_sales' => 100,
                // 总销售额
                'total_sales' => 1000,

                // 今日访问量
                'today_visitor' => 10,
                // 总访问量
                'total_visitor' => 100,

                // 今日新增用户量
                'today_new_user' => 30,
                // 总用户量
                'total_new_user' => 3000,

                // 订单量 (笔)
                'order_num' => 12,
                // 总订单量
                'order_sum' => 255
            ],
            // 常用功能
            'menu' => [
                [
                    'name' => '客服管理',
                    'image' => FileService::getFileUrl(config('project.default_image.menu_admin')),
                    'url' => '/kefu'
                ],
            ],
            // 近15日访客数
            'visitor' => self::visitor(),
            // 服务支持
            'support' => self::support()

        ];
        return $this->data($result);
    }

    /**
     * @notes 访问数
     * @return array
     * @author 段誉
     * @date 2021/12/29 16:57
     */
    public static function visitor(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('Y/m/d', $where_start);
            $num[$i] = rand(0, 100);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '访客数', 'data' => $num]
            ]
        ];
    }

    /**
     * @notes 服务支持
     * @return array[]
     * @author 段誉
     * @date 2022/7/18 11:18
     */
    public static function support()
    {
        return [
            [
                'image' => FileService::getFileUrl(config('project.default_image.qq_group')),
                'title' => '官方公众号',
                'desc' => '关注官方公众号',
            ],
            [
                'image' => FileService::getFileUrl(config('project.default_image.customer_service')),
                'title' => '添加企业客服微信',
                'desc' => '想了解更多请添加客服',
            ]
        ];
    }
}