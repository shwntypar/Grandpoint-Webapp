<script>

    let tabs = [
        {name: "Inventory", route:"/", icon: "database.png"} ,
        {name: "Users", route:"/", icon:"users.png" }
    ];

</script>   

<style>

    .icon {
        height: 20px;
        width: 22px;
    }
    
    .background {
        background-size: contain;
        background-image: url(bg.png);
    }

</style>

<div class="w-[20%] h-full bg-red-800 absolute left-0">
    <div class="flex flex-col justify-center w-full px-4 py-2 h-[20%] text-center background">
        <img src="GRANDPOINT.png" alt="tnginamo">    
    </div>

    <!-- TABS -->
    <div class="px-6 mt-6 flex flex-col space-y-3">
        {#each tabs as tab }
            <a class="hover:bg-red-950 py-2 px-4 rounded-lg text-white font-bold border-2 justify items-center flex flex-row gap-4" href="tab.route"><img class="icon" alt="icon" src={tab.icon}> {tab.name}</a>
        {/each}
    </div>
</div>