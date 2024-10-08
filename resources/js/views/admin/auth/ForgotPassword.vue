<script setup>
import FloatingConfigurator from "@/components/FloatingConfigurator.vue";
import { $t } from "@/plugins/i18n";
import router from "@/router/Index";
import { authStore } from "@/store/AuthStore";
import { useVuelidate } from "@vuelidate/core";
import { email as emailValidator, required } from "@vuelidate/validators";
import { ref } from "vue";
const auth = authStore();
const send = ref(false);
const email = ref("nacimbreeze@gmail.com");
const loading = ref(false);
const invalidEmail = ref(false);
const rules = {
    email: { required, emailValidator },
};

const v$ = useVuelidate(rules, { email });

const sendLink = () => {
    loading.value = true;
    v$.value.$touch();
    if (v$.value.$invalid) return;

    auth.forgotPassword(email.value)
        .then((res) => {
            if (res.status == 200) {
                send.value = true;
            }
        })
        .catch((err) => {
            if (err.response.status == 422) {
                invalidEmail.value = true;
            }
        });

    loading.value = false;
};
</script>

<template>
    <FloatingConfigurator />
    <div
        class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden"
    >
        <div class="flex flex-col items-center justify-center">
            <div
                style="
                    border-radius: 56px;
                    padding: 0.3rem;
                    background: linear-gradient(
                        180deg,
                        var(--primary-color) 10%,
                        rgba(33, 150, 243, 0) 30%
                    );
                "
            >
                <div
                    class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20"
                    style="border-radius: 53px"
                    v-if="!send"
                >
                    <div class="text-center mb-8 w-full">
                        <div
                            class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4 wrap"
                        >
                            {{ $t("auth.forgot_password") }}
                        </div>
                        <span
                            class="text-muted-color font-medium w-4/6 block mx-auto"
                        >
                            {{ $t("auth.forgot_password_message") }}
                        </span>
                    </div>

                    <div>
                        <label
                            for="email1"
                            class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2"
                            >{{ $t("auth.email") }}</label
                        >
                        <InputText
                            id="email1"
                            type="text"
                            placeholder="Email address"
                            class="w-full md:w-[30rem] mb-4"
                            fluid
                            v-model="email"
                        />

                        <div
                            class="text-red-500"
                            v-for="error of v$.email.$errors"
                            :key="error.$uid"
                        >
                            <Message severity="error">{{
                                error.$message
                            }}</Message>
                        </div>

                        <div class="text-red-500" v-if="invalidEmail">
                            <Message severity="error">
                                {{ $t("auth.non_existing_email") }}
                            </Message>
                        </div>

                        <Button
                            :label="$t('auth.send_code')"
                            class="w-full mt-4"
                            @click="sendLink"
                            :loading="loading"
                        ></Button>

                        <Button
                            :label="$t('auth.reset.sign_in')"
                            severity="secondary"
                            class="w-full mt-4"
                            @click="() => router.push({ name: 'login' })"
                        ></Button>
                    </div>
                </div>

                <div
                    class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20"
                    style="border-radius: 53px"
                    v-if="send"
                >
                    <div
                        class="text-surface-700 dark:text-surface-0 text-xl font-medium mb-4 max-w-screen-sm"
                    >
                        {{ $t("auth.sent_email_success") }}
                    </div>

                    <Button
                        :label="$t('login')"
                        class="w-full"
                        @click="() => router.push({ name: 'login' })"
                    ></Button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
