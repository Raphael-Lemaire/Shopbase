<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CartTable
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
			$select->join(array('c' => 'customer'), $this->table . '.id_customer= c.id', ['name_customer'=>'name'], 'left');
			
			if(!empty($this->active)) {
				$select->where->like('active', $this->active);	
			}
			
			
		});
		return $resultSet;
        //return $this->tableGateway->select();
    }
	
	public function getCartLimit($limit){
		
		$this->limit = $limit;
		
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'customer'), $this->table . '.id_customer= c.id', ['name_customer'=>'name'], 'left');
			$select->order("id DESC");
			$select->limit($this->limit);
		});
		return $resultSet;
		
	}
	
	public function getCurrentCart($id_customer) {
		$this->id_customer = $id_customer;
		$resultSet = $this->tableGateway->Select(function (Select $select){
			$select->where(['id_customer' => $this->id_customer]);
			$select->limit(1);
		});
		return $resultSet;
	}

    public function getCart($id)
    {
//        $id = (int) $id;
		$this->id = $id;
//        $rowset = $this->tableGateway->select(['id' => $id]);
		$rowset = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'customer'), $this->table . '.id_customer= c.id', ['name_customer'=>'name'], 'left');

            $select->where([$this->table.'.id' => $this->id]);
            });
        return $rowset->current();
    }
	
	public function getCartCustomer($id)
    {
//        $id = (int) $id;
		$this->id = $id;
//        $rowset = $this->tableGateway->select(['id' => $id]);
		$rowset = $this->tableGateway->select(function (Select $select){
			$select->join(array('c' => 'customer'), $this->table . '.id_customer= c.id', ['name_customer'=>'name'], 'left');
			$select->join(array('pc' => 'product_cart'), $this->table . '.id= pc.id_cart', ['cart_content'=>'id_product'], 'left');

            $select->where([$this->table.'.id_customer' => $this->id]);
            });
        return $rowset->current();
    }

    public function saveCart(Cart $product)
    {
        $data = [
            
			'id_customer' => $product->id_customer,
			
        ];

        $id = (int) $product->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getCart($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update product with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCart($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
	
	    public function deleteCartCustomer($id)
    {
        $this->tableGateway->delete(['id_customer' => (int) $id]);
    }
}

