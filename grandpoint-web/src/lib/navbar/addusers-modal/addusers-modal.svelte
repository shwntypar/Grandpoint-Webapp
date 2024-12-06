<script lang="ts">
    /* import { createEventDispatcher } from "svelte"; */
    let {close} = $props();

</script>

<div class="mt-5">
    <div class="grid grid-cols-3 gap-2">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" placeholder="Product Name">
        <input class="px-2 py-1 border-2 border-slate-300 rounded-lg" placeholder="Price">
        <input class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg" placeholder="Description">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" placeholder="Select Supplier">
        <input class=" px-2 py-1 border-2 border-slate-300 rounded-lg" placeholder="Quantity">
        <input class="col-span-3 px-2 py-1 border-2 border-slate-300 rounded-lg" type="file" placeholder="Product Image">
        
    </div>
</div>

