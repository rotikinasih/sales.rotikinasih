<template>
<head>
    <title>Login Account - Employee Database Application</title>
</head>

<div class="auth-wrapper">
        <div class="auth-content">
            <div class="auth-bg">
                <span class="r"></span>
                <span class="r s"></span>
                <span class="r s"></span>
                <span class="r"></span>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="feather icon-unlock auth-icon"></i>
                    </div>
                    <h3 class="mb-4">Login</h3>
                    <h5 class="mb-4">Database System</h5>
                    <form @submit.prevent="submit">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }"  placeholder="Username">
                        </div>
                        <div v-if="errors.username" class="alert alert-danger">
                            {{ errors.username }}
                        </div>
                        <div class="input-group mb-4">
                            <input v-if="showPassword" type="text" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" placeholder="Password"/>
                            <input v-else type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" placeholder="Password">
                            <div class="input-group-append" style="cursor:pointer">
                                <a class="input-group-text" @click="toggleShow">
                                    <span class="icon is-small is-right">
                                        <i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div v-if="errors.password" class="alert alert-danger">
                            {{ errors.password }}
                        </div>
                        <button class="btn btn-primary shadow-2 mb-4" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';
    //import reactive
    import { onMounted, reactive, ref } from 'vue';
    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';
    //import Head and useform from inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        //layout
        layout: LayoutAuth,
        //register component
        components: {
            Head,
            Link
        },
        props: {
            errors: Object,
            session: Object
        },

        //define composition API
        setup() {
            const showPassword = ref(false);

            onMounted(() => {
                return (showPassword.value) ? "Hide" : "Show";
            })
            //show hide password
            const toggleShow = () => {
                showPassword.value = !showPassword.value;
            }

            //define form state
            const form = reactive({
                username: '',
                password: '',
            });

            //submit method
            const submit = () => {
                //send data to server
                Inertia.post('/login', {
                    //data
                    username: form.username,
                    password: form.password,
                }, {
                    onSuccess: () => {
                        setTimeout(() => {
                            // reload page
                            location.reload();
                        }, 50);
                    },
                });
            }

            //return form state and submit method
            return {
                form,
                toggleShow, showPassword,
                submit,
            };
        }

    }
</script>

<style>
</style>
