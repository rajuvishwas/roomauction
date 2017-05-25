<?php

namespace App\Repositories\Eloquent;

use App\Pagination\CursorPaginator;
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
     * @param string $key
     * @param string $order
     * @return CursorPaginator
     */
    public function paginate($perPage = 20, $columns = array('*'), $key = 'id', $order = 'asc')
    {
        return $this->cursorPaginate($perPage, $columns, $key, $order);
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
     * @return mixed|null
     */
    public function decrypt($key)
    {
        try {
            return decrypt($key);
        } catch (DecryptException $decryptException) {
            return null;
        }

    }

    /**
     * @param $perPage
     * @param $columns
     * @param $key
     * @param $order
     * @param bool $encoded
     * @return CursorPaginator
     */
    private function cursorPaginate($perPage, $columns, $key, $order)
    {
        // only accept asc or desc
        $order = strtolower($order) == 'asc' ? 'asc' : 'desc';

        if (!in_array('*', $columns) && !in_array($key, $columns))
            array_push($columns, $key);

        $before = $this->decode(request('before'));
        $after = $this->decode(request('after'));

        $result = $this->model;

        if ($before != "") {

            $result = $result->where($key, $this->reverseComparator('<', $order), $before)
                ->limit($perPage)
                ->orderBy($key, $this->reverseOrderBy($order))
                ->get($columns)
                ->reverse();

        } else {

            if ($after != "") {
                $result = $result->where($key, $this->reverseComparator('>', $order), $after);
            }

            $result = $result->limit($perPage)->orderBy($key, $order)->get($columns);
        }

        if (!$result->isEmpty()) {

            $before = $result->first()->$key;
            $lastItem = $result->slice(0, $perPage);
            $after = $lastItem->last()->$key;

            $previous = $this->model
                ->where($key, $this->reverseComparator('<', $order), $before)
                ->orderBy($key, $order)
                ->limit(1)
                ->first();

            $next = $this->model
                ->where($key, $this->reverseComparator('>', $order), $after)
                ->orderBy($key, $order)
                ->limit(1)
                ->first();

            $isFirstPage = ( $previous == null ) ? true : false;
            $isLastPage = ( $next == null ) ? true : false;

            return new CursorPaginator(
                $result,
                $perPage,
                $isFirstPage,
                $isLastPage,
                $this->encode($before),
                $this->encode($after)
            );
        }

        return $result;
    }

    /**
     * @param $order
     * @return string
     */
    private function reverseOrderBy($order)
    {
        return ( $order == 'asc' ) ? 'desc' : 'asc';
    }

    /**
     * @param $value
     * @param bool $encoded
     * @return bool|string
     */
    private function decode($value)
    {
        return (env('APP_ENCODED_PAGINATOR')) ? base64_decode($value) : $value;
    }

    /**
     * @param $value
     * @param bool $encoded
     * @return string
     */
    private function encode($value)
    {
        return (env('APP_ENCODED_PAGINATOR')) ? base64_encode($value) : $value;
    }

    /**
     * @param $comparator
     * @param $order
     * @return string
     */
    private function reverseComparator($comparator, $order) {

        if($order == 'desc' && $comparator == '<') {
            return '>';
        } else if($order == 'desc' && $comparator == '>') {
            return '<';
        }

        return $comparator;
    }

}
