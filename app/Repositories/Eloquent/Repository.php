<?php

namespace App\Repositories\Eloquent;

use Illuminate\Contracts\Encryption\DecryptException;

abstract class Repository
{

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 20, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns)->appends('');
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'))
    {
        $result = $this->model->where($field, '=', $value);

        if ($result->count() == 1)
            return $result->first($columns);

        return $result;
    }

    /**
     * @param $key
     * @param array $columns
     * @return mixed|null
     */
    public function findByKey($key, $columns = array('*'))
    {
        try {
            $id = decrypt($key);
            return $this->find($id, $columns);
        } catch (DecryptException $decryptException) {
            return null;
        }

    }

}
