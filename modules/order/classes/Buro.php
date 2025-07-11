<?php defined('SYSPATH') OR die('No direct access allowed.');

class Buro
{
    private static $roles = [
        1 => 'admin',
        2 => 'arendator',
        3 => 'buro_manager'
    ];

    private static $buros = [
        1 => [
            'buro_name' => '4', 
            'description' => 'улица Годовиков, 9с14'
        ],
        2 => [
            'buro_name' => '10', 
            'description' => 'улица Годовиков, 9с17'
        ],
        3 => [
            'buro_name' => '17', 
            'description' => 'Улица Годовиков, 9с10'
        ]
    ];

    private static $user_mapping = [
        '1' => [
            'buros' => [2],
            'id_role' => 1
        ],
        '7692' => [
            'buros' => [1, 3],
            'id_role' => [2]
        ],
        '1212' => [
            'buros' => [1],
            'id_role' => 2
        ],
    ];

    public function get_id_buro_forUser($user_id){
        $user_id = (string)$user_id;
        $result = [];
        if (isset(self::$user_mapping[$user_id]['buros'])) {
            foreach (self::$user_mapping[$user_id]['buros'] as $buro_id){
                if (isset(self::$buros[$buro_id])){
                    $result[]=[
                        'id_buro'=>$buro_id,
                        //'buro_name'=>self::$buros[$buro_id]['buro_name'],
                        //'description'=>self::$buros[$buro_id]['description']
                    ];
                }
            }
        }
        return $result;
    }


    public static function getRoleInfo($id_role)
    {
        return isset(self::$roles[$id_role]) ? [
            'id_role' => $id_role,
            'name' => self::$roles[$id_role]
        ] : null;
    }

    public static function getUserRole($user_id)
    {
        $user_id = (string)$user_id;
        $role_id = isset(self::$user_mapping[$user_id]);
        
        return self::$user_mapping[$role_id];
    }

    public static function getUserInfo($user_id)
    {
        if (!isset(self::$user_mapping[$user_id])) {
            return null;
        }

        $info = self::$user_mapping[$user_id];
        $buros_info = [];

        foreach ($info['buros'] as $buro_id) {
            if (isset(self::$buros[$buro_id])) {
                $buros_info[] = array_merge(
                    ['id_buro' => $buro_id],
                    self::$buros[$buro_id]
                );
            }
        }

        return [
            'user_id' => $user_id,
            'role' => self::getRoleInfo($info['id_role']),
            'buros' => $buros_info
        ];
    }


    public static function getUserRoleId($user_id)
    {
        return isset(self::$user_mapping[$user_id]) 
            ? self::$user_mapping[$user_id]['id_role'] 
            : null;
    }
    public static function getUserRoleName($user_id)
    {
        $id_role = self::getUserRoleId($user_id);
        return isset(self::$roles[$id_role]) 
            ? self::$roles[$id_role] 
            : null;
    }

    public static function getUserBuros($user_id)
    {
        return isset(self::$user_mapping[$user_id]) 
            ? self::$user_mapping[$user_id]['buros'] 
            : [];
    }
}