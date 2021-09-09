<?php

namespace app\admin\controller\server;

use app\common\controller\Backend;
use app\common\rds\Redis;


/**
 * 资源服管理
 *
 * @icon fa fa-circle-o
 */
class Version extends Backend
{

    const RDS_KEY = "versions";

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
            $redis = new Redis();
            $key = self::RDS_KEY;
            $res = $redis->instance()->hGetAll($key);
            ksort($res);
            $list = [];
            foreach ($res as $field => $content) {
                $list[] = [
                    'id' => $field,
                    'field' => $field,
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
            if (empty($request['field'])) {
                $this->error("请输入版本号.服务器标识");
            } else if (empty($request['content'])) {
                $this->error("内容");
            }

            $key = self::RDS_KEY;
            $field = $request['field'];

            try {
                json_decode($request['content']);
            } catch (\Exception $e) {
                $this->error("JSON解析失败");
            }

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

        if (!$ids) {
            $this->error('参数错误');
        }
        $key = self::RDS_KEY;
        $redis = new Redis();
        $res = $redis->instance()->hDel($key, $ids);
        if ($res) {
            $this->success();
        } else {
            $this->error($res);
        }
    }

}
