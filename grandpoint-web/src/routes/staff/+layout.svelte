<script lang="ts">
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import { auth } from "$lib/stores/auth.ts";
  
    let Authenticated = false;

    onMount(() => {
      auth.initialize();
  
      if (!$auth.isAuthenticated) {
        goto("/login-page");
        Authenticated = false;
      }else{
        Authenticated = true;
      }
    });
  </script>
  
  {#if Authenticated = true}
    <slot />
  {/if}