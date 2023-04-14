<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class ProductTable
{
	private $id;
	private $limit;
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->table = $this->tableGateway->table;
    }

    public function fetchAll($active = '')
    {
		$this->active = $active;
		
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'color'), $this->table . '.id_color= c.id', ['name_color'=>'name'], 'left');
			$select->join(array('m' => 'manufacturer'), $this->table . '.id_manufacturer= m.id', ['name_manufacturer'=>'name'], 
			'left');
			$select->join(array('y' => 'year'), $this->table . '.id_year= y.id', ['name_year'=>'name'], 
			'left');
			$select->join(array('a' => 'author'), $this->table . '.id_author= a.id', ['name_author'=>'name'], 
			'left');
			$select->join(array('ca' => 'category'), $this->table . '.id_category= ca.id', ['name_category'=>'name'], 
			'left');
			
			if(!empty($this->active)) {
				$select->where->like('active', $this->active);	
			}
			
			
		});
		return $resultSet;
        //return $this->tableGateway->select();
    }
	
	public function fetchAllLimit($offset = 0, $limit = 3, $order = "id")
    {
		$this->offset = $offset;
		$this->limit = $limit;
		$this->order = $order;
		
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'color'), $this->table . '.id_color= c.id', ['name_color'=>'name'], 'left');
			$select->join(array('m' => 'manufacturer'), $this->table . '.id_manufacturer= m.id', ['name_manufacturer'=>'name'], 
			'left');
			$select->join(array('y' => 'year'), $this->table . '.id_year= y.id', ['name_year'=>'name'], 
			'left');
			$select->join(array('a' => 'author'), $this->table . '.id_author= a.id', ['name_author'=>'name'], 
			'left');
			$select->join(array('ca' => 'category'), $this->table . '.id_category= ca.id', ['name_category'=>'name'], 
			'left');
			
			$select->limit($this->limit);
			$select->offset($this->offset);
			
			$select->order($this->order);
			
//			s($this->tableGateway, $select);
			
		});
		return $resultSet;
        //return $this->tableGateway->select();
    }
	
	public function getProductLimit($limit){
		
		$this->limit = $limit;
		
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'color'), $this->table . '.id_color= c.id', ['name_color'=>'name'], 'left');
			$select->join(array('m' => 'manufacturer'), $this->table . '.id_manufacturer= m.id', ['name_manufacturer'=>'name'], 
			'left');
			$select->join(array('y' => 'year'), $this->table . '.id_year= y.id', ['name_year'=>'name'], 
			'left');
			$select->join(array('a' => 'author'), $this->table . '.id_author= a.id', ['name_author'=>'name'], 
			'left');
			$select->join(array('ca' => 'category'), $this->table . '.id_category= ca.id', ['name_category'=>'name'], 
			'left');
			$select->order("id DESC");
			$select->limit($this->limit);
		});
		return $resultSet;
		
	}

    public function getProduct($id)
    {
//        $id = (int) $id;
		$this->id = $id;
//        $rowset = $this->tableGateway->select(['id' => $id]);
		$rowset = $this->tableGateway->select(function (Select $select){
			$select->join(array('A' => 'author'), $this->table . '.id_author= A.id', ['name_author'=>'name'], 'left');
            $select->join(array('Y' => 'year'), $this->table . '.id_year= Y.id', ['name_year'=>'name'], 'left');
            $select->join(array('C' => 'color'), $this->table . '.id_color= C.id', ['name_color'=>'name'], 'left');
            $select->join(array('M' => 'manufacturer'), $this->table . '.id_manufacturer= M.id', ['name_manufacturer'=>'name'], 'left');
            $select->join(array('CA' => 'category'), $this->table . '.id_category = CA.id', ['name_category'=>'name'], 'left');
            $select->where([$this->table.'.id' => $this->id]);
            });
        return $rowset->current();
    }

    public function saveProduct(Product $product)
    {
        $data = [
            
            'name'  => $product->name,
			'id_author' => $product->id_author,
			'id_year' => $product->id_year,
			'product_img' => $product->product_img,
			'id_category' => $product->id_category,
			'id_manufacturer' => $product->id_manufacturer,		
			'id_color' => $product->id_color,
			
        ];

        $id = (int) $product->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getProduct($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update product with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteProduct($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
