<template>
    <Head>
        <title>Permissions</title>
    </Head>
    <main>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Permissions</h5>
                    <span class="d-block m-t-5">Page to manage the <code> permission </code> data</span>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="search" placeholder="search by Permissions name..." @keyup="handleSearch">
                            <button class="btn btn theme-bg5 text-white f-12" @click="handleSearch"> <i class="fa fa-search me-2"></i></button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pe, index) in permissions.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ pe.name }}</td>
                                </tr>
                                <!-- jika data kosong -->
                                <tr v-if="permissions.data[0] == undefined">
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
                                <label v-if="permissions.data[0] != undefined" align="start">Showing {{ permissions.from }} to {{ permissions.to }} of {{ permissions.total }} items</label>
                            </div>
                            <div class="col-md-8">
                                <Pagination v-if="permissions.data[0] != undefined" :links="permissions.links" align="end"/>
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
            permissions: Object,
        },

        setup() {
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('search'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/permissions', {
                    //send params "search" with value from state "search"
                    search: search.value,
                });
            }

            //return
            return {
                search,
                handleSearch,
            }

        }
    }
</script>

<style>

</style>
