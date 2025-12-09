<template>
    <div id="app">
        <router-view />
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();

// PENTING: Panggil saat aplikasi dimuat
onMounted(() => {
    authStore.initializeAuth();
    console.log("App mounted and auth initialized");
});
</script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
}

#app {
    min-height: 100vh;
}
</style>
