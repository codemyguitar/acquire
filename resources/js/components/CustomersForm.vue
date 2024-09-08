<template>
    <div v-if="showAddCustomerForm">
        <add-customer-form
            @save-success="saveSuccess"
            @cancel-add-client="cancelAddCustomer"
        />
    </div>

    <div v-else-if="showEditCustomerForm">
        <edit-customer-form
            :id="currentCustomerId"
            @cancel-edit="cancelEditCustomer"
            @edit-save-success="editSaveSuccess"
            :key="editCustomerFormKey"
        />
    </div>

    <div v-else-if="showDeleteCustomerConfirmation">
        <delete-customer-form
            :id="currentCustomerId"
            @delete-success="deleteCustomerSuccess"
            @delete-cancel="cancelDeleteCustomer"
            :key="deleteCustomerFormKey"
        />
    </div>

    <div v-else>
        <table
            v-if="customers && Object.keys(customers).length"
            class="table table-hover"
        >
            <thead>
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">DOB</th>
                    <th scope="col">Age</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key) in customers" :key="key">
                    <th scope="row">{{ item.first_name }}</th>
                    <td>{{ item.last_name }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.dob }}</td>
                    <td>{{ item.age }}</td>
                    <td>
                        <a
                            href="#"
                            class="text-default"
                            @click.default="editCustomer(item.id)"
                            >Edit</a
                        >
                        <a href="#" class="ms-1 text-danger" @click.default="deleteCustomer(item.id)"
                            >Delete</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-else class="text-center">No customers found</div>

        <button
            type="button"
            class="btn btn-primary"
            @click="showAddCustomerForm = true"
        >
            Add Customer
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useUserStore } from "../store/auth";
import { storeToRefs } from "pinia";

const userStore = useUserStore();
const customers = ref(null);
const showAddCustomerForm = ref(false);
const showEditCustomerForm = ref(false);
const showDeleteCustomerConfirmation = ref(false);
const currentCustomerId = ref(null);
const editCustomerFormKey = ref(1);
const deleteCustomerFormKey = ref(1);

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
            .post(
                "/api/user",
                {},
                { headers: { Authorization: `Bearer ${token.value}` } }
            )
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

const saveSuccess = () => {
    showAddCustomerForm.value = false;
    getCustomers();
};

const getCustomers = async () => {
    if (token.value) {
        await axios
            .get("/api/customers", {
                headers: { Authorization: `Bearer ${token.value}` },
            })
            .then((response) => {
                customers.value = response.data.data;
            })
            .catch((error) => {
                console.log("Error getting customers table", error);
            });
    }
};

const editCustomer = (id) => {
    currentCustomerId.value = id;
    editCustomerFormKey.value += 1;
    showEditCustomerForm.value = true;
};

const editSaveSuccess = async () => {
    await getCustomers();
    cancelEditCustomer();
};

const cancelEditCustomer = () => {
    currentCustomerId.value = null;
    showEditCustomerForm.value = false;
};

const cancelAddCustomer = () => {
    showAddCustomerForm.value = false;
};

const deleteCustomer = (id) => {
    currentCustomerId.value = id;
    deleteCustomerFormKey.value += 1;
    showDeleteCustomerConfirmation.value = true;
}

const deleteCustomerSuccess = async () => {
    await getCustomers();
    cancelDeleteCustomer();
}

const cancelDeleteCustomer = () => {
    currentCustomerId.value = null;
    showDeleteCustomerConfirmation.value = false;
}

onMounted(async () => {
    await getUserData();
    await getCustomers();
});
</script>
