<script>

    let tabs = [
        {name: "Inventory", route:"/staff/inventory", icon: "../database.png"} ,
        {name: "Suppliers", route:"/staff/suppliers", icon:"../users.png" },
        {name: "Users", route:"/staff/users", icon:"../users.png" },
        {name: "Transactions", route:"/staff/transactions", icon:"../users.png" },
        {name: "Transaction History", route:"/staff/Transaction-History", icon:"../users.png" }
    ];

    let sidebarHidden = true;

</script>   

<style>

    .icon {
        height: 20px;
        width: 22px;
    }
    
    .background {
        background-size: contain;
        background-image: url(../bg.png);
    }

</style>

<div class=" max-md:hidden">
    <button on:click={() => (sidebarHidden = !sidebarHidden)}>

    </button>
</div>

<div class="w-[20%] h-full bg-red-800 fixed top-0 bottom-0 left-0 max-sm:hidden">
    <div class="flex flex-col justify-center w-full px-4 py-2 h-[20%] text-center background">
        <img src="../GRANDPOINT.png" alt="Grandpoint Logo">    
    </div>

    <!-- TABS -->
    <div class="px-6 mt-6 flex flex-col space-y-3">
        {#each tabs as tab }
            <a class="hover:bg-red-950 py-2 px-4 rounded-lg text-white font-bold border-2 justify items-center flex flex-row gap-4" href="{tab.route}"><img class="icon" alt="icon" src={tab.icon}> {tab.name}</a>
        {/each}
    </div>
</div>