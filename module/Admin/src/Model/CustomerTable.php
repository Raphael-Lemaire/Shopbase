<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CustomerTable
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
			
			if(!empty($this->active)) {
				$select->where->like('active', $this->active);	
			}
			
			
		});
		return $resultSet;
        //return $this->tableGateway->select();
    }
	
    public function getCustomer($id)
    {
//        $id = (int) $id;
		$this->id = $id;
//        $rowset = $this->tableGateway->select(['id' => $id]);
		$rowset = $this->tableGateway->select(function (Select $select){

            $select->where([$this->table.'.id' => $this->id]);
            });
        return $rowset->current();
    }
	
	public function getCustomerLogin($email, $password){
		
        $rowset = $this->tableGateway->select(['email' => $email, 'password' => $password]);
		
        $row = $rowset->current();
		//d($row);
        return $row;
		
	}

    public function saveCustomer(Customer $customer)
    {
        $data = [
            
            
            'name'  => $customer->name,
			'password' => md5($customer->password),
			'email' => $customer->email,
			'profil_img' => $customer->profil_img,
			
        ];

        $id = (int) $customer->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getCustomer($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update customer with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCustomer($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
