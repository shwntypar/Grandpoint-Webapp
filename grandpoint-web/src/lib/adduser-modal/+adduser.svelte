<script lang="ts">
    import { api } from "$lib/services/api.ts";

    const { onClose, onSuccess } = $props<{ 
        onClose: () => void,
        onSuccess: () => void 
    }>();

    let formdata = {
        first_name: "",
        last_name: "",
        username: "",
        email: ""
    };

    async function handleSubmit(event: SubmitEvent) {
        event.preventDefault();
        try {
            const response = await api.post("AddUsers", formdata);
            console.log("Successfully added");
            onSuccess();
            onClose();
        } catch (error) {
            console.log("Error Adding User");
        }
    }
</script>

<div class="mt-5">
    <h1 class="text-2xl font-bold mb-6">Add User</h1>
    <form class="grid grid-cols-4 gap-2" on:submit|preventDefault={handleSubmit}>
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.first_name} placeholder="First Name">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.last_name} placeholder="Last Name">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.username} placeholder="Username">
        <input class="col-span-2 px-2 py-1 border-2 border-slate-300 rounded-lg" bind:value={formdata.email} placeholder="Email Address">
        <button class="flex items-center justify-center gap-2 rounded-full bg-green-500 text-white px-2 py-1" type="submit">Add 
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </button>
    </form>
</div>