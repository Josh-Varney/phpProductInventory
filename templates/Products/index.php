<script src="/js/product-inventory.js"></script>
<div class="w-full flex flex-col p-6">
    <!-- Title -->
    <div>
        <h1 class="text-5xl font-semibold text-center text-gray-800 mb-6">Product Inventory</h1>
    </div>

    <!-- Search Form -->
    <form action="" method="get" class="mb-6">
        <div class="flex items-center justify-center space-x-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Search products by name" 
                class="p-3 rounded-md shadow-sm w-1/3" 
                value="<?= htmlspecialchars($this->request->getQuery('search') ?? '', ENT_QUOTES) ?>" 
            />
            <button 
                type="submit" 
                class="border border-gray-400 rounded-md bg-blue-200 hover:bg-blue-300 p-2 cursor-pointer">
                Search
            </button>
        </div>
    </form>

    <!-- Filter Form -->
     <div class="flex flex-row justify-between">
        <div class="flex flex-row items-center">
            <?= $this->Form->create(null, ['type' => 'get', 'id' => 'filter-form']) ?>
            
            <?= $this->Form->control('status', [
                'type' => 'select', 
                'options' => [
                    '' => 'All',
                    'Low Stock' => 'Low Stock',
                    'In Stock' => 'In Stock',
                    'Out of Stock' => 'Out of Stock',
                ],
                'value' => $this->request->getQuery('status'), // Keeps the selected value
                'onchange' => 'document.getElementById("filter-form").submit()' // Automatically submits the form on change
            ]) ?>
            
            <?= $this->Form->end() ?>
        </div>

        <!-- Add Product Button -->
        <div class="flex flex-col justify-end p-10">
            <div 
                onclick="showForm()" 
                class="border border-gray-400 p-2 bg-blue-200 hover:bg-blue-300 cursor-pointer flex items-center justify-center rounded-full shadow-md">
                <div class="">+ Add Product</div>
            </div>
        </div> 
     </div>
    



    <!-- Product Table -->
    <table border="1" class="w-full text-left border-collapse border-gray-300">
        <thead>
            <tr>
                <th class="p-2 border border-gray-300">ID</th>
                <th class="p-2 border border-gray-300">Name</th>
                <th class="p-2 border border-gray-300">Quantity</th>
                <th class="p-2 border border-gray-300">Price</th>
                <th class="p-2 border border-gray-300">Status</th>
                <th class="p-2 border border-gray-300">Last Updated</th>
                <th class="p-2 border border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="p-2 border border-gray-300"><?= $product->id ?></td>
                    <td class="p-2 border border-gray-300"><?= $product->name ?></td>
                    <td class="p-2 border border-gray-300"><?= $product->quantity ?></td>
                    <td class="p-2 border border-gray-300"><?= $product->price ?></td>
                    <td class="p-2 border border-gray-300"><?= $product->status ?></td>
                    <td class="p-2 border border-gray-300"><?= $product->last_updated ?></td>
                    <td class="p-2 border border-gray-300">
                        <a href="#" 
                        onclick="showEditForm(<?= htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8') ?>)" 
                        class="text-blue-500 hover:underline">Edit</a> |
                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'delete', $product->id]) ?>"
                           class="text-red-500 hover:underline"
                           onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <!-- Add Product Form (Hidden) -->
    <div id="form-container" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">  
        <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-2xl font-bold mb-4">Add Product</h2>
        <!-- Start of the form with POST method to submit data to the add action -->
        <?= $this->Form->create($product, ['url' => ['action' => 'add'], 'type' => 'post']) ?>
        
        <!-- Product Name -->
        <div class="mb-4">
            <?= $this->Form->control('name', [
                'label' => 'Product Name',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'placeholder' => 'Enter product name',
                'required' => true
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('name')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('name')[0]) ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Quantity -->
        <div class="mb-4">
            <?= $this->Form->control('quantity', [
                'label' => 'Quantity',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'placeholder' => 'Enter quantity',
                'required' => true
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('quantity')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('quantity')[0]) ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Price -->
        <div class="mb-4">
            <?= $this->Form->control('price', [
                'label' => 'Price',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'placeholder' => 'Enter price',
                'required' => true
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('price')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('price')[0]) ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2">Cancel</button>
            <?= $this->Form->button('Save', ['class' => 'px-4 py-2 bg-blue-600 text-white rounded-md']) ?>
        </div>
        
        <!-- Close the form -->
        <?= $this->Form->end() ?>
        </div>
    </div>


    <div id="edit-form-container" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-2xl font-bold mb-4">Edit Product</h2>

        <!-- CakePHP Form for editing the product -->
        <?= $this->Form->create($product, [
            'url' => ['action' => 'edit', $product->id], // Edit action with product ID in the URL
            'type' => 'post', // Use 'post' for form submission
            'id' => 'edit-product-form'
        ]) ?>

        <!-- Product Name Input -->
        <div class="mb-4">
            <?= $this->Form->control('name', [
                'label' => 'Product Name',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'required' => true,
                'id' => 'edit-product-name', // Unique ID for JS access
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('name')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('name')[0]) ?></div>
            <?php endif; ?>
        </div>

        <!-- Quantity Input -->
        <div class="mb-4">
            <?= $this->Form->control('quantity', [
                'label' => 'Quantity',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'required' => true,
                'id' => 'edit-product-quantity', // Unique ID for JS access
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('quantity')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('quantity')[0]) ?></div>
            <?php endif; ?>
        </div>

        <!-- Price Input -->
        <div class="mb-4">
            <?= $this->Form->control('price', [
                'label' => 'Price',
                'class' => 'w-full p-3 border border-gray-300 rounded-md',
                'required' => true,
                'id' => 'edit-product-price', // Unique ID for JS access
            ]) ?>
            <!-- Display error if any -->
            <?php if ($product->getError('price')): ?>
                <div class="text-red-500 text-sm"><?= h($product->getError('price')[0]) ?></div>
            <?php endif; ?>
        </div>

        <!-- Form Action Buttons -->
        <div class="flex justify-end">
            <!-- Cancel Button (hide the modal) -->
            <?= $this->Form->button('Cancel', [
                'type' => 'button', 
                'onclick' => 'hideEditForm()', 
                'class' => 'px-4 py-2 bg-gray-500 text-white rounded-md mr-2'
            ]) ?>
            <!-- Save Button -->
            <?= $this->Form->button('Save', [
                'class' => 'px-4 py-2 bg-blue-600 text-white rounded-md'
            ]) ?>
        </div>

    <?= $this->Form->end() ?>
    </div>
</div>

</div>
<script>
    function showForm() {
    document.getElementById('form-container').classList.remove('hidden');
    }

    function hideForm() {
        document.getElementById('form-container').classList.add('hidden');
    }

    function showEditForm(product) {
        console.log("Showing Edit Form:", product);
        document.getElementById('edit-form-container').classList.remove('hidden');
        document.getElementById('edit-product-name').value = product.name;
        document.getElementById('edit-product-quantity').value = product.quantity;
        document.getElementById('edit-product-price').value = product.price;
        document.getElementById('edit-product-id').value = product.id;
    }

    function hideEditForm() {
        document.getElementById('edit-form-container').classList.add('hidden');
    }

    function confirmDelete() {
        return confirm("Are you sure you want to delete this product?");
    }
</script>