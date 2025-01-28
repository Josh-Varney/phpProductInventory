<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;

class ProductsController extends AppController
{
    /**
     * List all products with filters for status and name search
     *
     * @return void
     */
    public function index()
    {
        // Initialize query for products
        $query = $this->Products->find();

        // Exclude deleted products from the list
        $query->where(['deleted' => false]);

        // Filter by product status if provided in the query string
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

        // Define pagination limit (20 items per page)
        $products = $this->paginate($query, [
            'limit' => 20, // Limit to 20 products per page
        ]);

        // Order the results by product ID in ascending order
        $query->order(['id' => 'ASC']);
        
        // Pass paginated results to the view
        $this->set(compact('products', 'searchTerm', 'status'));
    }

    /**
     * Add a new product
     *
     * @return \Cake\Http\Response|null Redirects to index on success, renders view otherwise
     */
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

        // If no validation errors, redirect to index
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Edit an existing product
     *
     * @param string|null $id Product ID.
     * @return \Cake\Http\Response|null Redirects to index on success, renders view otherwise
     */
    public function edit($id = null)
    {
        Log::info("Entering edit action with ID: " . $id);

        // Step 1: Check if the ID is passed and valid
        if (!$id) {
            Log::error('Edit action called without an ID');
            $this->Flash->error(__('Invalid Product ID'));
            return;  // Stay on the edit page if no ID is provided
        }

        try {
            // Fetch the product based on ID
            $product = $this->Products->get($id);
            Log::info('Fetched Product: ' . json_encode($product));
        } catch (\Exception $e) {
            // If product not found, log the error and show a message
            Log::error('Product fetch failed: ' . $e->getMessage());
            $this->Flash->error(__('Product not found.'));
            return;  // Stay on the edit page if product not found
        }

        // Step 2: Check if the request is POST or PUT (form submission)
        if ($this->request->is(['post', 'put'])) {
            // Get the form data from the request
            $formData = $this->request->getData();
            Log::info('Form data received: ' . json_encode($formData));

            // Format the price to two decimal places
            if (isset($formData['price'])) {
                $formData['price'] = number_format($formData['price'], 2, '.', ''); // Ensures 2 decimal places
                Log::info('Formatted Price: ' . $formData['price']);
            }

            // Dynamically calculate the status based on quantity
            if (isset($formData['quantity'])) {
                $formData['status'] = $this->Products->calculateStatus($formData['quantity']);
                Log::info('Calculated Status: ' . $formData['status']);
            }

            // Compare the form data with the existing product data
            $dataChanged = false;
            foreach ($formData as $key => $value) {
                // Check if the form data is different from the existing product data
                if (isset($product->$key) && $product->$key != $value) {
                    $dataChanged = true;
                    break; // Exit the loop once a difference is found
                }
            }

            // If no data has changed, do not proceed with the update
            if (!$dataChanged) {
                Log::info('No changes detected. No update needed.');
                return $this->redirect(['action' => 'index']); // Exit the method, don't save the product
            }

            // Patch the product entity with the form data
            $product = $this->Products->patchEntity($product, $formData);

            if ($product->hasErrors()) {
                // If validation fails, log and show specific errors
                $errors = $product->getErrors();
                Log::error('Validation Errors: ' . json_encode($errors));

                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        Log::error("Error on '$field': $error");
                        $this->Flash->error(__("Error in '$field': $error"));
                    }
                }

                $this->Flash->error(__('There were errors in the form. Please fix them and try again.'));
            } else {
                // If no validation errors, try to save the updated product
                if ($this->Products->save($product)) {
                    Log::info('Product successfully updated: ' . json_encode($product));
                    $this->Flash->success(__('Product has been updated.'));
                    return $this->redirect(['action' => 'index']); // Redirect to the index action
                } else {
                    // If save fails, log and show error message
                    Log::error('Product save failed. Errors: ' . json_encode($product->getErrors()));
                    $this->Flash->error(__('Unable to update product. Please try again.'));
                }
            }
        }

        // Step 3: Set the product to be passed to the view
        $this->set(compact('product'));
    }

    /**
     * Soft-delete a product (set deleted flag to true)
     *
     * @param string|null $id Product ID.
     * @return \Cake\Http\Response|null Redirects to index on success
     */
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

    /**
     * Display products with low stock
     *
     * @return void
     */
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
