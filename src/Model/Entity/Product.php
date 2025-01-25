<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Product extends Entity
{
    // Ensure $_accessible is an array type
   
    protected array $_accessible = [
        '*' => true,    // Allow mass assignment for all fields except specified ones
        'id' => false,  // Prevent mass assignment of 'id'
        'slug' => false 
    ];
}
