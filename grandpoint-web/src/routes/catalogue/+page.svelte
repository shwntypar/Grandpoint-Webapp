<script lang="ts">
    import CatalogueNavbar from "$lib/catalogue-navbar/catalogue-navbar.svelte";
    import ProductPreview from "$lib/product-preview/+product-preview.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    let products:any = $state();
    let viewdetails = $state(false);
    let selectedProductId: number | null = $state(null);

    onMount(async () => {
        await loadData();
    });

    async function viewProduct(product: any) {
        console.log("Selected product:", product);
        selectedProductId = product.id;
        viewdetails = true;
    }
    
    async function loadData(){

        try{
            const response = await api.get("getProducts");
            products = response.payload;
            console.log(products);
        }catch(e:any){
            console.log(e);
        }
    }

</script>
<style>
 img{
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    height: 180px;
    width: 200px;
    object-fit: cover;
 }
</style>

<CatalogueNavbar/>

<div class="h-full w-full ">
    
    <div class="p-4 grid grid-row grid-cols-6 gap-4 mx-20 items-center">
        <!-- Product Placeholder -->
        {#each products as product}
            {#if product.url != null}
                <div class=" bg-slate-200 w-auto rounded-xl h-[250px] shadow-md border border-slate-200">
                    <button onclick={() => viewProduct(product)}>
                        <div class="w-full">
                            {#if product.image_route == null}
                            <img src="placeholder.png" alt="halaman">
                            {:else}
                            <img src="/uploads/{product.image_route}" alt="halaman">
                            {/if}
                        </div>
                        <div class="p-3 text-start">
                            <p class="font-semibold text-sm truncate max-w-[125px]">
                                {product.product_name}
                            </p>
                            <p class="text-sm font-bold text-red-500">₱{product.price}</p>
                        </div>
                    </button>
                </div>
            {/if}
        {/each}

        <!-- Modal -->
        {#if viewdetails}
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl overflow-y-auto relative">
                <!-- svelte-ignore a11y_consider_explicit_label -->
                <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => viewdetails = false}>
                   <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
                </button>
                <ProductPreview 
                    id={selectedProductId} 
                    onClose={() => viewdetails = false} 
                    onSuccess={loadData}
                />
            </div>
        </div>
        {/if}
        
    </div>
</div>

