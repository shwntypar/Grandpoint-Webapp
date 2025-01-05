<script lang=ts>
    import Navbar from "$lib/navbar/navbar.svelte";
    import AddproductModal from "$lib/addproduct-modal/+addproduct-modal.svelte";
    import EditProductModal from "$lib/editproduct-modal/+edit-product-modal.svelte";
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

    let products:any = $state();

    onMount(() => {
        console.log("Inventory page mounted");
        loadData().catch(error => {
            console.error("Error loading data:", error);
        });
    });
    let OpenModal = $state(false); // This controls whether the modal is shown or not
    let EditModal = $state(false);

    let selectedProduct = $state(null);

    // Function to toggle modal visibility
    function toggleModal() {
        OpenModal = !OpenModal;
    }

    function EditProduct(product: any){
        selectedProduct = product;
        EditModal = !EditModal;
    }

    let showDeleteConfirm = $state(false);
    let productToDelete = $state<number | null>(null);

    function confirmDelete(productId: number) {
        productToDelete = productId;
        showDeleteConfirm = true;
    }

    async function handleConfirmDelete() {
        if (productToDelete) {
            try {
                await api.delete(`deleteProduct/${productToDelete}`);
                await loadData();
                showDeleteConfirm = false;
            } catch (e) {
                console.error('Error deleting product:', e);
            }
        }
    }

    // Add pagination states
    let currentPage = $state(1);
    const itemsPerPage = 5; // Show 10 items per page

    // Computed property for paginated products
    $effect(() => {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        paginatedProducts = products?.slice(startIndex, endIndex) || [];
        totalPages = Math.ceil((products?.length || 0) / itemsPerPage);
    });

    let paginatedProducts: any[] = $state([]);
    let totalPages = $state(0);

    function nextPage() {
        if (currentPage < totalPages) currentPage++;
    }

    function prevPage() {
        if (currentPage > 1) currentPage--;
    }

</script>

<style>

    .image {
        height: 90px;
        width: 100px;
        object-fit: cover;
    }

    @media (max-width: 640px) {
        .image {
        height: 100px;
        width: 500px;
        object-fit: cover;
    }
    }

</style>


    <Navbar/>

    
    <div class="max-sm:ml-0 max-sm:px-4 ml-[20%] mt-20 px-8 py-4">
        <div class="grid grid-cols-2">
            <h1 class=" text-slate-300 font-extrabold max-sm:text-6xl text-8xl">INVENTORY</h1>
            <div class="flex items-end justify-end mb-1.5">
                <button class="py-2 px-4 font-bold text-sm text-white  rounded-full bg-green-500" onclick={() => toggleModal()}>
                    ADD PRODUCT
                </button>
            </div>
        </div>
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="border border-slate-300 w-full text-center text-sm rtl:text-right text-gray-600">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="max-sm:px-8 px-4 py-3">
                            IMAGE
                        </th>
                        <th scope="col" class="max-sm:px-6 px-4 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            NAME
                        </th>
                        <th scope="col" class="px-6 py-3">
                            QUANTITY
                        </th>
                        <th scope="col" class="px-6 py-3">
                            PRICE 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {#each paginatedProducts as product }
                    <tr class="border-b border-gray-300">
                        <th scope="row" class=" max-sm:px-0 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                            {#if product.image_route}
                                <div class="flex justify-center">
                                    <img class="image w-fit" alt="item" src="/uploads/{product.image_route}">
                                </div>
                            {:else}
                                <div class="flex justify-center">
                                    <img class="image w-fit" alt="item" src="../placeholder.png">
                                </div>
                            {/if}
                        </th>
                        <td class="px-6 py-4">
                            {product.id}
                        </td>
                        <td class="px-6 py-4">
                            {product.product_name}
                        </td>
                        <td class="px-6 py-4">
                            {product.quantity}
                        </td>
                        <td class="px-6 py-4">
                            {product.price}
                        </td>
                        <td class="flex justify-center gap-4 items-center px-6 py-4">
                            <button class="rounded-full bg-green-500 p-3 w-fit" onclick={() => EditProduct(product.id)}>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M15.514 3.293a1 1 0 0 0-1.415 0L12.151 5.24a.93.93 0 0 1 .056.052l6.5 6.5a.97.97 0 0 1 .052.056L20.707 9.9a1 1 0 0 0 0-1.415l-5.193-5.193ZM7.004 8.27l3.892-1.46 6.293 6.293-1.46 3.893a1 1 0 0 1-.603.591l-9.494 3.355a1 1 0 0 1-.98-.18l6.452-6.453a1 1 0 0 0-1.414-1.414l-6.453 6.452a1 1 0 0 1-.18-.98l3.355-9.494a1 1 0 0 1 .591-.603Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button 
                                class="rounded-full bg-red-600 p-3 w-fit" 
                                onclick={() => confirmDelete(product.id)}
                            >
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    {/each}
                </tbody>
            </table>
        </div>

        {#if OpenModal}
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl overflow-y-auto relative">
                <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => toggleModal()}>
                   <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
                </button>
                <AddproductModal onClose={toggleModal} onSuccess={loadData}/>
            </div>
        </div>
        {/if}

        {#if EditModal}
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
                <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => EditModal = false}>
                   <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
                </button>
                <EditProductModal 
                    product={selectedProduct} 
                    onClose={() => EditModal = false} 
                    onSuccess={loadData}
                />
            </div>
        </div>
        {/if}

        {#if showDeleteConfirm}
            <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                    <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
                    <p class="mb-6">Are you sure you want to delete this product?</p>
                    <div class="flex justify-end gap-4">
                        <button 
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                            onclick={() => showDeleteConfirm = false}
                        >
                            Cancel
                        </button>
                        <button 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            onclick={() => handleConfirmDelete()}
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        {/if}

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

