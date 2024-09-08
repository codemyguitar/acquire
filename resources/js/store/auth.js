import { defineStore } from "pinia";
import { ref } from "vue";

export const useUserStore = defineStore("user", () => {
    const clientId = ref(null);
    const token = ref(null);
    const secret = ref(null);
    const name = ref(null);
    const email = ref(null);
    const callbackUrl = ref(null);
    const stateCode = ref(null);
    const oathEnabled = ref(null);
    const baseUrl = ref(null);

    return {
        clientId,
        token,
        secret,
        name,
        email,
        callbackUrl,
        stateCode,
        oathEnabled,
        baseUrl,
    };
});
