<template>
    <div class="text-danger mb-4">
        <strong>Are you sure you want to delete this customer?</strong>
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
                    v-model="form.firstName"
                    disabled
                />
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
                    v-model="form.lastName"
                    disabled
                />
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
                    v-model="form.email"
                    disabled
                />
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
                    v-model="form.age"
                    disabled
                />
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-4 col-form-label text-md-end"
                >Date of Birth</label
            >
            <div class="col-md-6">
                <input
                    id="dob"
                    class="form-control"
                    v-model="form.dob"
                    disabled
                />
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="cancelDeleteCustomer"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-danger ms-1"
                    @click="deleteCustomer"
                >
                    Delete
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, defineEmits } from "vue";
import { useUserStore } from "../store/auth";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const { token } = storeToRefs(userStore);
const emit = defineEmits(["delete-success", "delete-cancel"]);
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

const deleteCustomer = () => {
    axios
        .delete("/api/customers/" + props.id, {
            headers: {
                Authorization: "Bearer " + token.value,
                Accept: "application/json",
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods":
                    "GET, POST, PUT, DELETE, OPTIONS",
            },
        })
        .then(() => {
            emit("delete-success");
        })
        .catch((error) => {
            console.log("Unable to delete: ", error);
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
            console.log("Unable to get customer information: ", error);
        });
};

const cancelDeleteCustomer = () => {
    emit("delete-cancel");
};

getCustomerInformation();
</script>
