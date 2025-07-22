<?php defined('SYSPATH') OR die('No direct access allowed.');

class Buro
{
    public $id;
    public $id_pep;
    public $id_buro;
    public $id_role;
    public $table_po = 'bu_conf';
    public $table_po1 = 'bu_buro' ;
    public $base_po = 'bucfg';

    public function __construct($id = null)
    {
        $this->id = null;
        $this->id_pep = null;
        $this->id_buro = null;
        $this->id_role = null;
        
        if (!is_null($id)) {
            $this->init($id);
        }
    }

    public function init($id = null)
    {
        if (!is_null($id)) {
            $sql = 'SELECT buc.id, buc.id_pep, buc.id_buro, buc.id_role 
                    FROM bu_conf buc
                    WHERE buc.id = '.$id;
            
            $query = DB::query(Database::SELECT, $sql)
                ->execute(Database::instance($this->base_po));
            
            foreach ($query as $key => $value) {
                $this->id = Arr::get($value, 'id');
                $this->id_pep = Arr::get($value, 'id_pep');
                $this->id_buro = Arr::get($value, 'id_buro');
                $this->id_role = Arr::get($value, 'id_role');
            }
        } else {
            $this->id = null;
            $this->id_pep = null;
            $this->id_buro = null;
            $this->id_role = null;
        }
    }

    /** Добавление записи в таблицу bu_conf
     * 
     * @return array Результат выполнения запроса
     */
    public function add()
    {
        $query = DB::insert($this->table_po, array(
            'id_pep', 'id_buro', 'id_role'
        ))
        ->values(array(
            $this->id_pep,
            $this->id_buro,
            $this->id_role
        ));
        
        $result = $query->execute($this->base_po);
        Log::instance()->add(Log::NOTICE, 'Добавление записи в bu_conf '. Debug::vars($this, $result));
        return $result;
    }

    /** Удаление записи из таблицы bu_conf
     * 
     * @return array Результат выполнения запроса
     */
    public function delete()
    {
        $query = DB::delete($this->table_po)
            ->where('id', '=', $this->id);
            
        $result = $query->execute($this->base_po);
        Log::instance()->add(Log::NOTICE, 'Удаление записи из bu_conf '. Debug::vars($this, $result));
        return $result;
    }

    /** Обновление записи в таблице bu_conf
     * 
     * @return array Результат выполнения запроса
     */
    public function update()
    {
        $query = DB::update($this->table_po)
            ->set(array(
                'id_pep' => $this->id_pep,
                'id_buro' => $this->id_buro,
                'id_role' => $this->id_role
            ))
            ->where('id', '=', $this->id);
            
        $result = $query->execute($this->base_po);
        Log::instance()->add(Log::NOTICE, 'Обновление записи в bu_conf '. Debug::vars($this, $result));
        return $result;
    }

    /** Получение всех записей из таблицы bu_conf
     * 
     * @return array Массив записей
     */
    public function get_all()
    {
        $sql = 'SELECT buc.id, buc.id_pep, buc.id_buro, buc.id_role 
                FROM bu_conf buc';
                
        $query = DB::query(Database::SELECT, $sql)
            ->execute(Database::instance($this->base_po));
            
        return $query->as_array();
    }

    public function getBuro(){
        $sql = 'SELECT bub.id, bub.name, bub.information
        FROM bu_buro bub';
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }

    public function getRoles(){
        $sql = 'SELECT bur.id, bur.name FROM bu_roles bur';
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }

    public function getUsersByIdBuro($id_buro){
        $sql = 'SELECT buc.id, buc.id_pep, buc.id_buro, buc.id_role 
        FROM bu_conf buc
        where buc.id_buro='.$id_buro;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }
    public function getIdBuroForUser($id_pep){
        $sql = 'SELECT buc.id, buc.id_pep, buc.id_buro, buc.id_role
        FROM bu_conf buc
        WHERE buc.id_pep='.$id_pep;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }
    public function getBuroById($id_buro){
        $sql = 'SELECT bub.id, bub.name, bub.information
        FROM bu_buro bub
        WHERE bub.id='.$id_buro;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }
    public function getRoleById($id_role){
        $sql = 'SELECT bur.id, bur.name 
        FROM bu_roles bur
        WHERE bur.id='.$id_role;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance($this->base_po));
        return $query->as_array();
    }
    public function addBuro($name, $information)
{
    $query = DB::insert($this->table_po1, array(
        'name', 
        'information'
    ))
    ->values(array(
        $name,
        $information
    ));
    
    $result = $query->execute($this->base_po);
    Log::instance()->add(Log::NOTICE, 'Добавление нового бюро: '.Debug::vars($name, $information, $result));
    return $result;
}

public function deleteBuro($id_buro)
{
    Database::instance($this->base_po)->begin();
    
    try {
        DB::delete($this->table_po)
            ->where('id_buro', '=', $id_buro)
            ->execute($this->base_po);
        
        $result = DB::delete($this->table_po1)
            ->where('id', '=', $id_buro)
            ->execute($this->base_po);
        
        Database::instance($this->base_po)->commit();
        
        Log::instance()->add(Log::NOTICE, 'Удалено бюро ID: '.$id_buro);
        return $result;
    } catch (Exception $e) {
        Database::instance($this->base_po)->rollback();
        Log::instance()->add(Log::ERROR, 'Ошибка удаления бюро: '.$e->getMessage());
        return false;
    }
}

public static function getUserRole($id_pep)
{
    $sql = 'SELECT bur.name 
            FROM bu_roles bur
            JOIN bu_conf buc ON buc.id_role = bur.id
            WHERE buc.id_pep = :id_pep
            LIMIT 1';
    
    $query = DB::query(Database::SELECT, $sql)
        ->param(':id_pep', $id_pep)
        ->execute(Database::instance('bucfg'));
    
    return isset($query[0]['name']) ? $query[0]['name'] : null;
}

public static function getUserRoleId($id_pep)
{
    $sql = 'SELECT buc.id_role 
            FROM bu_conf buc
            WHERE buc.id_pep = :id_pep
            LIMIT 1';
    
    $query = DB::query(Database::SELECT, $sql)
        ->param(':id_pep', $id_pep)
        ->execute(Database::instance('bucfg'));
    
    return isset($query[0]['id_role']) ? (int)$query[0]['id_role'] : null;
}
}