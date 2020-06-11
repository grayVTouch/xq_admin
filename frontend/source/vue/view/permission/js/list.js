import line from '../../public/js/line';

const form = {
    type: 'view' ,
    p_id: 0 ,
    enable: 1 ,
    is_menu: 0 ,
    is_view: 1 ,
    weight: 0 ,
};

export default {
    name: "list",

    data () {
        return {
            filter: {
                id: '' ,
            } ,
            list: [] ,
            val: {
                pending: {} ,
                drawer: false ,
                model: false ,
                attr: {
                    id: 'id',
                    floor: 'floor',
                    name: 'cn'
                } ,
                error: {} ,
                // edit-编辑 add-添加
                mode: '' ,
                select: {} ,
            } ,
            form: {...form}  ,
        };
    } ,

    mixins: [
        line ,
    ] ,

    mounted () {
        this.init();
    } ,

    computed: {
        drawerTitle () {
            return this.val.mode === 'edit' ? '编辑权限' : '添加权限';
        } ,

        is_menu () {

        }
    } ,

    methods: {

        init () {
            this.getData();
        } ,

        getData () {
            this.$refs.base.show();
            Api.adminPermission.index((data , code) => {
                this.$refs.base.hide();
                if (code !== TopContext.successCode) {
                    this.$Notice.open({
                        title: data
                    });
                    return ;
                }
                this.handleData(data);
                this.list = data;
            });
        } ,

        handleData (list) {
            list.forEach((v) => {
                this.pending(`is_menu_${v.id}` , false);
                this.pending(`is_view_${v.id}` , false);
                this.pending(`enable_${v.id}` , false);
                this.pending(`delete_${v.id}` , false);
                this.select(`select_${v.id}` , false);
            });
        } ,

        searchEvent () {

        } ,

        selectAllEvent (bool) {
            this.list.forEach((v) => {
                this.select('select_' + v.id , bool);
            });
        } ,

        findRecordById (id) {
            for (let i = 0; i < this.list.length; ++i)
            {
                const cur = this.list[i];
                if (cur.id == id) {
                    return cur;
                }
            }
            throw new Error('未找到 id 对应记录：' + id);
        } ,



        updateBoolValEvent (val , extra) {
            const oVal = val > 0 ? 0 : 1;
            const pendingKey = `${extra.field}_${extra.id}`;
            const record = this.findRecordById(extra.id);
            this.pending(pendingKey , true);

            Api.adminPermission.localUpdate(record.id , {
                [extra.field]: val
            } , (data , code) => {
                this.pending(pendingKey , false);
                if (code !== TopContext.successCode) {
                    record[extra.field] = oVal;
                    this.notice(data);
                    return ;
                }
                this.notice('操作成功');
            });
        } ,

        destroy (id , callback) {
            this.destroyAll([id] , callback);
        } ,

        selectEvent (record) {
            const key = 'select_' + record.id;
            const val = this.select(key);
            this.select(key , !val);
        } ,

        getSelectedIds () {
            const idList = [];
            this.list.forEach((v) => {
                if (!this.select('select_' + v.id)) {
                    return ;
                }
                idList.push(v.id);
            });
            return idList;
        } ,

        updateTextValEvent () {

        } ,

        destroyAll (idList , callback) {
            const invocation = () => {G.isFunction(callback) ? callback() : null};
            if (idList.length < 1) {
                this.notice('请选择待删除的项');
                invocation();
                return ;
            }
            const self = this;
            this.modal('confirm' , {
                title: '你确定删除吗？' ,
                onOk () {
                    Api.adminPermission.destroyAll(idList , (data , code) => {
                        invocation();
                        self.notice('操作成功' , '影响的记录数：' + data);
                        self.getData();
                    });
                } ,
                onCancel () {
                    invocation();
                } ,
            });
        } ,

        destroyEvent (index , record) {
            const pendingKey = 'delete_' + record.id;
            this.pending(pendingKey , true);
            this.destroy(record.id , () => {
                this.pending(pendingKey , false);

            });
        } ,

        getIndexFromListById (id) {
            for (let i = 0; i < this.list.length; ++i)
            {
                const cur = this.list[i];
                if (cur.id == id) {
                    return i;
                }
            }
            throw new Error('记录未找到：' + id);
        } ,

        destroyAllEvent () {
            const idList = this.getSelectedIds();
            this.pending('destroyAll' , true);
            this.destroyAll(idList , () => {
                this.pending('destroyAll' , false);
            });
        } ,

        editEvent (v) {
            this.showFormDrawer();
            this.val.mode = 'edit';
            this.form = {...v};
        } ,

        addEvent () {
            this.showFormDrawer();
            this.val.mode = 'add';
            this.error();
            this.form = {...form};
        } ,

        showFormDrawer () {
            this.val.drawer = true;
        } ,

        hideFormDrawer () {
            this.val.drawer = false;
        } ,

        submitEvent () {
            const callback = (data , code) => {
                this.pending('submit' , false);
                this.error();
                if (code !== TopContext.successCode) {
                    if (G.isString(data)) {
                        this.notice(data);
                        return ;
                    }
                    this.val.error = data;
                    this.error(data);
                    return ;
                }
                this.notice('操作成功');
                // this.closeFormDrawer();
                this.getData();
            };
            this.pending('submit' , true);
            if (this.val.mode === 'edit') {
                Api.adminPermission.update(this.form.id , this.form ,callback);
                return ;
            }
            Api.adminPermission.store(this.form , callback);
        } ,

        closeFormDrawer () {
            if (this.pending('submit')) {
                this.notice('表单请求中...请耐心等待');
                return ;
            }
            this.hideFormDrawer();
        } ,
    } ,
}