<script lang=ts>
 import { goto } from "$app/navigation";
 import { api } from "$lib/services/api.ts";
 import { auth } from "$lib/stores/auth.ts";

 let error: string | null = $state(null);
 let showErrorPopup = $state(false);
 let showSuccessPopup = $state(false);
 let formdata = {
    username: "",
    password: ""
}

async function handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    error = null;
    console.log("Login attempt:", formdata);

    try {
      const response = await api.post("login", formdata);
      console.log("Login response:", response);

      if (response?.status?.remarks === "success" && response.payload) {
        const { token, user } = response.payload;
        await auth.login(token, user);
        console.log("login successful");
        showSuccessPopup = true;
        setTimeout(() => {
          goto("/staff/inventory");
        }, 1000);
      } else {
        console.log("login failed");
        error = response.status.message || "Login failed";
        showErrorPopup = true;
      }
    } catch (err: any) {
         error = err.message;
        console.log(error);
        showErrorPopup = true;
      
    }
  }


</script>

<style>
 .background {
    background-size: contain;
    background-image: url(/bg.png);
 }
</style>

<div class="background flex flex-col justify-center items-center h-screen w-full">
        <form class="w-[45%] max-sm:w-[75%] h-full space-y-10 justify-center flex flex-col items-center" onsubmit={handleSubmit}> 
            <img src="GRANDPOINT.png" alt="grandpoint">
            <div class="flex flex-col items-center bg-slate-200 border border-black rounded-2xl w-[90%]">
                <div class="w-full  text-center border-black border-b py-2" >
                    <span class="max-sm:text-lg text-3xl font-bold">LOGIN</span>
                </div>
                <div class="w-[85%] py-8 space-y-4">
                    <div class="">
                        <label for="default-input1" class="block max-sm:text-xs text-sm font-bold ms-2 text-gray-900 ">USERNAME</label>
                        <input type="text" bind:value={formdata.username} placeholder="ENTER YOUR USERNAME" id="default-input1" class=" p-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                    </div>
                    <div class="">
                        <label for="default-input" class="block text-sm max-sm:text-xs font-bold ms-2 text-gray-900 ">PASSWORD</label>
                        <input type="text" bind:value={formdata.password} placeholder="ENTER YOUR PASSWORD" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                    </div>
                    <div class="flex justify-end">
                        <button class=" bg-red-800 font-semibold max-sm:text-xs text-white py-2 px-4 rounded-full hover:bg-red-950" type="submit">LOGIN</button>
                    </div>
                </div>
            </div>
        </form>
</div>

{#if showSuccessPopup}
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-bold text-green-600 mb-4">Login Successful</h2>
            <p>Redirecting to dashboard...</p>
        </div>
    </div>
{/if}

{#if showErrorPopup}
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-xl font-bold text-red-600 mb-4">Login Failed</h2>
            <p>{error || "Invalid username or password"}</p>
            <div class="mt-4 flex justify-end">
                <button 
                    class="px-4 py-2 bg-red-500 text-white rounded-lg"
                    onclick={() => showErrorPopup = false}
                >
                    Close
                </button>
            </div>
        </div>
    </div>
{/if}