<?php defined('SYSPATH') OR die('No direct access allowed.');

class Documents
{
    private static $docs = [
        1 => [
            'id_doc' => 1,
            'docname' => 'паспорт'
        ],
        2 => [
            'id_doc'=> 2,
            'docname' => 'военный билет'
        ],
        3=> [
            'id_doc'=> 3,
            'docname' => 'водительское удостоверение'
        ],
        4 => [
            'id_doc'=> 4,
            'docname' => 'социальная карта москвича'
        ],
        5=> [
            'id_doc'=>5,
            'docname' => 'загран. паспорт'
        ]
    ];

    public static function getDoc()
    {
        return self::$docs;
    }
    public static function getDocById($id){
        return self::$docs[$id];
    }
}