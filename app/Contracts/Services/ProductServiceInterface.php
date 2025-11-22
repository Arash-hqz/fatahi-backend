<?php

namespace App\Contracts\Services;

interface ProductServiceInterface
{
    public function all();
    public function find($id);
    public function create(array $data, $imageFile = null);
    public function update($id, array $data, $imageFile = null);
    public function delete($id);
}
