<h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Edit Product</h1>

<!-- Container for the form -->
<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <!-- Begin the form creation -->
    <?= $this->Form->create($product, ['class' => 'space-y-6']) ?>
    
    <!-- Product Name Input -->
    <div>
        <?= $this->Form->control('name', [
            'label' => 'Product Name',  
            'class' => 'w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500', 
            'required' => true  
        ]) ?>
    </div>
    
    <!-- Quantity Input -->
    <div>
        <?= $this->Form->control('quantity', [
            'label' => 'Quantity',
            'class' => 'w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500',
            'required' => true
        ]) ?>
    </div>
    
    <!-- Price Input -->
    <div>
        <?= $this->Form->control('price', [
            'label' => 'Price',
            'class' => 'w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500', 
            'required' => true
        ]) ?>
    </div>

    <!-- Button Section for Save & Cancel -->
    <div class="flex justify-end space-x-4">
        <!-- Cancel Button -->
        <button type="button" onclick="window.location='<?= $this->Url->build(['action' => 'index']) ?>'" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            Cancel
        </button>
        
        <!-- Save Changes Button -->
        <?= $this->Form->button(__('Save Changes'), [
            'class' => 'px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700'
        ]) ?>
    </div>

    <!-- End of the form -->
    <?= $this->Form->end() ?>
</div>
