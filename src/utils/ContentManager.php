<?php
require_once __DIR__ . '/../config/Config.php';

class ContentManager
{
    public static function getPage() {
        global $table_query;

        return call_user_func("{$table_query}::getPage");
    }


}