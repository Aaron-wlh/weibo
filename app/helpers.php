<?php

function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));

        return $db_config = [
            'connection' => 'pgsql',
            'host' => $url["host"],
            'database'  => substr($url["path"], 1),
            'username'  => $url["user"],
            'password'  => $url["pass"],
        ];
    } else {
        return $db_config = [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
        ];
    }
}

// 快捷方法: 查看数据
function d(...$data)
{
    foreach ($data as $item) {
        $result =  print_r($item, true);

        $style = 'border:1px solid #ccc;border-radius: 5px;';
        $style .= 'background: #efefef; padding:8px;';
        printf('<pre style="%s">%s</pre>', $style, $result);
    }
}
