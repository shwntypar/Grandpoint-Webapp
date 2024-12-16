<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess, product } = $props<{ 
        onClose: () => void,
        onSuccess: () => void,
        product: number | null
    }>();
    
    console.log(product);
    
    let formdata = $state({
        supplier_name: '',
        contact_person  : '',
        email: '',
        phone: '',
        address: '',
    });

    let suppliers: { id: number; supplier_name: string; }[] = $state([]);

    // Fetch product and supplier data on mount
    onMount(async () => {
        try {
            console.log("Fetching product ID:", product);
            const productResponse = await api.get(`getSuppliers/${product}`);
            console.log("Full response:", productResponse); // Debug full response
            
            if (productResponse.payload && productResponse.payload.length > 0) {
                const productData = productResponse.payload.find((p: any) => p.id === product);
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


    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();
        console.log("Submit started", formdata);

        try {
            // Send as JSON instead of FormData
            const response = await api.post(`updateSupplier/${product}`, {
                supplier_name: formdata.supplier_name,
                contact_person: formdata.contact_person,
                email: formdata.email,
                phone: formdata.phone,
                address: formdata.address
            });
            
            console.log("Response:", response);
            
            if (response?.status?.remarks === "success") {
                onSuccess();
                onClose();
            }
        } catch (error) {
            console.error("Error updating supplier:", error);
            console.log("FormData being sent:", formdata); // Debug
        }
    }

</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    <form onsubmit={handleSubmit}>
        <div class="flex space-x-4 h-fit">
            <!-- Left Column - Form Inputs -->
            <div class="w-full grid grid-cols-4 gap-4">
                <!-- Supplier Name -->
                <div class="col-span-2">
                    <label class="font-medium">Supplier Name</label>
                    <input 
                        class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                        bind:value={formdata.supplier_name}
                        placeholder="Enter supplier name"
                    >
                </div>

                <!-- Contact Person -->
                <div class="col-span-2">
                    <label class="font-medium">Contact Person</label>
                    <input 
                        class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                        bind:value={formdata.contact_person}
                        placeholder="Enter contact person"
                    >
                </div>

                <!-- Email -->
                <div class="col-span-2">
                    <label class="font-medium">Email</label>
                    <input 
                        type="email"
                        class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                        bind:value={formdata.email}
                        placeholder="Enter email"
                    >
                </div>

                <!-- Phone -->
                <div class="col-span-2">
                    <label class="font-medium">Phone</label>
                    <input 
                        class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                        bind:value={formdata.phone}
                        placeholder="Enter phone number"
                    >
                </div>

                <!-- Address (2 rows) -->
                <div class="col-span-4">
                    <label class="font-medium">Address</label>
                    <textarea 
                        class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg resize-none h-24"
                        bind:value={formdata.address}
                        placeholder="Enter complete address"
                    ></textarea>
                </div>
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

