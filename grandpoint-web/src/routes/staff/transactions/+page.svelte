<script lang=ts>
    import Navbar from "$lib/navbar/navbar.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    async function loadData(){

        try{
            const response = await api.get("getProducts");
            products = response.payload;
            console.log(products);
        }catch(e:any){
            console.log(e);
        }
    }

    let prod = {name: "test", price: 100, quantity: 1}

    let carts:any = $state(prod);

    let products:any = $state();

    let cart: any[] = $state([]);

    // Pagination states
    let currentPage = $state(1);
    const itemsPerPage = 8; // Show 8 products per page

    // First filter products with quantity > 0
    $effect(() => {
        availableProducts = products?.filter((product: { quantity: number }) => product.quantity > 0) || [];
    });

    // Then paginate the filtered products
    $effect(() => {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        paginatedProducts = availableProducts?.slice(startIndex, endIndex) || [];
        totalPages = Math.ceil((availableProducts?.length || 0) / itemsPerPage);
    });

    let availableProducts: any[] = $state([]);
    let paginatedProducts: any[] = $state([]);
    let totalPages = $state(0);

    function nextPage() {
        if (currentPage < totalPages) currentPage++;
    }

    function prevPage() {
        if (currentPage > 1) currentPage--;
    }

    function addToCart(product: any) {
        // Check if product already in cart
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            // If quantity less than stock, increment
            if (existingItem.quantity < product.quantity) {
                existingItem.quantity += 1;
                cart = [...cart]; // Trigger reactivity
            }
        } else {
            // Add new item to cart
            cart = [...cart, { 
                id: product.id,
                name: product.product_name,
                price: product.price,
                quantity: 1,
                maxStock: product.quantity
            }];
        }
    }

    function removeFromCart(productId: number) {
        cart = cart.filter(item => item.id !== productId);
    }

    function updateQuantity(productId: number, newQuantity: number) {
        const item = cart.find(item => item.id === productId);
        if (item && newQuantity > 0 && newQuantity <= item.maxStock) {
            item.quantity = newQuantity;
            cart = [...cart]; // Trigger reactivity
        }
    }

    let showConfirmation = $state(false); // To control the confirmation dialog
    let showSuccessMessage = $state(false); // To control the success message
    let successMessageTimeout: ReturnType<typeof setTimeout>; // To store the timeout for the success message

    async function submitTransaction() {
        const user = JSON.parse(localStorage.getItem("user") || '{}'); // Default to an empty object if null
        const transactionData = {
            total_amount: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
            prepared_by: user.id?.toString() || "", // Convert to string or default to empty string
            items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                price: item.price
            }))
        };

        try {
            const response = await api.post("addTransaction", transactionData);
            console.log("Transaction successful:", response);
            
            // Reload products data
            await loadProducts(); // Call to reload products
            
            // Clear the cart
            cart = [];
            
            // Show success message
            showSuccessMessage = true;
            clearTimeout(successMessageTimeout); // Clear any existing timeout
            successMessageTimeout = setTimeout(() => {
                showSuccessMessage = false; // Hide success message after 1 second
            }, 1000);
        } catch (error) {
            console.error("Error submitting transaction:", error);
        }
    }

    function confirmSubmission() {
        showConfirmation = true; // Show confirmation dialog
    }

    function handleConfirm() {
        showConfirmation = false; // Hide confirmation dialog
        submitTransaction(); // Proceed with the transaction
    }

    function handleCancel() {
        showConfirmation = false; // Hide confirmation dialog
    }

    async function loadProducts() {
        try {
            const response = await api.get("getProducts");
            products = response.payload; // Assuming you have a products variable to store the fetched data
        } catch (error) {
            console.error("Error loading products:", error);
        }
    }

    onMount(() => {
        console.log("Inventory page mounted");
        loadData().catch(error => {
            console.error("Error loading data:", error);
        });
    });

</script>

<style>

    img {
        height: 100px;
        width: 100px;
        object-fit: cover;
    }
</style>


    <Navbar/>

    <div class="grid grid-cols-3 gap-4 ml-[20%] h-[75%] mt-20 px-8 py-4">
        <!-- Left side - Products (span 2) -->
        <div class="col-span-2 bg-slate-50 p-4 rounded-lg shadow h-auto border border-slate-300">
            <h2 class="text-xl font-bold mb-4">Products</h2>
            <div class="grid max-md:grid-cols-1 grid-cols-2 gap-4">
                {#each paginatedProducts as product}
                    <div class="col-span-1 grid-cols-3 grid bg-white rounded-lg shadow h-auto border border-slate-300">
                            {#if product.image_route}
                                <div class="flex justify-center">
                                    <img src="/uploads/{product.image_route}" alt="product" class=" rounded-l-lg">
                                </div>
                            {:else}
                                <div class="flex justify-center">
                                    <img class="image w-fit" alt="item" src="../placeholder.png">
                                </div>
                            {/if}
                        <div class="col-span-2 ml-3 relative pb-12">
                            <div>
                                <p class="text-black text-sm truncate">
                                    {product.product_name}
                                </p>
                                <p class="text-black text-xs">₱{product.price}</p>
                                <p class="text-black text-xs">
                                    Stock: {
                                        product.quantity - (cart.find(item => item.id === product.id)?.quantity || 0)
                                    }
                                </p>
                            </div>
                            <button 
                                class="absolute bottom-2 right-2 px-3 py-1 bg-green-500 text-white rounded-lg text-sm disabled:opacity-50"
                                onclick={() => addToCart(product)}
                                disabled={product.quantity === (cart.find(item => item.id === product.id)?.quantity || 0)}
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            </button>
                        </div>
                    </div>
                {/each}
            </div>

            <!-- Pagination Controls -->
            <div class="flex justify-center gap-4 mt-6">
                <button 
                    class="px-4 py-2 rounded-lg bg-gray-200 disabled:opacity-50"
                    disabled={currentPage === 1}
                    onclick={prevPage}
                >
                    Previous
                </button>
                
                <span class="flex items-center">
                    Page {currentPage} of {totalPages}
                </span>
                
                <button 
                    class="px-4 py-2 rounded-lg bg-gray-200 disabled:opacity-50"
                    disabled={currentPage === totalPages}
                    onclick={nextPage}
                >
                    Next
                </button>
            </div>
        </div>

        <!-- Right side - Cart (span 1) -->
        <div class="col-span-1 bg-slate-50 p-4 rounded-lg shadow h-auto border border-slate-300">
            <h2 class="text-xl font-bold mb-4">Cart</h2>
            {#if cart.length === 0}
                <p class="text-gray-500">Cart is empty</p>
            {:else}
                {#each cart as item}
                    <div class="bg-white p-4 rounded-lg shadow mb-3 border border-slate-300">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold truncate max-w-[200px]">{item.name}</h3>
                                <p class="text-sm">₱{item.price}</p>
                                <p class="text-xs text-gray-500">Stock: {item.maxStock}</p>
                            </div>
                            <button 
                                class="text-red-500"
                                onclick={() => removeFromCart(item.id)}
                            >
                                ×
                            </button>
                        </div>
                        <div class="flex items-center mt-2">
                            <button 
                                class="px-2 bg-gray-200 rounded disabled:opacity-50"
                                onclick={() => updateQuantity(item.id, item.quantity - 1)}
                                disabled={item.quantity <= 1}
                            >
                                -
                            </button>
                            <span class="mx-2">{item.quantity}</span>
                            <button 
                                class="px-2 bg-gray-200 rounded disabled:opacity-50"
                                onclick={() => updateQuantity(item.id, item.quantity + 1)}
                                disabled={item.quantity >= item.maxStock}
                            >
                                +
                            </button>
                        </div>
                    </div>
                {/each}
                <div class="mt-4 border-t pt-4">
                    <p class="font-bold">Total: ₱{cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)}</p>
                </div>
            {/if}
            <div class="flex justify-center mt-4">
                <button 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg"
                    onclick={confirmSubmission} 
                    disabled={cart.length === 0}
                >
                    Submit Transaction
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Dialog -->
    {#if showConfirmation}
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold">Confirm Submission</h2>
                <p>Are you sure you want to submit the transaction?</p>
                <div class="flex justify-end mt-4">
                    <button class="px-4 py-2 bg-red-500 text-white rounded" onclick={handleConfirm}>Yes</button>
                    <button class="px-4 py-2 bg-gray-300 rounded ml-2" onclick={handleCancel}>No</button>
                </div>
            </div>
        </div>
    {/if}

    <!-- Success Message -->
    {#if showSuccessMessage}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-green-500 text-white p-4 rounded shadow">
                <p>Transaction submitted successfully!</p>
            </div>
        </div>
    {/if}
