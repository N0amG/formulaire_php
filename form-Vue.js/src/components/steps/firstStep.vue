<script setup>
const props = defineProps(['steps']);

</script>

<template>
    <form>
        <div>
            <label for="partnerNumber">Prénom</label>
            <div>
                <input input
                type="number"
                id="partnerNumber"
                name="partnerNumber"
                placeholder="Nombre de partenaires"
                min="1"
                max="100"
                required>
                v-model="props.steps.partnerNumber">
            </div>
        </div>
    </form>
</template>