<script lang="ts">
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess, product } = $props<{ 
        onClose: () => void,
        onSuccess: () => void,
        product: number | null
    }>();

    let formdata = $state({
        supplier_name: '',
        contact_person: '',
        email: '',
        phone: '',
        address: '',
    });

    onMount(async () => {
        try {
            const productResponse = await api.get(`getSuppliers/${product}`);
            if (productResponse.payload && productResponse.payload.length > 0) {
                const productData = productResponse.payload.find((p: any) => p.id === product);
                if (productData) {
                    formdata = productData;
                }
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();
        try {
            const response = await api.post(`updateSupplier/${product}`, formdata);
            if (response?.status?.remarks === "success") {
                onSuccess();
                onClose();
            }
        } catch (error) {
            console.error("Error updating supplier:", error);
        }
    }
</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Edit Supplier</h1>
    <form onsubmit={handleSubmit}>
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-2">
                <label class="font-medium">Supplier Name</label>
                <input class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.supplier_name} placeholder="Enter supplier name">
            </div>
            <div class="col-span-2">
                <label class="font-medium">Contact Person</label>
                <input class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.contact_person} placeholder="Enter contact person">
            </div>
            <div class="col-span-2">
                <label class="font-medium">Email</label>
                <input type="email" class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.email} placeholder="Enter email">
            </div>
            <div class="col-span-2">
                <label class="font-medium">Phone</label>
                <input class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg" bind:value={formdata.phone} placeholder="Enter phone number">
            </div>
            <div class="col-span-4">
                <label class="font-medium">Address</label>
                <textarea class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg resize-none h-24" bind:value={formdata.address} placeholder="Enter complete address"></textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600" type="submit">
                Update Supplier
            </button>
        </div>
    </form>
</div>

