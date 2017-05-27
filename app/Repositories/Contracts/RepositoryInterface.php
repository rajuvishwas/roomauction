<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all($columns = array('*'));

    public function paginate($perPage = 20, $columns = array('*'), $key = 'id', $order = 'asc');

    public function create(array $data);

    public function update(array $data, $id, $attribute = 'id');

    public function delete($id);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));

    public function decode($key);
}