<div class="container mx-auto p-6">
    <h1 class="text-5xl font-semibold text-center text-gray-800 mb-6">Products</h1>

    <!-- Search Form -->
    <form action="" method="get" class="mb-6">
        <div class="flex items-center justify-center space-x-4">
            <input type="text" name="search" placeholder="Search products by name" 
                   class="p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/3" />
            <button type="submit" class="p-3 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-500 focus:ring-2 focus:ring-blue-500">Search</button>
        </div>
    </form>

    <!-- Product List Table -->
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-4 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-700">Quantity</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-700">Price</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-700">Last Updated</th>
                <th class="p-4 text-center text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Repeat this for each product in your list -->
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 text-sm text-gray-700">Product Name</td>
                <td class="p-4 text-sm text-gray-700">10</td>
                <td class="p-4 text-sm text-gray-700">$100.00</td>
                <td class="p-4 text-sm text-gray-700">
                    <!-- Example dynamic status calculation -->
                    <span class="text-green-500">In Stock</span>
                </td>
                <td class="p-4 text-sm text-gray-700">2025-01-25</td>
                <td class="p-4 text-center">
                    <a href="#" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Edit</a> |
                    <a href="#" class="text-red-600 hover:text-red-500 font-medium transition-colors" onclick="openModal()">Delete</a>
                </td>
            </tr>
            <!-- End Repeat -->
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center space-x-4">
        <a href="#" class="p-3 bg-gray-300 text-gray-600 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">Previous</a>
        <a href="#" class="p-3 bg-gray-300 text-gray-600 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">Next</a>
    </div>
</div>
