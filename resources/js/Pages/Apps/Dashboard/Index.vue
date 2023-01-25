<template>
    <Head>
        <title>Dashboard</title>
    </Head>
    <main>
        <p>INI Dashboard</p>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';
    //import Head and useForm from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    export default {
        //layout
        layout : LayoutApp,
        //register component
        components:{
            Head
        }
    }
</script>
