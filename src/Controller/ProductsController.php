<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;

class ProductsController extends AppController
{
    // List all products with filters for status and name search
    public function index()
    {
        // Initialize query for products
        $query = $this->Products->find();

        // $this->Flash->success('Test Flash Message');

        // Exclude deleted products
        $query->where(['deleted' => false]);

        // Filter by status if provided in the query string
        $status = $this->request->getQuery('status');
        if (!empty($status)) {
            $query->where(['status' => $status]);
        }

        // Search products by name if a search term is provided
        $searchTerm = $this->request->getQuery('search');
        if (!empty($searchTerm)) {
            $query->where(['name LIKE' => '%' . $searchTerm . '%']);
        }

        // Paginate the results
        $products = $this->paginate($query);

        // Pass the results to the view
        $this->set(compact('products', 'searchTerm', 'status'));
    }

    // Add a new product
    public function add()
    {
        $product = $this->Products->newEmptyEntity();

    if ($this->request->is('post')) {
        // Get the posted data
        $data = $this->request->getData();

        // Patch the new entity with data (including validation)
        $product = $this->Products->patchEntity($product, $data);

        // Check if the entity passed validation
        if ($product->hasErrors()) {
            $errorMessages = $product->getErrors();
            Log::error('Product creation failed. Errors: ' . json_encode($errorMessages));

            // Provide error messages to the user
            $errorMessage = 'Unable to add the product. Please check your input.';
            if (!empty($errorMessages)) {
                $errorMessage .= ' Validation Errors: ' . json_encode($errorMessages);
            }

            $this->Flash->error($errorMessage);
        } else {
            // Attempt to save the new product
            if ($this->Products->save($product)) {
                $this->Flash->success('Product has been added successfully!');
                return $this->redirect(['action' => 'index']); // Redirect after success
            } else {
                $this->Flash->error('Unable to add the product. Please try again.');
            }
        }
    }

        // If you don't want to render a view, you can just use a redirect or other response.
        return $this->redirect(['action' => 'index']); // No view rendering
    }

    public function edit($id = null)
    {
        // Fetch the product being edited by its ID
        $product = $this->Products->get($id);

        if ($this->request->is(['post', 'put'])) {
            // Get the updated product data from the form
            $updatedData = $this->request->getData();

            // Debug incoming data for troubleshooting (keep this for debugging)
            // debug($updatedData);

            // Patch the existing product with the updated data
            $product = $this->Products->patchEntity($product, $updatedData);

            var_dump($product);

            // Check the status based on the updated quantity
            if ($product->quantity > 10) {
                $product->status = 'In Stock';
            } elseif ($product->quantity > 0 && $product->quantity <= 10) {
                $product->status = 'Low Stock';
            } else {
                $product->status = 'Out of Stock';
            }

            // Attempt to save the updated product
            if ($this->Products->save($product)) {
                $this->Flash->success('Product has been updated successfully!');
                return $this->redirect(['action' => 'index']); // Redirect after success
            } else {
                // If save fails, log the error and display a general error message
                $errorMessages = $product->getErrors();
                Log::error('Product update failed. Errors: ' . json_encode($errorMessages));

                // Provide user-friendly error message, including specific validation errors if any
                $errorMessage = 'Unable to update the product. Please try again.';
                if (!empty($errorMessages)) {
                    $errorMessage .= ' Validation Errors: ' . json_encode($errorMessages);
                }

                $this->Flash->error($errorMessage);
            }
        }

        // Pass the product object to the view for the form
        $this->set(compact('product'));
    }


    // Soft-delete a product (set deleted flag)
    public function delete($id = null)
    {
        $product = $this->Products->get($id);

        // Set deleted flag to true instead of actually deleting the record
        $product->deleted = true;

        if ($this->Products->save($product)) {
            $this->Flash->success('Product has been soft-deleted successfully!');
        } else {
            $this->Flash->error('Unable to soft-delete the product. Please try again.');
        }

        // Redirect back to the product list
        return $this->redirect(['action' => 'index']);
    }

    public function lowStock()
    {
    // Find all products with status 'Low Stock'
    $query = $this->Products->find()
        ->where(['status' => 'Low Stock']);

    // Paginate the query
    $products = $this->paginate($query);

    // Pass the products to the view
    $this->set(compact('products'));
    }

}

