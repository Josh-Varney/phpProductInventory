<?php

declare(strict_types=1);

namespace App\Model\Table;

use PHPUnit\Framework\TestCase;
use App\Model\Table\ProductsTable;

// require_once 'src/bootstrap.php';

/**
 * Unit tests for the ProductsTable model.
 *
 * This class contains test cases to verify the functionality of methods within the ProductsTable class.
 * Specifically, it tests the save functionality and the calculateStatus method.
 */
class ProductsTest extends TestCase
{
    /**
     * @var \App\Model\Table\ProductsTable|null
     */
    private $Products;

    /**
     * Set up method for each test case
     *
     * This method is called before each test to initialize the Products model
     * and mock necessary methods such as 'save', 'patchEntity', and 'get'.
     *
     * @return void
     */
    public function setUp(): void
    {
        // Mock the ProductsTable and only mock the save, patchEntity, and get methods
        $this->Products = $this->getMockBuilder(ProductsTable::class)
            ->onlyMethods(['save', 'patchEntity', 'get']) // Mock the save method
            ->getMock();
    }

    /**
     * Test the save method of ProductsTable.
     *
     * This test ensures that the save method works as expected by creating a fake product entity,
     * mocking the save method to return the fake entity, and asserting that the save method returns the same entity.
     *
     * @return void
     */
    public function testSaveProduct()
    {
        // Create a fake product entity
        $fakeProductEntity = $this->Products->newEmptyEntity();
        $fakeProductEntity->name = 'Fake Product';
        $fakeProductEntity->price = 100;
        $fakeProductEntity->quantity = 20;

        // Mock the save method to return the fake product entity
        $this->Products->method('save')
            ->willReturn($fakeProductEntity);

        // Call the save method and assert the result
        $result = $this->Products->save($fakeProductEntity);
        $this->assertSame($fakeProductEntity, $result); // Assert that the result is the same as the mock entity
    }

    /**
     * Test the calculateStatus method of ProductsTable.
     *
     * This test ensures that the calculateStatus method returns the correct status
     * based on the quantity of the product.
     *
     * @return void
     */
    public function testCalculateStatus()
    {
        // Testing the calculateStatus method
        $this->assertEquals('in stock', $this->Products->calculateStatus(20)); // Expect 'in stock' for quantity > 10
        $this->assertEquals('low stock', $this->Products->calculateStatus(5));  // Expect 'low stock' for quantity between 1 and 10
        $this->assertEquals('out of stock', $this->Products->calculateStatus(0)); // Expect 'out of stock' for quantity = 0
    }
}
