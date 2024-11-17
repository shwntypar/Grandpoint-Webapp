<script lang=ts>
    import Navbar from "$lib/navbar/navbar.svelte";

    let Inventory = [
        {img: "placeholder.png", name:" Sample item", qty:"6", price:"$190"},
        {img: "placeholder.png", name:" Sample item", qty:"1", price:"$190"},
        {img: "placeholder.png", name:" Sample item", qty:"1", price:"$190"}
    ]

</script>

<style>
    .image {
        height: 80px;
        width: 80px;
    }
</style>

<Navbar/>

<div class=" h-full ml-[20%] px-8 py-4">
    <h1 class=" text-slate-300 font-extrabold text-8xl">INVENTORY</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="border border-slate-300 w-full text-center text-sm rtl:text-right text-gray-600">
            <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-10 py-3">
                        IMAGE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NAME
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                {#each Inventory as item }
                <tr class="border-b border-gray-300">
                    <th scope="row" class="px-10 flex w-5/6 justify-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <img class="image "  alt="item" src={item.img}>
                    </th>
                    <td class="px-6 py-4">
                        {item.name}
                    </td>
                    <td class="px-6 py-4">
                        {item.qty}
                    </td>
                    <td class="px-6 py-4">
                        {item.price}
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                {/each}
            </tbody>
        </table>
    </div>

</div>
