<template>
    <div class="item" v-if="_function_.disabed !== 'y'" :data-id="_function_.path">
        <div class="function">
            <div class="icon"><img :src="_function_.sIco" class="image"></div>
            <div class="explain">
                <div class="in">
                    <div class="ico"><img :src="$store.state.pluginUrl + 'InfiniteClassification/image/default/ring.png'" class="image"></div>
                    <div class="text">{{ _function_.cn }}</div>
                </div>
            </div>
            <div class="flag hide">
                <div class="new">新的</div>
                <div class="number">10</div>
                <div class="switch"><img :src="$store.state.pluginUrl + 'InfiniteClassification/image/default/spread.png'" class="image"></div>
            </div>
        </div>

        <div class="child">
            <div class="list">
                <v-function v-for="v1 in _function_.children" :_function_="v1" :key="v1.path"></v-function>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "v-function" ,
        props: {
            _function_: Object
        }
    }
</script>

<style scoped>

</style>