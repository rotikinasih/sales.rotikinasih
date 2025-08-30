<template>
    <Head>
        <title>Edit User - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user"></i> EDIT USER</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Address</label>
                                                <input class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" type="email" placeholder="Email Address">
                                            </div>
                                            <div v-if="errors.email" class="alert alert-danger">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Password</label>
                                                <input class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" type="password" placeholder="Password">
                                            </div>
                                            <div v-if="errors.password" class="alert alert-danger">
                                                {{ errors.password }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Password Confirmation</label>
                                                <input class="form-control" v-model="form.password_confirmation" type="password" placeholder="Password Confirmation">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Username</label>
                                                <input class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }" type="text" placeholder="Username">
                                            </div>
                                            <div v-if="errors.username" class="alert alert-danger">
                                                {{ errors.username }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Roles</label>
                                                <br>
                                                <div class="form-check form-check-inline" v-for="(role, index) in roles" :key="index">
                                                    <input class="form-check-input" type="checkbox" v-model="form.roles" :value="role.name" :id="`check-${role.id}`">
                                                    <label class="form-check-label" :for="`check-${role.id}`">{{ role.name }}</label>
                                                </div>
                                            </div>
                                            <div v-if="errors.roles" class="alert alert-danger">
                                                {{ errors.roles }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Outlet (boleh lebih dari satu)</label>
                                                <div v-for="outlet in outlets" :key="outlet.id" class="form-check">
                                                    <input class="form-check-input" type="checkbox" :id="'outlet-'+outlet.id"
                                                        :value="outlet.id" v-model="form.outlet_ids">
                                                    <label class="form-check-label" :for="'outlet-'+outlet.id">{{ outlet.lokasi }}</label>
                                                </div>
                                                <div v-if="errors.outlet_ids" class="alert alert-danger">
                                                    {{ errors.outlet_ids }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">Update</button>
                                            <Link href="/apps/users" class="btn btn-secondary shadow-sm rounded-sm-5 ms-3" style="float:right">Kembali</Link>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import LayoutApp from '../../../Layouts/App.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import VueMultiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
    layout: LayoutApp,
    components: {
        Head,
        Link,
        VueMultiselect
    },
    props: {
        errors: Object,
        user: Object,
        roles: Array,
        outlets: Array // <-- tambahkan ini
    },
    setup(props) {
        const form = reactive({
            name: props.user.name,
            email: props.user.email,
            username: props.user.username,
            password: '',
            password_confirmation: '',
            roles: props.user.roles.map(role => role.name),
            outlet_ids: props.user.outlets.map(o => o.id) // <-- penting!
        });

        const submit = () => {
            Inertia.put(`/apps/users/${props.user.id}`, {
                email: form.email,
                username: form.username,
                password: form.password,
                password_confirmation: form.password_confirmation,
                roles: form.roles,
                name: form.name,
                outlet_ids: form.outlet_ids // <-- pastikan ini dikirim
            }, {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
            });
        };

        return {
            form,
            submit
        };
    }
}
</script>

<style scoped>
.multiselect {
    width: 100%;
}
</style>
