<?php

namespace app\admin\controller\server;

use app\common\controller\Backend;
use app\common\rds\Redis;


/**
 * 公告管理
 *
 * @icon fa fa-circle-o
 */
class Notice extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $version = $this->request->request('version');
            $server = $this->request->request('server');

            if (!$version || !$server) {
                $this->error('先输入版本和服务器进行搜索');
            }
            $redis = new Redis();
            $key = "notices.{$version}.{$server}";
            $res = $redis->instance()->hGetAll($key);
            ksort($res);
            $list = [];
            foreach ($res as $sort => $content) {
                $list[] = [
                    'id' => "{$key}_\$\$_{$sort}",
                    'sort' => $sort,
                    'content' => $content
                ];
            }

            $result = array("total" => count($list), "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $redis = new Redis();
            $request = $this->request->request('row/a');
            if (empty($request['version'])) {
                $this->error("请输入版本号");
            } else if (empty($request['server'])) {
                $this->error("请输入服务器标识");
            } else if (empty($request['server'])) {
                $this->error("请输入序号, 请输入 > 0");
            } else if (empty($request['content'])) {
                $this->error("内容");
            }

            $key = "notices.{$request['version']}.{$request['server']}";
            $field = $request['sort'];
            $res = $redis->instance()->hMSet($key, [$field => $request['content']]);
            if ($res) {
                $this->success();
            } else {
                $this->error($res);
            }
        }
        return parent::add();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
        }
        return;
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }

        [$key, $sort] = explode('_$$_', $ids);
        if (!$key || !$sort) {
            $this->error('参数错误');
        }
        $redis = new Redis();
        $res = $redis->instance()->hDel($key, $sort);
        if ($res) {
            $this->success();
        } else {
            $this->error($res);
        }
    }

}
