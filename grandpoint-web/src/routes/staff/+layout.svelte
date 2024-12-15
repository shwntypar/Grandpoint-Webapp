<script lang="ts">
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import { auth } from "$lib/stores/auth.ts";
  
    onMount(() => {
      auth.initialize();
  
      if (!$auth.isAuthenticated) {
        goto("/login-page");
      }
    });
  </script>

  <slot />
  