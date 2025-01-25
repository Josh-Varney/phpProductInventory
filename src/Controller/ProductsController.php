<?php

namespace App\Controller;

use App\Controller\AppController;

class ProductsController extends AppController
{
    public function index()
    {
        $products = $this->paginate($this->Products->find());
        $this->set('products', $products);
    }
}
