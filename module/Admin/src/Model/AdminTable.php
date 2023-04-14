<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class AdminTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAdmin($id)
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
	
	public function getAdminLogin($email, $password){
		
        $rowset = $this->tableGateway->select(['email' => $email, 'password' => $password]);
		
        $row = $rowset->current();
		//d($row);
        return $row;
		
	}

    public function saveAdmin(Admin $admin)
    {
        $data = [
            
            'name'  => $admin->name,
			'password' => md5($admin->password),
			'email' => $admin->email,
			'profil_img' => $admin->profil_img,
        ];

        $id = (int) $admin->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAdmin($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update admin with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAdmin($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
