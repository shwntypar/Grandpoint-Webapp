<script>

    let tabs = [
        {name: "Inventory", route:"/" },
        {name: "Users", route:"/" }
    ];

</script>

<div class="w-[20%] h-full bg-red-800 absolute left-0 px-5">
    <div class="flex flex-col justify-center w-full py-4 h-[20%] text-center">
        <span class=" text-2xl font-bold text-white">GRANDPOINT</span>
        <span class=" font-bold text-white text-sm">GENERAL MERCHANDISE</span>
    </div>

    <!-- TABS -->
    <div class=" mt-6 flex flex-col space-y-3">
        {#each tabs as tab }
            <a class="py-2 px-4 rounded-lg text-white font-bold border" href="tab.route">{tab.name}</a>
        {/each}
    </div>
</div>