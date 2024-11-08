<script setup>
import subscriptionForm from './components/subscriptionForm.vue';

</script>

<template>
    <div id="app">
        <subscriptionForm id="form" />
    </div>
</template>

<style scoped>

body {
    font-family: 'Arial', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
}

#app {
    width: 100vw;
    height: 100vh;
    background-color: #302d2d;
}

#form {
    width: 100%;
    height: 100%;
}
</style>
