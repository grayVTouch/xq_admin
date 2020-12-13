export default {

    notice (title , desc) {
        return this.$Notice.open({
            title ,
            desc ,
        });
    } ,

    modal (action , title , content = '' , onOk = null , merge = {}) {
        onOk = G.isFunction(onOk) ? onOk : () => {};
        return this.$Modal[action]({
            title ,
            content ,
            onOk ,
            ...merge ,
        });
    } ,

    message (action , content = '' , merge = {}) {
        return this.$Message[action]({
            background: true ,
            content ,
            duration: 3 ,
            ...merge ,
        });
    } ,

    errorHandle (message) {
        return this.modal('error' ,'错误信息' ,  message);
    } ,

    successHandle (callback) {
        return this.modal('confirm' , '操作成功' , '' , null , {
            okText: '继续' ,
            cancelText: '取消' ,
            onOk () {
                G.invoke(callback , null , true);
            } ,
            onCancel () {
                G.invoke(callback , null , false);
            } ,
        });
    } ,

    confirmModal (title , callback) {
        return this.modal('confirm' , title , '' , null , {
            onOk () {
                G.invoke(callback , null , true);
            } ,
            onCancel () {
                G.invoke(callback , null , false);
            } ,
        });
    } ,


};
