<?php

namespace App\Repositories\Eloquent;

use App\Pagination\CursorPaginator;
use Illuminate\Contracts\Encryption\DecryptException;

abstract class Repository
{

    /**
     * Get all rows for the table
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * Get all rows with pagination support
     *
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
     * Create row in a table
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a row in a table
     *
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
     * Delete a row in a table
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Find a row in a table
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find a row / lists in a table by any given field
     *
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

        return $result->get();
    }

    /**
     * Decode the encoded text
     *
     * @param $key
     * @return mixed|null
     */
    public function decode($key)
    {
        return base64_decode($key);
    }

    /**
     * Get all rows with cursor pagination support
     *
     * @param $perPage
     * @param $columns
     * @param $key
     * @param $order
     * @param array $filters
     * @return CursorPaginator
     */
    protected function cursorPaginate($perPage, $columns, $key, $order, array $filters = array())
    {
        // only accept asc or desc
        $order = strtolower($order) == 'asc' ? 'asc' : 'desc';

        if (!in_array('*', $columns) && !in_array($key, $columns))
            array_push($columns, $key);

        $before = $this->decodePaginator(request('before'));
        $after = $this->decodePaginator(request('after'));

        $result = $this->model;

        if(count($filters) != 0) {
            $result = $result->where($filters);
        }

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
            $after = $result->last()->$key;

            $previous = $this->model
                ->where($key, $this->reverseComparator('<', $order), $before)
                ->where($filters)
                ->orderBy($key, $order)
                ->limit(1)
                ->first();

            $next = $this->model
                ->where($filters)
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
                $this->encodePaginator($before),
                $this->encodePaginator($after)
            );
        }

        return $result;
    }

    /**
     * Reverse order for cursor pagination support
     *
     * @param $order
     * @return string
     */
    private function reverseOrderBy($order)
    {
        return ( $order == 'asc' ) ? 'desc' : 'asc';
    }

    /**
     * Decode pagination links for cursor pagination support
     *
     * @param $value
     * @param bool $encoded
     * @return bool|string
     */
    private function decodePaginator($value)
    {
        return (config('app.encoded_paginator')) ? base64_decode($value) : $value;
    }

    /**
     * Encode pagination links for cursor pagination support
     *
     * @param $value
     * @param bool $encoded
     * @return string
     */
    private function encodePaginator($value)
    {
        return (config('app.encoded_paginator')) ? base64_encode($value) : $value;
    }

    /**
     * Reverse the comparator for cursor pagination support
     *
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
