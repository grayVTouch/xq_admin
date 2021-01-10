<template>
    <div class="option">
        <div class="field">{{ name }}<template v-if="showSeparator">：</template></div>
        <div class="value">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-search-form-item" ,

        props: {
            name: {
                type: String ,
                default: '' ,
            } ,

            showSeparator: {
                type: Boolean ,
                default: true ,
            } ,

        } ,
    }
</script>

<style scoped>

</style>
