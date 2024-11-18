<script>
	import Sidebar from "$lib/sidebar/sidebar.svelte";
    import { goto } from "$app/navigation";

    function logout (){
        goto ('/login-page');
    }

</script>

<Sidebar></Sidebar>

<div class="ml-[20%] px-6 flex flex-row justify-between items-center h-20 bg-slate-100 shadow-md">
    <h1 class=" text-2xl font-bold" >Welcome USER</h1>
    <a aria-labelledby="exit"  class=" text-gray-800 bg-slate-200 hover:text-slate-50 rounded-full p-3 border border-black hover:bg-red-900 hover:border-red-900" href="/login-page"> 
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
          </svg>          
    </a>
</div>