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
