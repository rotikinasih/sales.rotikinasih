<template>
    <Head>
        <title>User</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <Link href="/apps/users/create" v-if="hasAnyPermission(['users.create'])" class="btn theme-bg4 text-white f-12 float-right" style="cursor:pointer; border:none;"><i class="fa fa-plus"></i> Tambah</Link>
                    <h5>Users</h5>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                v-model="search"
                                placeholder="Cari berdasarkan Nama Karyawan..."
                                style="width: 0%"
                                @input="debouncedSearch"
                            >
                            <button
                                class="btn btn theme-bg5 text-white f-12"
                                style="margin-left: 10px;"
                                @click="handleSearch"
                            >
                                <i style="margin-left: 10px" class="fa fa-search me-2"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    
                                    <th class="text-center">Email Address</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Roles</th>
                                    <th class="text-center">Outlets</th>
                                    <th class="text-center" style="width:20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users.data" :key="index">
                                    <td class="text-center">{{ index + users.from }}</td>
                                    
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.username }}</td>
                                    <td class="text-center">
                                        <span v-for="(role, idx) in user.roles" :key="idx" class="label theme-bg4 text-white f-12 me-2" style="border-radius:10px">
                                            {{ role.name }}
                                        </span>
                                    </td>
                                    <td>
    <span v-for="outlet in user.outlets" 
          :key="outlet.id" 
          class="label theme-bg6 text-white f-12 me-2" style="border-radius:10px">
        {{ outlet.lokasi }}
    </span>
</td>

                                    <td class="text-center">
                                        <Link :href="`/apps/users/${user.id}/edit`" v-if="hasAnyPermission(['users.edit'])" class="label theme-bg3 text-white f-12 me-2" style="cursor:pointer; border-radius:10px">
                                            <i class="fa fa-pencil-alt me-1"></i>
                                        </Link>
                                        <a @click.prevent="destroy(user.id)" v-if="hasAnyPermission(['users.delete'])" class="label theme-bg6 text-white f-12" style="cursor:pointer; border-radius:10px">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
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
                                <label v-if="users.data.length > 0" align="start">
                                    Showing {{ users.from }} to {{ users.to }} of {{ users.total }} items
                                </label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="users.data.length > 0" :links="users.links" align="end" />
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
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import debounce from 'lodash/debounce';

export default {
    layout: LayoutApp,
    components: {
        Head,
        Link,
        Pagination
    },
    props: {
        users: Object
    },
    setup() {
        const search = ref((new URL(document.location)).searchParams.get('search') || '');

        const handleSearch = () => {
            Inertia.get('/apps/users', { search: search.value }, { preserveState: true });
        };

        const debouncedSearch = debounce(handleSearch, 1000);

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
            }).then((result) => {
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
            });
        };

        return {
            search,
            handleSearch,
            destroy,
            debouncedSearch
        };
    }
};
</script>

<style scoped>
.input-group .form-control {
    flex: 1;
}
</style>
