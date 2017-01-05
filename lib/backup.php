<?php

class backup {
    public static function do($dir = null){

        if(is_null($dir)) $name = "backup_" . date("YmdHis") . ".sql";
        else $name = $dir . "/backup_" . date("YmdHis") . ".sql";

        $backupString = "mysqldump -u " . config::DB_USERNAME . " -p" . config::DB_PASSWD . " "
            . config::DB_NAME . " > " . $name;
        exec($backupString);
    }
}