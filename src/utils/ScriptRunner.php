<?php

class ScriptRunner
{
    public static function runSqlScript($path) {
        $db = DBConnection::getInstance()->getConnection();
        $sql = file_get_contents($path);
        $db->exec($sql);
    }
}