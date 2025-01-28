<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Product extends Entity
{
    /**
     * List of fields that can be mass-assigned.
     *
     * Mass-assignment allows you to quickly create or update entities using data arrays.
     * This property ensures that fields can be safely mass-assigned or protected.
     *
     * @var array
     */
    protected array $_accessible = [
        '*' => true,    // Allow mass assignment for all fields except specified ones
        'id' => false,   // Prevent mass assignment of 'id' (to protect primary key)
        'slug' => false, // Prevent mass assignment of 'slug' (to protect SEO-friendly field)
        'deleted' => true // Allow mass assignment of 'deleted' field (for soft delete)
    ];
}
