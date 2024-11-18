<script>
    let funtions = [
        {}
    ]

    let ToggleFilter = false;

    
</script>

<div class="flex flex-row justify-between h-20 px-6 py-4 bg-slate-200 shadow-md">
    <div class="flex">
        <img src="GRANDPOINT.png" alt="logo">
    </div>
    <div class="w-[40%] border border-slate-100 shadow-md rounded-lg p-1 bg-white flex items-center">
        <input class="p-1.5 w-full outline-none" placeholder="Search for products.....">
        <button class="p-2 h-full bg-red-700 rounded-md rounded-l-none">
            <svg class="w-5 h-5 text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
              </svg>
        </button>
    </div>
    <div class="flex items-center">
        <button class="p-2.5  bg-red-700 border-2 border-red-700 rounded-full hover:border-2 hover:border-white"  on:click={() => ToggleFilter = !ToggleFilter} >
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M20 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6h-2m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4"/>
              </svg>              
        </button>
    </div>
</div>