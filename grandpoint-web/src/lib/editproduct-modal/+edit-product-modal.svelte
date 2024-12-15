<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess, product } = $props<{ 
        onClose: () => void,
        onSuccess: () => void,
        product: number
    }>();
    
    console.log(product);

    let images: File | null = null;
    let imagePreview: string | null = $state(null);
    let error = '';
    
    let formdata = $state({
        product_name: '',
        price: '',
        description: '',
        quantity: '',
        supplier_id: '',
        image_route: '' 
    });

    let suppliers: { id: number; supplier_name: string; }[] = $state([]);

    // Fetch product and supplier data on mount
    onMount(async () => {
        try {
            console.log("Fetching product ID:", product);
            const productResponse = await api.get(`getProducts/${product}`);
            console.log("Full response:", productResponse); // Debug full response
            
            if (productResponse.payload && productResponse.payload.length > 0) {
                const productData = productResponse.payload.find(p => p.id === product);
                if (productData) {
                    formdata = productData;
                    console.log("Matched product data:", formdata);
                }
            }

            // Fetch suppliers list
            const suppliersResponse = await api.get('getSuppliers');
            if (suppliersResponse.payload) {
                suppliers = suppliersResponse.payload;
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });


    /* Preview the image upload */
    function handleImageChange(e: Event) {
        const target = e.target as HTMLInputElement;
        const file = target.files?.[0];
        if (file) {
            images = file;
            imagePreview = URL.createObjectURL(file);
        }
    }

    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();
        console.log("Submit started"); // Debug

        try {
            if (!images) {
                console.log("Updating without new image"); // Debug
                const formData = new FormData();
                formData.append('product_name', formdata.product_name);
                formData.append('price', formdata.price);
                formData.append('description', formdata.description);
                formData.append('quantity', formdata.quantity);
                formData.append('supplier_id', formdata.supplier_id);
                
                console.log("FormData created:", Object.fromEntries(formData)); // Debug
                const response = await api.post(`updateProduct/${product}`, formData);
                console.log("Response received:", response); // Debug
                
                if (response?.status?.remarks === "success") {
                    onSuccess();
                    onClose();
                }
            } else {
                console.log("Updating with new image"); // Debug
                const formData = new FormData();
                formData.append('product_name', formdata.product_name);
                formData.append('price', formdata.price);
                formData.append('description', formdata.description);
                formData.append('quantity', formdata.quantity);
                formData.append('supplier_id', formdata.supplier_id);
                formData.append('images', images);
                
                console.log("FormData with image created:", Object.fromEntries(formData)); // Debug
                const response = await api.post(`updateProduct/${product}`, formData);
                console.log("Response received:", response); // Debug
                
                if (response?.status?.remarks === "success") {
                    onSuccess();
                    onClose();
                }
            }
        } catch (error) {
            console.error("Error updating product:", error);
        }
    }

</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    <form onsubmit={handleSubmit}>
        <div class="flex space-x-4 h-fit">
            <!-- Left Column - Form Inputs -->
            <div class="w-1/2 grid grid-cols-4 gap-1">
                <label class="col-span-4 font-medium">Product Name</label>
                <input class="col-span-3 px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.product_name}>
                <input class="px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.price} placeholder="Price">
                
                <label class="col-span-4 font-medium mt-2">Description</label>
                <textarea 
                    class="col-span-4 px-2 py-1.5 border-2 border-slate-300 rounded-lg resize-none h-24" 
                    bind:value={formdata.description} 
                    placeholder="Enter product description"
                ></textarea>
                
                <div class="col-span-3">
                    <label class="font-medium">Supplier</label>
                    <select class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.supplier_id}>
                        <option value="" disabled selected>Select Supplier</option>
                        {#each suppliers as supplier}
                            <option value={supplier.id}>{supplier.supplier_name}</option>
                        {/each}
                    </select>
                </div>
                <div>
                    <label class="font-medium">Quantity</label>
                    <input class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.quantity} placeholder="Qty">
                </div>
            </div>

            <!-- Right Column - Image Upload -->
            <div class="w-1/2 border-2 border-slate-300 rounded-lg p-4">
                <input 
                    class="hidden" 
                    id="imageInput"
                    type="file" 
                    accept="image/*"
                    onchange={handleImageChange}
                >
                <label 
                    for="imageInput" 
                    class="cursor-pointer w-full h-full"
                >
                    <div class="h-full w-full bg-gray-50 rounded-lg flex flex-col items-center justify-center border-2 border-dashed border-gray-300 hover:bg-gray-100 transition-colors">
                        {#if imagePreview}
                            <img src={imagePreview} alt="Preview" class="max-h-60 object-contain">
                        {:else}
                            <div class="text-center text-gray-500 p-8">
                                <img src="../uploads/{formdata.image_route}" alt="Preview" class="max-h-60 object-contain">
                            </div>
                        {/if}
                    </div>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600" type="submit">
                Update Product
            </button>
        </div>
    </form>
</div>

