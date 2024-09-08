<template>
    <form>
        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end"
                >Email Address</label
            >

            <div class="col-md-6">
                <input
                    id="email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': formError.email }"
                    name="email"
                    v-model="form.email"
                    @keyup="formError.email = null"
                    required
                    autocomplete="email"
                    autofocus
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
            <label for="password" class="col-md-4 col-form-label text-md-end"
                >Password</label
            >

            <div class="col-md-6">
                <input
                    id="password"
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': formError.password }"
                    @keyup="formError.password = null"
                    name="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <span
                    v-if="formError.password"
                    class="invalid-feedback"
                    role="alert"
                >
                    <strong>{{ formError.password }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="button" class="btn btn-primary" @click="login">
                    Login
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref } from "vue";
import { useUserStore } from "../store/auth";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const form = ref({
    email: null,
    password: null,
});
const formError = ref({
    email: null,
    password: null,
});
const { name, email, token, secret, callbackUrl, clientId, stateCode } =
    storeToRefs(userStore);
const login = () => {
    axios({
        method: "post",
        url: "/api/login",
        data: form.value,
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
    })
        .then((response) => {
            name.value = response.data.data.name;
            email.value = response.data.data.email;
            callbackUrl.value = response.data.data.callback_url;
            clientId.value = response.data.data.id;
            stateCode.value = response.data.data.state_code;
        })
        .catch((error) => {
            if (error.response?.data?.errors) {
                for (var key in error.response?.data?.errors) {
                    if (error.response?.data?.errors.hasOwnProperty(key)) {
                        formError.value[key] =
                            error.response?.data?.errors[key][0];
                    }
                }
            }
        });
};
</script>
