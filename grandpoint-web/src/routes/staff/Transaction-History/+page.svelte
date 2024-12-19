<script lang="ts">
    import Navbar from "$lib/navbar/navbar.svelte";
    import { api } from "$lib/services/api.ts";
    import { onMount } from "svelte";

    let transactions:any = $state([]); // Variable to hold transactions

    async function loadTransactions() {
        try {
            const response = await api.get("getTransactions"); // Fetch transaction receipts
            transactions = response.payload; // Update the transactions variable
        } catch (error) {
            console.error("Error loading transactions:", error);
        }
    }

    onMount(() => {
        loadTransactions();
    });
</script>

<Navbar />

<div class="ml-[20%] mt-20 px-8 py-4">

    <h1 class="text-3xl font-bold mb-6 text-center">Transaction Receipts</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#if transactions.length === 0}
            <div class="col-span-1 text-center text-gray-500">No transaction receipts available.</div>
        {:else}
            {#each transactions as transaction}
                <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 border border-slate-300">
                    <h2 class="text-xl font-semibold text-gray-800">Transaction ID: {transaction.id}</h2>
                    <p class="text-gray-600">Date: {transaction.transaction_date}</p>
                    <p class="text-gray-600">Total Amount: <span class="font-bold">₱{transaction.total_amount}</span></p>
                    <p class="text-gray-600">Prepared By: {transaction.prepared_by}</p>
                    <p class="text-gray-600">Status: <span class="font-semibold">{transaction.status}</span></p>

                    <h3 class="mt-4 font-bold text-gray-700">Items:</h3>
                    <ul class="list-disc list-inside pl-5">
                        {#each transaction.items as item}
                            <li class="text-gray-600">
                                {item.quantity} x {item.product_name} - ₱{item.price} (Subtotal: ₱{item.subtotal})
                            </li>
                        {/each}
                    </ul>
                </div>
            {/each}
        {/if}
    </div>
</div>
