<?php

// src/Model/Table/ProductsTable.php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    /**
     * Initialize method
     *
     * Initializes the ProductsTable and adds the 'Timestamp' behavior
     *
     * @param array $config Configuration array
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Add 'Timestamp' behavior to automatically manage created and modified fields
        $this->addBehavior('Timestamp');
    }

    /**
     * Validation rules for the Product entity
     *
     * Defines validation rules for product attributes like name, quantity, and price.
     *
     * @param \Cake\Validation\Validator $validator Validator instance
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            // Name validation: required, between 3 and 50 characters
            ->requirePresence('name', 'create')
            ->notEmptyString('name', 'Name is required')
            ->add('name', 'length', [
                'rule' => ['lengthBetween', 3, 50],
                'message' => 'Name must be between 3 and 50 characters.'
            ])

            // Quantity validation: integer, between 0 and 1000
            ->integer('quantity', 'Quantity must be an integer.')
            ->add('quantity', 'range', [
                'rule' => ['range', 0, 1000],
                'message' => 'Quantity must be between 0 and 1000.'
            ])
            
            // Price validation: decimal, greater than 0 and less than 10,000
            ->decimal('price', 2, 'Price must be a valid decimal.')
            ->greaterThan('price', 0, 'Price must be greater than 0.')
            ->lessThan('price', 10000, 'Price must be less than 10,000.')

            // Custom validation: Price > 100 requires quantity to be at least 10
            ->add('quantity', 'customPriceQuantity', [
                'rule' => function ($value, $context) {
                    if ($context['data']['price'] > 100 && $value < 10) {
                        return false; // Quantity must be at least 10 if price > 100
                    }
                    return true;
                },
                'message' => 'If price is greater than 100, the quantity must be at least 10.'
            ])

            // Custom validation: "promo" in the name requires price < 50
            ->add('price', 'promoPrice', [
                'rule' => function ($value, $context) {
                    if (isset($context['data']['name']) && strpos($context['data']['name'], 'promo') !== false) {
                        if ($value >= 50) {
                            return false; // Price must be less than 50 if the name contains 'promo'
                        }
                    }
                    return true;
                },
                'message' => 'If the name contains "promo", the price must be less than 50.'
            ]);

        return $validator;
    }

    /**
     * Build validation rules for uniqueness and other logic
     *
     * Adds rules for ensuring that product names are unique in the database.
     *
     * @param \Cake\ORM\RulesChecker $rules RulesChecker instance
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Ensure that the 'name' field is unique across the products table
        $rules->add($rules->isUnique(['name'], 'This name is already taken.'));
        return $rules;
    }

    /**
     * Calculate the stock status based on the quantity
     *
     * This method determines the status of a product based on its quantity.
     *
     * @param int $quantity Quantity of the product
     * @return string Stock status: 'in stock', 'low stock', or 'out of stock'
     */
    public function calculateStatus($quantity)
    {
        if ($quantity > 10) {
            return 'in stock'; // Status is 'in stock' if quantity is greater than 10
        } elseif ($quantity >= 1 && $quantity <= 10) {
            return 'low stock'; // Status is 'low stock' if quantity is between 1 and 10
        } else {
            return 'out of stock'; // Status is 'out of stock' if quantity is 0
        }
    }
}
