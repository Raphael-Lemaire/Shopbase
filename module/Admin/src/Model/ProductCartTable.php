<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class ProductCartTable
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
			$select->join(array('p' => 'product'), $this->table . '.id_product= p.id', ['name_product'=>'name'], 
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
			$select->join(array('p' => 'product'), $this->table . '.id_product= p.id', ['name_product'=>'name'], 
			'left');
			
			$select->limit($this->limit);
			$select->offset($this->offset);
			
			$select->order($this->order);
			
//			s($this->tableGateway, $select);
			
		});
		return $resultSet;
        //return $this->tableGateway->select();
    }
	
	public function getProductCartLimit($limit){
		
		$this->limit = $limit;
		
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->join(array('p' => 'product'), $this->table . '.id_product= p.id', ['name_product'=>'name'], 
			'left');
			$select->order("id DESC");
			$select->limit($this->limit);
		});
		return $resultSet;
		
	}
	
    public function getProductCart($id)
    {
//        $id = (int) $id;
		$this->id = $id;
//        $rowset = $this->tableGateway->select(['id' => $id]);
		$rowset = $this->tableGateway->select(function (Select $select){
            $select->join(array('p' => 'product'), $this->table . '.id_product= p.id', ['name_product'=>'name'], 'left');

            $select->where([$this->table.'.id' => $this->id]);
            });
        return $rowset->current();
    }
	
	public function fetchAllByCart($id_cart)
    {
		$this->id_cart = $id_cart;
		$resultSet = $this->tableGateway->select(function (Select $select) 
		{
			
            $select->join(array('P' => 'product'), $this->table . '.id_product = P.id', ['name_product' => 'name','product_image' => 'product_img'], 'left');
			$select->where([$this->table.'.id_cart' => $this->id_cart]);
        });
        return $resultSet->current();
    }


    public function saveProductCart(ProductCart $productcart)
    {
        $data = [
            
			'id' => $productcart->id,
			'id_product' => $productcart->id_product,		
			'id_cart' => $productcart->id_cart,
			'quantity' => $productcart->quantity,
			
        ];

        $id = (int) $productcart->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getProductCart($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update product with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteProductCart($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
    
}
