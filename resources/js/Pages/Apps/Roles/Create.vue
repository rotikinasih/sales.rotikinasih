<template>

    <Head>
        <title>Add New Role</title>
    </Head>
    <main>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Role</h5>
                    </div>
                    <div class="card-body">
                        <h5>Create Role</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <form @submit.prevent="submit">
                                    <div class="form-group">
                                        <label class="fw-bold">Role Name</label>
                                        <input type="text" v-model="form.name" class="form-control" aria-describedby="usernameHelp" placeholder="Role Name" :class="{ 'is-invalid': errors.name }">
                                        <div v-if="errors.name" class="alert alert-danger">
                                            {{ errors.name }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="fw-bold">Permissions</label>
                                        <br>
                                        <div class="form-check form-check-inline" v-for="(permission, index) in permissions" :key="index">
                                            <input class="form-check-input" type="checkbox" v-model="form.permissions" :value="permission.name" :id="`check-${permission.id}`">
                                            <label class="form-check-label" :for="`check-${permission.id}`">{{ permission.name }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">UPDATE</button>
                                            <button class="btn btn-warning shadow-sm rounded-sm ms-3" type="reset">RESET</button>
                                            <Link href="/apps/roles" class="btn btn-success shadow-sm rounded-sm-5" style="float:right">BACK</Link>
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

    //import Heade and useForm from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            permissions: Array,
        },

        //composition API
        setup() {

            //define form with reactive
            const form = reactive({
                name: '',
                permissions: [],
            });

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post('/apps/roles', {
                    //data
                    name: form.name,
                    permissions: form.permissions,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Role saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });

            }

            return {
                form,
                submit,
            };

        }
    }
</script>

<style>

</style>
