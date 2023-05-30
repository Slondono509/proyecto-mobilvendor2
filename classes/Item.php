
<?php

class Item
{
    private $_id;
    private $_code;

    public function setAtributo($atributo, $data)
    {
        $this->$atributo = $data;
    }

    public function getDataCode()
    {
        $record = Connection::getRecord('SELECT * FROM items WHERE code = ?', array($this->_code));
        if ($record) {
            $details = $this->getDetails($record->id);   
            foreach ($details as $value) {
                if ($value->id_items == NULL) {                    
                    $value->TypeOptions = $this->getTypeOptions($value->id_item_types);                    
                }
            }
            return compact('record', 'details');
        } else {
            throw new DatabaseException('No item found');
        }
    }

    public function getDetails($id)
    {
        $query = 'SELECT    id.id AS id_item_details,
                    id.quantity,
                    it.id AS id_item_types,
                    it.name AS name_item_types,
                    i.id AS id_items,
                    i.code,
                    i.name AS name_items,
                    i.unit,
                    i.iva,
                i.price
                FROM       `item_details` id
                INNER JOIN `item_types` it
                ON         id.type_id = it.id
                LEFT JOIN  items i
                ON         id.detail_item_id = i.id
                WHERE      id.parent_item_id = ?';
        return Connection::getQuery($query, array($id))->toArray();
    }

    public function getTypeOptions($id)
    {
        $query = 'SELECT ito.id  AS id_tem_type_options,
                            it.id   AS id_item_types,
                            it.name AS name_item_types,
                            i.id    AS id_items,
                            i.code,
                            i.name  AS name_items,
                            i.unit,
                            i.iva,
                            i.price,
                            CASE 
                            WHEN (ROW_NUMBER() OVER(PARTITION BY ito.type_id)) = 1 THEN 1
                                ELSE 0 
                            END  AS Active
                    FROM   `item_type_options` ito
                            INNER JOIN items i
                                    ON i.id = ito.item_id
                            INNER JOIN item_types it
                                    ON it.id = ito.type_id
                    WHERE  ito.type_id = ?';
        return Connection::getQuery($query, array($id))->toArray();
    }
}
