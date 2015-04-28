<?php

class Repository
{
    private $db = null;
    
    
	public function __construct()
	{
		$this->db = Application::$app->getDB();
    }
	
    
    public function find($class, $table, $id)
	{
		$row = $this->db->selectOne(
			$table,
			'id = :id',
			[':id' => $id]
		);
		
        return $this->createObject($class, $row);
	}
    
    
    public function createObject($class, $properties = [])
    {
		$object = new $class();
        if($properties) {
            $object->setProperties($properties);
        }
        
        return $object;
    }
    
    
    public function save($table, &$object)
    {
        $properties = $object->getProperties();
        
        foreach ($properties as $field => $value)
        {
            $where[] = "$field = :$field";
            $bind[":$field"] = $value;
        }
        
        if ($properties['id'] == 0) {
            $this->db->insert(
                $table,
                $properties
            );
            $insert_id = $this->db->lastInsertId();
            $object->id = $insert_id;
        } else {
            $this->db->update(
                $table,
                $properties,
                'id = :id',
                [':id' => $properties['id']]
            );
        }
        
		return $res;
    }
    
    public function filter($table, $filter, $limit = 0, $offset = 0, $fields = '*')
	{
        $where = [];
        $bind = [];
        foreach ($filter as $field => $value)
        {
            $where[] = "$field = :$field";
            $bind[":$field"] = $value;
        }
        
		$rows = $this->db->select(
			$table,
			implode(' AND ', $where),
			$bind,
            $fields,
            ($offset || $limit ? 'LIMIT '.(int)$offset.', '.(int)$limit : '')
		);
        return $rows;
	}
}