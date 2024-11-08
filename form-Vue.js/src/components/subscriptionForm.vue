<script setup>
import { ref, computed } from 'vue';
import FirstStep from './steps/firstStep.vue';
import SecondStep from './steps/secondStep.vue';
import ThirdStep from './steps/thirdStep.vue';
import FourthStep from './steps/fourthStep.vue';
import useForm from '../composables/form.js';

const { values } = useForm()

const steps = ref([
    FirstStep,
    SecondStep,
    ThirdStep,
    FourthStep
])

const step = ref(0)

const previousStep = () => {
    if (step.value > 0) {
        step.value--;
    }
}

const nextStep = () => {
    if (step.value < steps.value.length - 1) {
        step.value++;
    }
}

const progressWidth = computed(() => {
    return ((step.value) / (steps.value.length - 1)) * 100 + '%';
})
</script>

<template>
    <div id="form">
        <h2>Formulaire de Partenariat Commercial</h2>

        <nav id="progress-bar">
            <div id="progress" :style="{ width: progressWidth }"></div>
        </nav>

        <component id="stepDisplay" v-bind:is="steps[step]" />
        <button type="button" class="styled-button" id="previous-button" @click="previousStep">
            Précédent
        </button>
        <button type="button" class="styled-button" id="next-button" @click="nextStep">
            Suivant
        </button>
    </div>
    <!-- components dynamiques -->
    <component v-bind:is="steps[step]" v-bind:formValues="values"></component>

</template>

<style scoped>
#progress-bar {
    width: 100%;
    background-color: #f3f3f3;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 20px;
}

#progress {
    height: 20px;
    background-color: #4CAF50;
    width: 0;
    transition: width 0.75s ease;
}

.styled-button {
    background-color: #4CAF50;
    /* Green */
    border: none;
    color: white;
    padding: 10px 15px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 12px;
    transition: background-color 0.2s ease;
    width: 150px;
    /* Fixed width */
}

.styled-button:hover {
    background-color: #357739;
}

#form {
    max-width: 70%;
    color: #f3f3f3;
    margin: auto;
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 10px;
    background-color: #302d2d;
}
</style>