<?php
/**
 * @package		SiteMapGenerator
 * @subpackage          Функции работы с файлами
 * @copyright           Copyright (C) 2013 Romantic-Toys.ru
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */ 

function HLReadFile($file)
{
    $file = fopen($file, "r+");
    return fread($file, 400000);
}

function HLSaveFile($file,$string)
{
    $fp=fopen($file,'a');
    fwrite($fp, $string);
    fclose($fp);
}

function HLMakeFile($file,$string)
{
    $fp=fopen($file,'w');
    fwrite($fp, $string);
    fclose($fp);
}