<template>
    <Head title="Log in" />

    <GuestLayout>
    <div class="container-login">
        <div style="width: 100%;">
        <Link href="/" class="flex items-center justify-center" >
            <ApplicationLogo class=" fill-current text-gray-500" style="background: none" />
        </Link>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Correo" />
                <TextInput id="email" type="email" class="mt-1 block w-full" style="height: 40px;"  v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-3">
                <InputLabel for="password" value="Contraseña" />
                <TextInput id="password" type="password" class="mt-1 block w-full" style="height: 40px;" v-model="form.password" required autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2 flex justify-between">
                <label class="inline-flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="mx-2 text-sm text-gray-600">Recuerdame</span> </label>
            </div>

            <div class="mt-8">
                <PrimaryButton class="w-full primary"  style="height: 40px; background: linear-gradient(to right, #0079EA, #0006EB);  box-shadow: 0px 10px 20px -10px #0000FF9D;" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Ingresar al Sistema
                </PrimaryButton>
                <div style="display: flex; justify-content: center;" class="mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')" style="font-size: .8rem; text-decoration: none;" class="text-sm text-gray-600 underline hover:text-gray-900">
                        ¿Olvidé mi contraseña?
                    </Link>
                </div>

            </div>
        </form>
        </div>
    </div>
    </GuestLayout>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<style scoped>
.container-login{
    min-width: 310px;
}
@media (min-width: 1600px) {
    .container-login{
        width: 370px;
        height: 390px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}
</style>