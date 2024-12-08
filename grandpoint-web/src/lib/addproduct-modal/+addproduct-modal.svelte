<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose } = $props<{ onClose: () => void }>();
    
    let formdata = {
        product_name: "",
        price: "",
        description: "",
        quantity: "",
        images: null,
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
    
    async function handleSubmit(event:SubmitEvent){
        event.preventDefault
        console.log(formdata);
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
        <form class="grid grid-rows-7 grid-cols-4 gap-2" on:submit|preventDefault={handleSubmit}>
            <input class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg"    bind:value={formdata.product_name} placeholder="Product Name">
            <input class="px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.price} placeholder="Price">
            <input class=" col-span-4 row-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.description} placeholder="Description">
            <select class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.supplier_id} placeholder="Select Supplier">
                <option value="" disabled selected>Select Supplier</option>
                {#each suppliers as supplier}
                    <option value={supplier.id}>{supplier.supplier_name}</option>
                {/each}
            </select>
            <input class="px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.quantity} placeholder="Quantity">
            <input class="col-span-4 row-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.images} type="file" placeholder="Product Image">
            <button class=" col-start-4 gap-2 rounded-full bg-green-500 text-white px-2 py-1" type="submit">Update</button>
        </form>
</div>

