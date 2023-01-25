<script>
export default {
    props: {
        show: Boolean
    }
}
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                <div class="modal-header">
                    <slot name="header">ini header</slot>
                </div>

                <div class="modal-body">
                    <slot name="body">ini body</slot>
                </div>

                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-secondary m-2" @click="$emit('close')">Close</button>
                    </slot>
                </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 700px;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 5px 0;
}

.modal-default-button {
    float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

/*untuk layar device berukuran kecil*/
@media screen and (min-width: 320px) {
    .modal-container {
        width: 95%
    }
}

/*untuk layar device berukuran sedang*/
@media screen and (min-width: 550px) {
    .modal-container {
        width: 67%;
    }
}

/*untuk layar device berukuran besar*/
@media screen and (min-width: 800px) {
    .modal-container {
        width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
}
</style>


