<template>
    <my-form-modal
            v-model="visible"
            title="请选择分类"
            width="75%"
            :mask-closable="true"
            :closable="true"
    >
        <template slot="footer">
            <i-button v-ripple type="error" @click="hide">取消</i-button>
        </template>
        <template slot="default">
            <div class="search-modal">
                <div class="list">
                    <i-table
                            ref="table"
                            class="w-r-100"
                            border
                            :height="TopContext.table.height + 150"
                            :columns="tableData.field"
                            :data="tableData.data"
                            :loading="myValue.pending.getData"
                            @on-row-click="rowClickEvent"
                    >
                        <template v-slot:name="{row,index}">
                            <template v-if="row.floor > 1">{{ '|' + '_'.repeat((row.floor - 1) * 10) + row.name }}</template>
                            <template v-else>{{ row.name }}</template>
                        </template>
                        <template v-slot:module_id="{row,index}">{{ row.module ? `${row.module.name}【${row.module.id}】` : `unknow【${row.module_id}】` }}</template>
                        <template v-slot:action="{row , index}">
                            <my-table-button @click="rowClickEvent(row,index)">选择</my-table-button>
                        </template>
                    </i-table>
                </div>
            </div>
        </template>
    </my-form-modal>
</template>

<script>

    const tableData = {
            field: [
                {
                    type: 'selection',
                    width: TopContext.table.checkbox ,
                    align: TopContext.table.alignCenter ,
                    fixed: 'left' ,
                },
                {
                    title: '名称' ,
                    slot: 'name' ,
                    width: 600 ,
                    fixed: 'left' ,
                } ,
                {
                    title: '类型' ,
                    key: '__type__' ,
                    minWidth: TopContext.table.name ,
                } ,
                {
                    title: '模块【id】',
                    slot: 'module_id',
                    minWidth: TopContext.table.name ,
                    align: TopContext.table.alignCenter,
                },
                {
                    title: '描述' ,
                    key: 'description' ,
                    minWidth: TopContext.table.desc
                } ,
                {
                    title: '创建时间' ,
                    key: 'created_at' ,
                    minWidth: TopContext.table.time ,
                    align: TopContext.table.alignCenter ,
                } ,
                {
                    title: '操作' ,
                    slot: 'action' ,
                    minWidth: TopContext.table.action - 50 ,
                    align: TopContext.table.alignCenter ,
                    fixed: 'right' ,
                } ,
            ] ,
            data: [] ,
    };
    export default {

        name: "my-category-selector" ,

        props: {
            moduleId: {
                default: 0 ,
            } ,
            type: {
                default: '' ,
            } ,
        } ,

        data () {
            return {
                visible: false ,
                tableData: G.copy(tableData) ,
            };
        } ,

        methods: {
            getData () {
                this.pending('getData' , true);
                Api.category
                    .index({
                        module_id: this.moduleId ,
                        type: this.type ,
                        is_enabled: 1 ,
                    })
                    .then((res) => {
                        if (res.code !== TopContext.code.Success) {
                            this.errorHandle(res.message);
                            return ;
                        }
                        this.tableData.data = res.data;
                    })
                    .finally(() => {
                        this.pending('getData' , false);
                    });
            } ,

            rowClickEvent (row , index) {
                this.$emit('on-change' , G.copy(row));
                this.hide();
            },

            hide () {
                this.data = G.copy(tableData);
                this.visible   = false;
            } ,

            show () {
                this.getData();
                this.visible = true;
            } ,

        } ,
    }
</script>

<style scoped>

</style>
