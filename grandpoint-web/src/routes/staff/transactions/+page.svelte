<script lang=ts>
    import Navbar from "$lib/navbar/navbar.svelte";
    import AddproductModal from "$lib/addproduct-modal/+addproduct-modal.svelte";
    import EditProductModal from "$lib/editproduct-modal/+edit-product-modal.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    async function loadData(){

        try{
            const response = await api.get("getProducts");
            products = response.payload;
            console.log(products);
        }catch(e:any){
            console.log(e);
        }
    }

    let prod = {name: "test", price: 100, quantity: 1}

    let carts:any = $state(prod);

    let products:any = $state();

    onMount(() => {
        console.log("Inventory page mounted");
        loadData().catch(error => {
            console.error("Error loading data:", error);
        });
    });

</script>

<style>

    img {
        height: 90px;
        width: 100px;
        object-fit: cover;
    }
</style>


    <Navbar/>

    <div class="grid grid-cols-3 gap-4 ml-[20%] h-screen mt-20 px-8 py-4">
        <!-- Left side - Products (span 2) -->
        <div class="col-span-2 bg-slate-50 p-4 rounded-lg shadow h-auto border border-slate-300">
            <h2 class="text-xl font-bold mb-4">Products</h2>
            <div class="grid grid-cols-2 gap-4">
                {#each products as product}
                    {#if product.quantity > 0}
                        <div class="col-span-1 grid-cols-3 grid bg-white rounded-lg shadow h-auto border border-slate-300">
                            <img src="/uploads/{product.image_route}" alt="product" class=" shadow-md rounded-l-lg">
                            <div class="col-span-2 ml-3">
                                <p class="text-black text-sm">{product.product_name}</p>
                                <p class="text-black  text-xs">Price: {product.price}</p>
                                <p class="text-black text-xs">Stock: {product.product_name}</p> 
                            </div>
                        </div>
                    {/if}
                {/each}
            </div>
        </div>

        <!-- Right side - Cart (span 1) -->
        <div class="col-span-1 bg-slate-50 p-4 rounded-lg shadow h-auto border border-slate-300">
            <h2 class="text-xl font-bold mb-4">Cart</h2>
            {#each carts as cart}
                <div class="col-span-1 bg-white p-4 rounded-lg shadow h-auto border border-slate-300">
                    <h2 class="text-xl font-bold mb-4">{cart.prod.name}</h2>
                </div>
            {/each}
        </div>
    </div>

