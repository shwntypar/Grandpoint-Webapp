<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const {supplier } = $props<{ 
        supplier: number | null
    }>();
    
    console.log(supplier);

    /* let images: File | null = null;
    let imagePreview: string | null = $state(null);
    let errorMessage: string | null = null; */

    let supplier_info = $state([]);

    // Fetch product and supplier data on mount
    onMount(async () => {
        try {
            console.log("Fetching Supplier ID:", supplier);
            const suppliersResponse = await api.get(`getSuppliers/${supplier}`);
            console.log("Full response:", suppliersResponse); // Debug full response
            
            if (suppliersResponse.payload && suppliersResponse.payload.length > 0) {
                const SupplierData = suppliersResponse.payload.find((p: { id: number }) => p.id === supplier);
                if (SupplierData) {
                    supplier_info = SupplierData;
                    console.log("Matched product data:", supplier_info);
                }
            }

            // Fetch suppliers list
            /* const suppliersResponse = await api.get('getSuppliers');
            if (suppliersResponse.payload) {
                suppliers = suppliersResponse.payload;
            } */
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });


    /* Preview the image upload */
    /* function handleImageChange(e: Event) {
        const target = e.target as HTMLInputElement;
        const file = target.files?.[0];
        if (file) {
            images = file;
            imagePreview = URL.createObjectURL(file);
            errorMessage = null;
        }
    } */

    /* async function handleSubmit(event: SubmitEvent) {
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
                formData.append('url', formdata.url);

                console.log("FormData created:", Object.fromEntries(formData)); // Debug
                const response = await api.post(`updateProduct/${product}`, formData);
                console.log("Response received:", response); // Debug
                
                if (response?.status?.remarks === "success") {
                    onSuccess();
                    onClose();
                } else {
                    errorMessage = response?.status?.message || "An error occurred";
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
                formData.append('url', formdata.url);

                console.log("FormData with image created:", Object.fromEntries(formData)); // Debug
                const response = await api.post(`updateProduct/${product}`, formData);
                console.log("Response received:", response); // Debug
                
                if (response?.status?.remarks === "success") {
                    onSuccess();
                    onClose();
                } else {
                    errorMessage = response?.status?.message || "An error occurred";
                }
            }
        } catch (error) {
            console.error("Error updating product:", error);
            errorMessage = "An error occurred while updating the product.";
        }
    } */

</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Supplier Info</h1>
            <div class="w-full grid grid-cols-4 gap-4">
                <div class="col-span-2">
                    <p>Supplier Name</p>
                    <p class=" font-bold text-xl">{supplier_info.supplier_name}</p>
                </div>
                <div class="col-span-2">
                    <p>Contact Person</p>
                    <p class=" font-bold text-xl">{supplier_info.contact_person}</p>
                </div>
                <div class="col-span-2">
                    <p>Email</p>
                    <span class=" font-bold">{supplier_info.email}</span>
                </div>
                <div class="col-span-2">
                    <p>Phone</p>
                    <p class=" font-bold">{supplier_info.phone}</p>
                </div>
                <div class="col-span-4">
                    <p>Address</p>
                    <p class=" font-bold">{supplier_info.address}</p>
                </div>
            </div>
</div>

