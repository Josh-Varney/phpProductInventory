<?php

// src/Model/Table/ProductsTable.php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }

    // Validation rules for the Product entity
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

            // Custom validation for price > 100 should have at least 10 quantity
            ->add('quantity', 'customPriceQuantity', [
                'rule' => function ($value, $context) {
                    if ($context['data']['price'] > 100 && $value < 10) {
                        return false; // Quantity must be at least 10 if price > 100
                    }
                    return true;
                },
                'message' => 'If price is greater than 100, the quantity must be at least 10.'
            ])

            // Custom validation for "promo" in the name
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

    // Rules for uniqueness and other logic
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name'], 'This name is already taken.'));
        return $rules;
    }
}
