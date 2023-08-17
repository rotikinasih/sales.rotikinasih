<template>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- sidebar header -->
        <Sidehead/>
    <!-- end sidebar header -->

    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <slot/>
                    <small class="fw-bold">Copyright &copy; 2023 <strong>Software Database Karyawan</strong> - RLA Group.</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Sidehead from '../Components/Sidehead.vue';

    export default {
        components:{
            Sidehead
        }
    }
</script>

<style>

</style>
