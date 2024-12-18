<script lang="ts">
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess, id } = $props<{ 
        onClose: () => void,
        onSuccess: () => void,
        id: number | null
    }>();

    let product: any = $state();
    let showRedirectPopup = $state(false);

    onMount(async () => {
        try {
            console.log("Fetching product ID:", id);
            const productResponse = await api.get(`getProducts/${id}`);
            if (productResponse.payload && productResponse.payload.length > 0) {
                const productData = productResponse.payload.find((p: any) => p.id === id);
                if (productData) {
                    product = productData;
                    console.log("Fetched product:", product);
                }
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    function handleRedirect() {
        if (product?.url) {
            window.location.href = product.url;
        }
    }
</script>

<div class="flex space-x-8 p-6">
    <!-- Left Column - Image -->
    <div class="w-1/2">
        <div class="border-2 border-slate-300 rounded-lg p-4">
            <img 
                src={product?.image_route ? `/uploads/${product.image_route}` : '/placeholder.png'} 
                alt="Product" 
                class="w-full h-96 object-contain"
            >
        </div>
    </div>

    <!-- Right Column - Product Info -->
    <div class="w-1/2 space-y-4">
        <h1 class="text-2xl font-bold">{product?.product_name || 'Loading...'}</h1>
        
        <div class="text-xl font-bold text-red-500">
            ₱{product?.price || '0'}
        </div>
        
        <div class="text-gray-600">
            Stock: {product?.quantity || '0'}
        </div>
        
        <div class="border-t border-gray-200 py-4">
            <h2 class="font-bold mb-2">Description</h2>
            <p class="text-gray-600">{product?.description || 'No description available'}</p>
        </div>

        <button 
            class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600"
            onclick={() => showRedirectPopup = true}
        >
            Redirect to Source
        </button>
    </div>
</div>

<!-- Redirect Popup -->
{#if showRedirectPopup}
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-bold mb-4">Redirect Confirmation</h2>
            <p>You will be redirected to an external website. Do you want to continue?</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button 
                    class="px-4 py-2 bg-gray-200 rounded-lg"
                    onclick={() => showRedirectPopup = false}
                >
                    Cancel
                </button>
                <button 
                    class="px-4 py-2 bg-green-500 text-white rounded-lg"
                    onclick={handleRedirect}
                >
                    Yes, Continue
                </button>
            </div>
        </div>
    </div>
{/if}