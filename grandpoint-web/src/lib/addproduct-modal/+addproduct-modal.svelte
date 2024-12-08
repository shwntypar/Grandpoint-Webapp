<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    
    let formdata = {
        product_name: "",
        price: "",
        description: "",
        quantity: "",
        views: "",
        supplier_id: ""
    }

    export let onClose:() => void
    let isOpen = false;

    async function handleSubmit(event:SubmitEvent){
        event.preventDefault
        try {
            const response = await api.post("AddProducts", formdata);
            console.log("Successfully added");
            onClose();
        } catch (error) {
            console.log("Error Adding Product");
        }
    } 

</script>

<div class="mt-5">
    <div class="grid grid-cols-3 gap-2">
        <form on:submit|preventDefault={handleSubmit}>
            <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg"    bind:value={formdata.product_name} placeholder="Product Name">
            <input class="px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.price} placeholder="Price">
            <input class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.description} placeholder="Description">
            <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.supplier_id} placeholder="Select Supplier">
            <input class=" px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.quantity} placeholder="Quantity">
            <button type="submit">Add Product</button>
            <!-- <input class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={}type="file" placeholder="Product Image"> -->
        </form>
    </div>
</div>

