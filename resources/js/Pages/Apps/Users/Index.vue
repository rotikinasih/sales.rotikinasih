<template>
    <Head>
        <title>User</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <Link href="/apps/users/create" v-if="hasAnyPermission(['users.create'])" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none; margin-right: 0px;"><i class="fa fa-plus"></i>Tambah</Link>
                    <h5>Users</h5>
                    <!-- <span class="d-block m-t-5">Page to manage the <code> users </code> data</span> -->
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari berdasrkan nama user..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" style="margin-left: 10px" @click="handleSearch"><i style="margin-left: 10px" class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th scope="col" style="text-align: center">#</th> -->
                                    <th scope="col" style="text-align: center">Nama Lengkap</th>
                                    <th scope="col" style="text-align: center">Email Address</th>
                                    <th scope="col" style="text-align: center">Username</th>
                                    <th scope="col" style="text-align: center">Roles</th>
                                    <th scope="col" style="width:20%; text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users.data" :key="index">
                                    <!-- <td>{{ index + 1 }}</td> -->
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.username }}</td>
                                    <td class="text-center">
                                        <span v-for="(role, index) in user.roles" :key="index" class="label theme-bg4 text-white f-12 me-2" style="border-radius:10px">
                                            {{ role.name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <Link :href="`/apps/users/${user.id}/edit`" v-if="hasAnyPermission(['users.edit'])" class="label theme-bg3 text-white f-12 me-2" style="cursor:pointer; border-radius:10px"><i class="fa fa-pencil-alt me-1"></i></Link>
                                        <a @click.prevent="destroy(user.id)" v-if="hasAnyPermission(['users.delete'])" class="label theme-bg6 text-white f-12" style="cursor:pointer; border-radius:10px"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="users.data[0] == undefined">
                                    <td colspan="6" class="text-center">
                                        <br>
                                        <i class="fa fa-file-excel fa-5x"></i><br><br>
                                            Data Kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="max-width:100%; overflow-x:hidden">
                            <div class="col-md-4">
                                <label v-if="users.data[0] != undefined" align="start">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="users.data[0] != undefined" :links="users.links" align="end"/>
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
    import { Head, Link  } from '@inertiajs/inertia-vue3';

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

        //props
        props: {
            users: Object,
        },

        setup() {

            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/users', {

                    //send params "q" with value from state "search"
                    search: search.value,
                });
            }

            //method destroy
            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/apps/users/${id}`);

                        Swal.fire({
                            title: 'Sukses!',
                            text: 'User berhasil dihapus.',
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
