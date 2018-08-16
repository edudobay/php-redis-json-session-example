<?php

class MySessionHandler extends \UMA\RedisSessionHandler
{
    public function read($session_id)
    {
        $json_serialized = parent::read($session_id);
        return \serialize(\json_decode($json_serialized, true));
    }

    public function write($session_id, $session_data)
    {
        error_log('writing: ' . $session_data);
        $json_serialized = \json_encode(\unserialize($session_data));
        error_log('--> ' . $json_serialized);
        return parent::write($session_id, $json_serialized);
    }
}
