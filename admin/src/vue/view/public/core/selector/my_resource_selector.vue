<template>
    <my-form-modal
            v-model="visible"
            title="请选择"
            :mask-closable="true"
            :closable="true"
    >
        <template slot="footer">
            <i-button v-ripple type="error" @click="hide">取消</i-button>
        </template>
        <template slot="default">
            <div class="search-modal">
                <tree v-if="visible" :data="data" :load-data="loadData" @on-select-change="selectChangedEvent"></tree>
            </div>
        </template>
    </my-form-modal>
</template>

<script>
    const search = {
        // 上级搜索目录
        parent_path: '' ,
    };

    export default {
        name: "my-user-selector" ,
        data () {
            return {
                visible: false ,
                data: [] ,
                search: G.copy(search) ,
            };
        } ,
        methods: {
            getData () {
                this.pending('getData' , true);
                return new Promise((resolve , reject) => {
                    Api.systemDisk
                        .index({
                            ...this.search ,
                        })
                        .then((res) => {
                            if (res.code !== TopContext.code.Success) {
                                this.errorHandle(res.message);
                                reject();
                                return ;
                            }
                            resolve(res.data);
                        })
                        .finally(() => {
                            this.pending('getData' , false);
                        });
                });
            } ,

            loadData (row , callback) {
                this.search.parent_path = row.title;
                this.getData()
                    .then((res) => {
                        const data = res.map((v) => {
                            return {
                                title: v ,
                                loading: false ,
                                children: [] ,
                            };
                        });
                        callback(data);
                    });
            } ,

            selectChangedEvent (selections , selection) {
                this.$emit('on-change' , selection.title);
                this.hide();
            },

            hide () {
                this.visible   = false;
                this.data = [];
                this.search = G.copy(search);
            } ,

            show () {
                this.getData()
                    .then((res) => {
                        this.data = res.map((v) => {
                            return {
                                title: v ,
                                loading: false ,
                                children: [] ,
                            };
                        });
                    });
                this.visible = true;
            } ,

        } ,
    }
</script>

<style scoped>

</style>
