<?php
//Fait le lien entre la BDD et le code
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class ShoeTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->table = $this->tableGateway->table;
    }

    public function fetchAll()
    {
        	$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'color'), $this->table . '.id_color= c.id', ['name_color'=>'name'], 'left');
			$select->join(array('m' => 'manufacturer'), $this->table . '.id_manufacturer= m.id', ['name_manufacturer'=>'name'], 
			'left');
		});
		return $resultSet;
    }

    public function getShoe($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveShoe(Shoe $shoe)
    {
        $data = [
            'name' => $shoe->name,
            'id_color'  => $shoe->id_color,
            'id_manufacturer'  => $shoe->id_manufacturer,
			'shoe_img' => $shoe->shoe_img,
        ];

        $id = (int) $shoe->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getShoe($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update shoe with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteShoe($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}






