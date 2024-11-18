<script lang=ts>
 import { goto } from "$app/navigation";

 function login (){
    goto ('/inventory');
 }
</script>

<style>
 .background {
    background-size: contain;
    background-image: url(bg.png);
 }
</style>

<div class="background flex flex-col justify-center items-center h-screen w-full">
    <div class="w-[45%] h-full justify-center flex flex-col items-center">
        <img src="GRANDPOINT.png" alt="grandpoint">
        <div class="flex flex-col items-center bg-slate-200 border border-black rounded-2xl w-[90%]">
            <div class="w-full  text-center border-black border-b py-2" >
                <span class=" text-3xl font-bold">LOGIN</span>
            </div>
            <div class="w-[85%] py-8 space-y-4">
                <div class="">
                    <label for="default-input" class="block text-sm font-bold ms-2 text-gray-900 ">USERNAME</label>
                    <input type="text" placeholder="ENTER YOUR USERNAME" id="default-input" class=" p-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                </div>
                <div class="">
                    <label for="default-input" class="block text-sm font-bold ms-2 text-gray-900 ">PASSWORD</label>
                    <input type="text" placeholder="ENTER YOUR PASSWORD" id="default-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                </div>
                <div class="flex justify-end">
                    <button on:click={() => login()}>LOGIN</button>
                </div>
            </div>
        </div>
    </div>
</div>