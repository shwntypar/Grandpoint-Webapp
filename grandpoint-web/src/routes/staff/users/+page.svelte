<script lang=ts>
    import Adduser from "$lib/adduser-modal/+adduser.svelte";
    import Navbar from "$lib/navbar/navbar.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";
    import Edituser from "$lib/edituser-modal/+edit-user-modal.svelte";

    let OpenModal = $state(false); // This controls whether the modal is shown or not

    let showDeleteConfirm = $state(false);
    let userToDelete = $state<number | null>(null);

    let EditModal = $state(false);
    let selectedUser: number | null = $state(null);

    async function loadData() {
        try {
            const response = await api.get("getUsers");
            users = response.payload;
        } catch (e) {
            console.error("Error loading users:", e);
        }
    }

    let users:any = $state();
    onMount(loadData);

    function confirmDelete(userId: number) {
        userToDelete = userId;
        showDeleteConfirm = true;
    }

    async function handleConfirmDelete() {
        if (userToDelete) {
            try {
                await api.delete(`deleteUser/${userToDelete}`);
                await loadData();
                showDeleteConfirm = false;
            } catch (e) {
                console.error('Error deleting user:', e);
            }
        }
    }

// Function to toggle modal visibility
    function toggleModal() {
        OpenModal = !OpenModal;
    }

    function EditUser(user: any) {
        selectedUser = user.id;
        EditModal = true;
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
        <h1 class=" text-slate-300 font-extrabold text-8xl">USERS</h1>
        <div class="flex items-end justify-end mb-1.5">
            <button class="py-2 px-4 font-bold text-sm text-white  rounded-full bg-green-500" onclick={() => toggleModal()}> 
                ADD USER
            </button>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="border w-full border-slate-300 text-center text-sm rtl:text-right text-gray-600">
            <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6">
                        NAME
                    </th>
                    <th scope="col" class="px-6 py-3">
                        USERNAME
                    </th>
                    <th scope="col" class="px-6 py-3">
                        EMAIl
                    </th>
                    <th scope="col" class="flex justify-center px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                {#each users as user }
                <tr class="border-b border-gray-300">
                    <td class="px-6 py-4">
                        {user.first_name} {user.last_name}
                    </td>
                    <td class="px-6 py-4">
                        {user.username}
                    </td>
                    <td class="px-6 py-4">
                        {user.email}
                    </td>
                    <td class="flex justify-center px-6 py-4">
                        <div class="flex w-fit justify-center gap-4 items-center">
                            <a href="#" class="rounded-full bg-green-500 p-3 w-fit" onclick={() => EditUser(user)}><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M15.514 3.293a1 1 0 0 0-1.415 0L12.151 5.24a.93.93 0 0 1 .056.052l6.5 6.5a.97.97 0 0 1 .052.056L20.707 9.9a1 1 0 0 0 0-1.415l-5.193-5.193ZM7.004 8.27l3.892-1.46 6.293 6.293-1.46 3.893a1 1 0 0 1-.603.591l-9.494 3.355a1 1 0 0 1-.98-.18l6.452-6.453a1 1 0 0 0-1.414-1.414l-6.453 6.452a1 1 0 0 1-.18-.98l3.355-9.494a1 1 0 0 1 .591-.603Z" clip-rule="evenodd"/>
                            </svg>
                            </a>
                            <button 
                                class="rounded-full bg-red-600 p-3 w-fit" 
                                onclick={() => confirmDelete(user.id)}
                            >
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                </svg>
                            </button>
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
                <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => (OpenModal = !OpenModal)}>
                   <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
                </button>
                <Adduser onClose={toggleModal} onSuccess={loadData}/>
            
            </div>
        </div>
    {/if}

    {#if EditModal}
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <button class="absolute top-2 right-2 bg-red-700 rounded-full p-1" onclick={() => (EditModal = !EditModal)}>
               <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>                  
            </button>
            <Edituser 
            user={selectedUser} 
            onClose={() => EditModal = false} 
            onSuccess={loadData}
            />
        </div>
    </div>
    {/if}


    {#if showDeleteConfirm}
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
                <p class="mb-6">Are you sure you want to delete this user?</p>
                <div class="flex justify-end gap-4">
                    <button 
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                        onclick={() => showDeleteConfirm = false}
                    >
                        Cancel
                    </button>
                    <button 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        onclick={() => handleConfirmDelete()}
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    {/if}

</div>
