<template>
    <form>
        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end"
                >Client ID</label
            >

            <div class="col-md-6">
                <input
                    id="id"
                    class="form-control"
                    name="id"
                    v-model="clientId"
                    disabled
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end"
                >Name</label
            >

            <div class="col-md-6">
                <input
                    id="name"
                    class="form-control"
                    name="name"
                    v-model="name"
                    disabled
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end"
                >Email Address</label
            >

            <div class="col-md-6">
                <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="email"
                    disabled
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end"
                >OAuth Enabled</label
            >

            <div class="col-md-6">
                <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="oathEnabled"
                    disabled
                />
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useUserStore } from "../store/auth";
import { storeToRefs } from "pinia";

const urlParams = new URLSearchParams(window.location.search);
const userStore = useUserStore();

const form = ref({
    email: null,
    name: null,
    id: null,
});

const {
    name,
    email,
    token,
    secret,
    callbackUrl,
    clientId,
    stateCode,
    oathEnabled,
    baseUrl,
} = storeToRefs(userStore);

const getUserData = async () => {
    if (token.value) {
        await axios
            .post("/api/user", {}, { headers: {"Authorization" : `Bearer ${token.value}`} })
            .then((response) => {
                clientId.value = response.data.data.id;
                name.value = response.data.data.name;
                email.value = response.data.data.email;
                callbackUrl.value = response.data.data.callback_url;
                clientId.value = response.data.data.id;
                stateCode.value = response.data.data.state_code;
                oathEnabled.value = response.data.data.oath_enabled;
                baseUrl.value = response.data.data.base_url;
                secret.value = response.data.data.secret;
                token.value = response.data.data.token;
            })
            .catch((error) => {
                console.log("Error getting user information: ", error);
            });
    } else {
        await axios
            .get("/user")
            .then((response) => {
                clientId.value = response.data.data.id;
                name.value = response.data.data.name;
                email.value = response.data.data.email;
                callbackUrl.value = response.data.data.callback_url;
                clientId.value = response.data.data.id;
                stateCode.value = response.data.data.state_code;
                oathEnabled.value = response.data.data.oath_enabled;
                baseUrl.value = response.data.data.base_url;
                secret.value = response.data.data.secret;
                token.value = response.data.data.token;
            })
            .catch((error) => {
                console.log("Error getting user information: ", error);
            });
    }
};

const createNewClient = async () => {
    if (!oathEnabled.value && !token.value) {
        await axios
            .post("/oauth/clients", {
                name: name.value,
                redirect: callbackUrl.value,
            })
            .then((res) => {
                secret.value = res.data.secret;

                window.open(
                    baseUrl.value +
                        "/oauth/authorize?client_id=" +
                        clientId.value +
                        "&redirect_uri=" +
                        callbackUrl.value +
                        "&response_type=code&scope=&state=" +
                        stateCode.value,
                    "_self"
                );
            })
            .catch((err) => {
                console.log("Error enabling OAuth: ", error);
            });
    }
};

const getAccessCode = async () => {
    if (urlParams.has("code") && !token.value) {
        await axios
            .post(baseUrl.value + "/oauth/token", {
                grant_type: "authorization_code",
                client_id: clientId.value,
                client_secret: secret.value,
                redirect_uri: callbackUrl.value,
                code: urlParams.get("code"),
            })
            .then((response) => {
                token.value = response.data.access_token;

                axios.post("/set-access-token", {
                    access_token: token.value,
                });
            })
            .catch((error) => {
                console.log("Unable to process authorization code");
            });
    }
};

onMounted(async () => {
    await getUserData();
    await createNewClient();
    await getAccessCode();
});
</script>
