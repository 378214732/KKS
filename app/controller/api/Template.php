<?php

namespace app\controller\api;

use kss\base\BaseController;



/**
 * 测试
 * Class Demo
 * @package app\controller\api
 */
class Template extends BaseController
{


    /**
     * https://new.ezhancn.com/api/template/get_template
     * 获取商品导入模板
     */
    public function getTemplate()
    {
        $filename = 'goods_import.csv';

        //判断是否有模板文件
        $templatePath =  public_path('excel') . $filename;

        $dir = dirname($templatePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }



        //检查文件是否存在

        $talbeTitle = [
            '商品图片',
            '商品名称',
            '商品简称',
            '商品类型（卡密/虚拟/普通）',
            '商品成本',
            '商品面值',
            '销售限价',
            '记账成本',
            '购买起始值（不填默认是1）',
            '购买结束值（不填默认是10）',
            '商品状态（销售/暂停/禁售）',
            '商品分类id',
            '商品下单模板id',
            '商品定价id（为空则为预支定价）',
        ];

        $str = implode(',', $talbeTitle) . "\n";
        file_put_contents($templatePath, $str);

        $data = [[
            'https://tupian.img',
            '默认商品名称',
            '默认商品简称',
            '卡密',
            '1',
            '2',
            '0',
            '1',
            '1',
            '10',
            '暂停',
            '1',
            '1',
            '0',
        ]];

        foreach ($data as $v) {
            L('ExportTableLogic')->exportTableData($templatePath, $v);
        }



        // 返回文件下载
        return download($templatePath, $filename)
            ->header([
                'Content-Type' => 'text/csv;charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
    }

}
