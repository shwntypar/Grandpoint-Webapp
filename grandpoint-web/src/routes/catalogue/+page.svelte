<script>
    import CatalogueNavbar from "$lib/catalogue-navbar/catalogue-navbar.svelte";

    let samples = [
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"},
        {img:"placeholder.png",  name:"Product Name", type:"Battery", price:"$100"}
    ]
</script>
<style>
 img{
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
 }
</style>

<CatalogueNavbar/>

<div class="h-full w-full ">
    
    <div class="p-4 grid grid-row grid-cols-6 gap-4 mx-6 items-center">
        <!-- Product Placeholder -->
        {#each samples as sample}
        <div class=" bg-slate-200 w-auto rounded-xl shadow-md border border-slate-200">
            <div class="w-full">
                <img src="{sample.img}" alt="halaman">
            </div>
            <div class="p-3">
                <p>{sample.name}</p>
                <p>{sample.type}</p>
                <p>{sample.price}</p>
            </div>
        </div>
        {/each}
    </div>
</div>

