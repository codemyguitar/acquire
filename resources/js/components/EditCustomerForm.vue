<template>
    <div class="text-primary mb-4">
        <strong>Edit customer information</strong>
    </div>
    <form>
        <div class="row mb-3">
            <label for="first-name" class="col-md-4 col-form-label text-md-end"
                >First Name</label
            >

            <div class="col-md-6">
                <input
                    id="first-name"
                    class="form-control"
                    :class="{ 'is-invalid': formError.firstName }"
                    name="email"
                    v-model="form.firstName"
                    @keyup="formError.firstName = null"
                    required
                />

                <span
                    v-if="formError.firstName"
                    class="invalid-feedback"
                    role="alert"
                >
                    <strong>{{ formError.firstName }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="last-name" class="col-md-4 col-form-label text-md-end"
                >Last Name</label
            >

            <div class="col-md-6">
                <input
                    id="last-name"
                    class="form-control"
                    :class="{ 'is-invalid': formError.lastName }"
                    name="email"
                    v-model="form.lastName"
                    @keyup="formError.lastName = null"
                    required
                />

                <span
                    v-if="formError.lastName"
                    class="invalid-feedback"
                    role="alert"
                >
                    <strong>{{ formError.lastName }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end"
                >Email</label
            >

            <div class="col-md-6">
                <input
                    id="email"
                    class="form-control"
                    type="email"
                    :class="{ 'is-invalid': formError.email }"
                    name="email"
                    v-model="form.email"
                    @keyup="formError.email = null"
                    required
                />

                <span
                    v-if="formError.email"
                    class="invalid-feedback"
                    role="alert"
                >
                    <strong>{{ formError.email }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="age" class="col-md-4 col-form-label text-md-end"
                >Age</label
            >

            <div class="col-md-6">
                <input
                    id="age"
                    class="form-control"
                    type="number"
                    :class="{ 'is-invalid': formError.age }"
                    name="email"
                    v-model="form.age"
                    @keyup="formError.age = null"
                    required
                />

                <span
                    v-if="formError.age"
                    class="invalid-feedback"
                    role="alert"
                >
                    <strong>{{ formError.age }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-4 col-form-label text-md-end"
                >Date of Birth</label
            >
            <div class="col-md-6">
                <Datepicker v-model="form.dob" :enable-time-picker="false" />
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="cancelSaveCustomer"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-primary ms-1"
                    @click="saveCustomer"
                >
                    Save
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, defineEmits } from "vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { useUserStore } from "../store/auth";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const { token } = storeToRefs(userStore);
const emit = defineEmits(["edit-save-success", "edit-close"]);
const props = defineProps({
    id: {
        default: 0,
        type: Number,
    },
});

const form = ref({
    firstName: null,
    lastName: null,
    email: null,
    age: null,
    dob: null,
});

const formError = ref({
    firstName: null,
    lastName: null,
    email: null,
    age: null,
    dob: null,
});

const saveCustomer = () => {
    axios
        .put("/api/customers/" + props.id, form.value, {
            headers: {
                Authorization: "Bearer " + token.value,
                'Accept': 'application/json',
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods":
                    "GET, POST, PUT, DELETE, OPTIONS",
            },
        })
        .then((response) => {
            form.value.firstName = response.data.data.first_name;
            form.value.lastName = response.data.data.last_name;
            form.value.age = response.data.data.age;
            form.value.email = response.data.data.email;
            form.value.dob = response.data.data.dob;

            emit("edit-save-success");
        })
        .catch((error) => {
            console.log("Unable to save: ", error);
        });
};

const getCustomerInformation = () => {
    axios
        .get("/api/customers/" + props.id, {
            headers: { Authorization: "Bearer " + token.value },
        })
        .then((response) => {
            form.value.firstName = response.data.data.first_name;
            form.value.lastName = response.data.data.last_name;
            form.value.age = response.data.data.age;
            form.value.email = response.data.data.email;
            form.value.dob = response.data.data.dob;
        })
        .catch((error) => {
            console.log("Unable to save: ", error);
        });
};

const cancelSaveCustomer = () => {
    emit("cancel-edit");
};

getCustomerInformation();
</script>
