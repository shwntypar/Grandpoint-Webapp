<script lang="ts">
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    const { onClose, onSuccess, user } = $props<{ 
        onClose: () => void,
        onSuccess: () => void,
        user: number | null
    }>();
    
    let formdata = $state({
        first_name: '',
        last_name: '',
        username: '',
        email: ''
    });

    onMount(async () => {
        try {
            const userResponse = await api.get(`getUsers/${user}`);
            if (userResponse.payload && userResponse.payload.length > 0) {
                const userData = userResponse.payload.find(u => u.id === user);
                if (userData) {
                    formdata = userData;
                }
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();

        try {
            const response = await api.post(`updateUser/${user}`, {
                first_name: formdata.first_name,
                last_name: formdata.last_name,
                username: formdata.username,
                email: formdata.email
            });
            
            if (response?.status?.remarks === "success") {
                onSuccess();
                onClose();
            }
        } catch (error) {
            console.error("Error updating user:", error);
        }
    }
</script>

<div class="">
    <h1 class="text-2xl font-bold mb-6">Edit User</h1>
    <form onsubmit={handleSubmit}>
        <div class="w-full grid grid-cols-4 gap-4">
            <div class="col-span-2">
                <label class="font-medium">First Name</label>
                <input 
                    class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                    bind:value={formdata.first_name}
                    placeholder="Enter first name"
                >
            </div>

            <div class="col-span-2">
                <label class="font-medium">Last Name</label>
                <input 
                    class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                    bind:value={formdata.last_name}
                    placeholder="Enter last name"
                >
            </div>

            <div class="col-span-2">
                <label class="font-medium">Username</label>
                <input 
                    class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                    bind:value={formdata.username}
                    placeholder="Enter username"
                >
            </div>

            <div class="col-span-2">
                <label class="font-medium">Email</label>
                <input 
                    type="email"
                    class="w-full px-2 py-1.5 border-2 border-slate-300 rounded-lg"
                    bind:value={formdata.email}
                    placeholder="Enter email"
                >
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 rounded-full bg-green-500 font-bold text-white hover:bg-green-600" type="submit">
                Update User
            </button>
        </div>
    </form>
</div>

