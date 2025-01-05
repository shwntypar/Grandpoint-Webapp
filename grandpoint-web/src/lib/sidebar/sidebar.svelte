<script>

    let tabs = [
        {name: "Inventory", route:"/staff/inventory"} ,
        {name: "Suppliers", route:"/staff/suppliers" },
        {name: "Users", route:"/staff/users" },
        {name: "Transactions", route:"/staff/transactions" },
        {name: "Transaction History", route:"/staff/Transaction-History" }
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


<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
</button>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class=" h-full bg-red-800 fixed top-0 bottom-0 left-0 max-sm:hidden">
        <div class="flex flex-col justify-center w-full px-4 py-2 h-[20%] text-center background">
            <img src="../GRANDPOINT.png" alt="Grandpoint Logo">    
        </div>
    
        <!-- TABS -->
        <div class="px-6 mt-6 flex flex-col space-y-3">
            {#each tabs as tab }
                <a class="hover:bg-red-950 py-2 px-4 rounded-lg text-white font-bold border-2 justify items-center flex flex-row gap-4" href="{tab.route}"> {tab.name}</a>
            {/each}
        </div>
    </div>
</aside>
    

