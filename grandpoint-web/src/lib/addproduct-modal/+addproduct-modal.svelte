<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess } = $props<{ 
        onClose: () => void,
        onSuccess: () => void 
    }>();
    
    let images: File | null = null;
    let imagePreview: string | null = $state(null);
    let error = '';
    
    let formdata = {
        product_name: "",
        price: "",
        description: "",
        quantity: "",
        images: null,
        url: "",
        views: "",
        supplier_id: ""
    }

    onMount(getSuppliers);
    
    let suppliers:any = $state();

    async function getSuppliers(){
        try {
            const response = await api.get("getSuppliers");
            suppliers = response.payload
            console.log(suppliers);
        } catch (error) {
            console.log("Error fetching suppliers");
        }
    }
    
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

        if (!images) {
            error = 'Please add an image before submitting.';
            return;
        }

        const formData = new FormData();
        formData.append('product_name', formdata.product_name);
        formData.append('price', formdata.price);
        formData.append('description', formdata.description);
        formData.append('quantity', formdata.quantity);
        formData.append('supplier_id', formdata.supplier_id);
        formData.append('images', images);
        formData.append('url', formdata.url);
        try {
            const response = await api.post("AddProducts", formData);
            console.log("Successfully added");
            onSuccess();
            onClose();
        } catch (error) {
            console.log("Error Adding Product");
        }
    } 

</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Add Product</h1>
    <form onsubmit={handleSubmit}>
        <div class="flex space-x-4 h-fit">
            <!-- Left Column - Form Inputs -->
            <div class="w-1/2 grid grid-cols-4 gap-1">
                <label class="col-span-4 font-medium">Product Name</label>
                <input class="col-span-3 px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.product_name} placeholder="Enter product name">
                <input class="px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.price} placeholder="Price">
                
                <label class="col-span-4 font-medium mt-2">Description</label>
                <textarea 
                    class="col-span-4 px-2 py-1.5 border-2 border-slate-300 rounded-lg resize-none h-24" 
                    bind:value={formdata.description} 
                    placeholder="Enter product description"
                ></textarea>
                
                <label class="col-span-4 font-medium">External Link</label>
                <input class="col-span-4 px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.url} placeholder="URL">

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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="font-medium">Click to upload image</p>
                                <p class="text-sm text-gray-400">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        {/if}
                    </div>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600" type="submit">
                Add Product
            </button>
        </div>
    </form>
</div>

