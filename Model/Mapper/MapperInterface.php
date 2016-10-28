<?php

namespace Model\Mapper;

interface MapperInterface
{
    public function findById($id);
    
    public function find($criteria = '');

    public function insert($data);

    //public function update($entity);
    public function update( $data , $criteria );

    public function delete($entity);
}
