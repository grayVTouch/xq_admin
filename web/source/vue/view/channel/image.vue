<template>
    <div class="channel-item-view">

        <div class="list">
            <a class="item" v-for="v in data" target="_blank" :href="genUrl(`/collection_group/${v.id}/image`)">
                <div class="thumb">
                    <div class="image-mask">
                        <img :src="v.thumb ? v.thumb : $store.state.context.res.notFound" class="image judge-img-size" v-judge-img-size>
                    </div>
                    <div class="info">
                        <span class="m-r-5">{{ v.count_for_image_subject }}</span>
                        <my-icon icon="shijian" class="run-position-relative run-t-1"></my-icon>
                    </div>
                </div>
                <div class="name">{{ v.name }}</div>
                <div class="time">{{ v.create_time }}</div>
            </a>

            <div class="loading" v-if="val.pending.getData">
                <my-loading></my-loading>
            </div>

            <div class="empty" v-if="!val.pending.getData && data.length < 1">暂无相关数据</div>

        </div>

    </div>
</template>

<script>
    export default {
        name: "my-image" ,

        data () {
            return {
                data: [] ,
            };
        } ,

        mounted () {
            this.getData();
        } ,

        methods: {
            getData () {
                this.pending('getData' , true);
                Api.user.collectionGroupByUserId(this.$parent.id , {
                    limit: this.data.limit ,
                    page: this.data.page ,
                    relation_type: 'image_subject' ,
                } , (msg , data , code) => {
                    this.pending('getData' , false);
                    if (code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(msg , data , code);
                        return ;
                    }
                    // this.data.total = data.total;
                    // this.data.page = data.page;
                    // this.data.data = data.data;
                    this.data = data;
                });
            } ,

            toPage (page) {
                this.data.page = page;
                this.getData();
            } ,

        } ,
    }
</script>

<style scoped src="./css/index.css"></style>
<style scoped src="./css/image.css"></style>