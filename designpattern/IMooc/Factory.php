<?php
namespace IMooc;

/**
 * 工厂模式
 *
 * 工厂方法或者类生成对象，而不是在代码中直接new
 */

class Factory
{

    public static function createDatabase($alias = 'DB')
    {
        $db = Database::getInstance();
        Register::set($alias, $db);
        return $db;
    }

}