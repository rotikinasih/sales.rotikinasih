<template>
    <Head>
        <title>Roles</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <Link href="/apps/roles/create" v-if="hasAnyPermission(['roles.create'])" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Add</Link>
                    <h5>Roles</h5>
                    <span class="d-block m-t-5">Page to manage the <code> roles </code> data</span>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="search by roles Name..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col" style="width:20%">Role Name</th>
                                    <th>Permissions</th>
                                    <th scope="col" style="width:20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(role, index) in roles.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ role.name }}</td>
                                    <td style="display:flex; flex-wrap: wrap;">
                                        <span class="label theme-bg4 text-white f-12" style="border-radius:10px" v-for="(permission, index) in role.permissions" :key="index">
                                            {{ permission.name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <Link :href="`/apps/roles/${role.id}/edit`" v-if="hasAnyPermission(['roles.edit'])" class="label theme-bg3 text-white f-12 me-2" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt me-1"></i></Link>
                                        <a @click.prevent="destroy(role.id)" v-if="hasAnyPermission(['roles.delete'])" class="label theme-bg2 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="roles.data[0] == undefined">
                                    <td colspan="4" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            No Data To Display
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="roles.data[0] != undefined" align="start">Showing {{ roles.from }} to {{ roles.to }} of {{ roles.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="roles.data[0] != undefined" :links="roles.links" align="end"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link,
            Pagination
        },

        props: {
            roles: Object,
        },

        setup() {

            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/roles', {

                    //send params "q" with value from state "search"
                    q: search.value,
                });
            }

            //define method destroy
            const destroy = (id) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/apps/roles/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Role deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            //return
            return {
                search,
                handleSearch,
                destroy
            }

        }
    }
</script>

<style>

</style>
