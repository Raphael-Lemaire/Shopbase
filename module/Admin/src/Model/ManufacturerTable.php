<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class ManufacturerTable
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

    public function getManufacturer($id)
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

    public function saveManufacturer(Manufacturer $manufacturer)
    {
        $data = [
            
            'name'  => $manufacturer->name,
        ];

        $id = (int) $manufacturer->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getManufacturer($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update manufacturer with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteManufacturer($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
