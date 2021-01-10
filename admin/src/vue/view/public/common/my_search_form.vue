<template>
    <form @submit.prevent="searchEvent">
        <div class="filter-option">
            <slot></slot>

            <my-search-form-item :show-separator="false">
                <button type="submit" v-show="false"></button>
            </my-search-form-item>
        </div>
    </form>
</template>

<script>
    export default {
        name: "my-search-form" ,

        methods: {
            searchEvent () {
                this.$emit('submit');
            } ,
        } ,
    }
</script>

<style scoped>

</style>
