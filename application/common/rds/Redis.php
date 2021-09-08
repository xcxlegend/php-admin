<?php

namespace app\common\rds;
class Redis
{
    protected $options;
    public $redis;

    public function instance() {
        return $this->redis;
    }

    public function __construct($options = [])
    {
        $this->options = config("redis");
        if (!extension_loaded('redis')) {
            throw new Exception('redis扩展未安装');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $func        = isset($this->options['persistent']) ? 'pconnect' : 'connect';
        $this->redis = new \Redis;
        $this->redis->$func($this->options['host'], $this->options['port']);

        if ( isset($this->options['password']) &&  '' != $this->options['password']) {
            $this->redis->auth($this->options['password']);
        }

        if (0 != $this->options['select']) {
            $this->redis->select($this->options['select']);
        }
    }

}