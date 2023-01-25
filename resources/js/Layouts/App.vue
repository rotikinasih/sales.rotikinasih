<template>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- sidebar -->
        <Sidebar/>
    <!-- end sidebar -->

    <!-- header -->
        <Header/>
    <!-- end header -->

    <section class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <slot/>
                    <small class="fw-bold"><strong>Employee Database Application</strong> &copy; 2023 - IT Brownies Cinta</small>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Header from '../Components/Header.vue';
    import Sidebar from '../Components/Sidebar.vue';

    export default {
        components:{
            Header,
            Sidebar
        }
    }
</script>

<style>

</style>
