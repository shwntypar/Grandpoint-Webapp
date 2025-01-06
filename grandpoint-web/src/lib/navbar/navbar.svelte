<script>
	import Sidebar from "$lib/sidebar/sidebar.svelte";
    import { goto } from "$app/navigation";
    import { auth } from "$lib/stores/auth.ts";

    function logout (){
        auth.logout();
        goto ('/login-page');
    }

</script>

<div class="max-sm:ml-0 max-sm:max-w-[100%] ml-[20%] fixed top-0  z-20 max-w-[80%] w-full px-6 flex flex-row justify-between items-center h-20 bg-slate-100 shadow-md">
    <Sidebar></Sidebar>

    
    <a aria-labelledby="exit"  class=" text-gray-800 bg-slate-200 hover:text-slate-50 rounded-full p-3 border border-black hover:bg-red-900 hover:border-red-900" href="/login-page" on:click={logout}> 
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
          </svg>          
    </a>
</div>