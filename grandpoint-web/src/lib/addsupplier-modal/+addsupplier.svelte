<script lang="ts">
    import { api } from "$lib/services/api.ts";

    const { onClose, onSuccess } = $props<{ 
        onClose: () => void,
        onSuccess: () => void 
    }>();

    let formdata = {
        supplier_name: "",
        contact_person: "",
        email: "",
        phone: "",
        address: ""
    };

    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();
        try {
            const response = await api.post("AddSuppliers", formdata);
            console.log("Successfully added");
            onSuccess();
            onClose();
        } catch (error) {
            console.log("Error Adding Supplier");
        }
    }
</script>

<div class="mt-5">
    <h1 class="text-2xl font-bold mb-6">Add Supplier</h1>
    <form class="grid grid-cols-4 gap-2" on:submit|preventDefault={handleSubmit}>
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.supplier_name} placeholder="Supplier Name">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.contact_person} placeholder="Contact Person">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.email} placeholder="Email Address">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.phone} placeholder="Phone Number">
        <input class="col-span-4 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.address} placeholder="Address">
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600" type="submit">
                Add Supplier
            </button>
        </div>
    </form>
</div>

