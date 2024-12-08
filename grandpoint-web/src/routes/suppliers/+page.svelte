<script lang=ts>
    import Addsupplier from "$lib/addsupplier-modal/+addsupplier.svelte";
    import Navbar from "$lib/navbar/navbar.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";


    let Inventory = [
        {name: "John Quiroz", username: "JohnHoenn", email: "akosi@gmail.com"},
        {name: "Xai Dave", username: "XyrellDiv", email: "akosi@gmail.com"},
        {name: "Peter Parker", username: "PetPar", email: "akosi@gmail.com"},
    ]


    async function loadData(){

        try{
            const response = await api.get("getSuppliers");
            suppliers = response.payload;
            console.log(suppliers);
        }catch(e:any){
            console.log(e);
        }
    }

    let suppliers:any = $state();

    onMount(loadData);

    let OpenModal = $state(false); // This controls whether the modal is shown or not

// Function to toggle modal visibility
    function toggleModal() {
        OpenModal = !OpenModal;
    }
</script>

<style>
    .image {
        height: 90px;
        width: 100px;
    }
</style>

<Navbar/>

<div class="mt-20 ml-[20%] px-8 py-4">
    <div class="grid grid-cols-2">
        <h1 class=" text-slate-300 font-extrabold text-8xl">SUPPLIERS</h1>
        <div class="flex items-end justify-end mb-1.5">
            <button class="py-2 px-4 flex items-center gap-2 font-bold text-sm text-white  rounded-full bg-green-500" onclick={() => toggleModal()}> 
                ADD SUPPLIER
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="border w-full border-slate-300 text-center text-sm rtl:text-right text-gray-600">
            <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6">
                        SUPPLIER NAME
                    </th>
                    <th scope="col" class="px-6 py-3">
                        CONTACT PERSON
                    </th>
                    <th scope="col" class="px-6 py-3">
                        EMAIl
                    </th>
                    <th scope="col" class="px-6 py-3">
                        PHONE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ADDRESS
                    </th>
                    <th scope="col" class="flex justify-center px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                {#each suppliers as supplier }
                <tr class="border-b border-gray-300">
                    <td class="px-6 py-4">
                        {supplier.supplier_name}
                    </td>
                    <td class="px-6 py-4">
                        {supplier.contact_person}
                    </td>
                    <td class="px-6 py-4">
                        {supplier.email}
                    </td>
                    <td class="px-6 py-4">
                        {supplier.phone}
                    </td>
                    <td class="px-6 py-4">
                        {supplier.address}
                    </td>
                    <td class="flex justify-center px-6 py-4">
                        <div class="flex w-fit justify-center gap-4 items-center">
                            <a href="#" class="rounded-full bg-green-500 p-3 w-fit"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M15.514 3.293a1 1 0 0 0-1.415 0L12.151 5.24a.93.93 0 0 1 .056.052l6.5 6.5a.97.97 0 0 1 .052.056L20.707 9.9a1 1 0 0 0 0-1.415l-5.193-5.193ZM7.004 8.27l3.892-1.46 6.293 6.293-1.46 3.893a1 1 0 0 1-.603.591l-9.494 3.355a1 1 0 0 1-.98-.18l6.452-6.453a1 1 0 0 0-1.414-1.414l-6.453 6.452a1 1 0 0 1-.18-.98l3.355-9.494a1 1 0 0 1 .591-.603Z" clip-rule="evenodd"/>
                            </svg>
                            </a>
                            <a href="#" class="rounded-full bg-red-600 p-3 w-fit" ><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                            </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                {/each}
            </tbody>
        </table>
    </div>

    {#if OpenModal}
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => toggleModal()}>
                   <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
                </button>
                <Addsupplier onClose={toggleModal}/>
            </div>
        </div>
    {/if}

</div>
