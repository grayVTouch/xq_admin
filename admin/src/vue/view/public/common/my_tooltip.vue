<template>
    <Tooltip
            placement="top"
            :content="content"
            :transfer="true"
    >
        <slot></slot>
    </Tooltip>
</template>

<script>
    export default {
        name: "my-tooltip" ,
        props: {
            content: {
                type: String ,
                default:''
            } ,
        } ,
    }
</script>

<style scoped>

</style>
