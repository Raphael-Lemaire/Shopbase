<?php
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CategoryTable
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

    public function getCategory($id)
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

    public function saveCategory(Category $category)
    {
        $data = [
            
            'name'  => $category->name,
			'sub_category'  => $category->sub_category,
        ];

        $id = (int) $category->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getCategory($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update category with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCategory($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
