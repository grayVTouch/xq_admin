<template>
    <div class="channel-item-view">

        <div class="list m-b-20">

            <a class="item" v-for="v in data.data" target="_blank" :href="genUrl(`/channel/${v.user_id}/image`)">
                <div class="avatar">
                    <div class="image-mask"><img :src="v.user.avatar ? v.user.avatar : TopContext.res.notFound" class="image judge-img-size" v-judge-img-size></div>
                    <div class="info">
                        <div class="name">{{ getUsername(v.user.username , v.user.nickname) }}</div>
                        <div class="desc">{{ v.user.description }}</div>
                    </div>
                </div>
                <div class="action">
                    <button v-ripple class="my-button" @click.prevent="focusHandle(v.user)">
                        <template v-if="v.user.focused">取消关注</template>
                        <template v-else>互关</template>
                        <my-loading size="16" v-if="val.pending.focusHandle"></my-loading>
                    </button>
                </div>
            </a>

            <div class="loading" v-if="val.pending.getData">
                <my-loading></my-loading>
            </div>

            <div class="empty" v-if="!val.pending.getData && data.total < 1">暂无相关数据</div>

        </div>

        <div class="pager">
            <my-page :total="data.total" :page="data.page" :limit="data.limit" @on-change="toPage"></my-page>
        </div>

    </div>
</template>

<script>
    export default {
        name: "my-focus-user" ,

        data () {
            return {
                data: {
                    data: [] ,
                    page: 1 ,
                    total: 0 ,
                    limit: TopContext.limit ,
                } ,
            };
        } ,

        mounted () {
            this.getData();
        } ,

        methods: {
            getData () {
                this.pending('getData' , true);
                Api.user
                    .focusMeUser(this.$parent.id , {
                        limit: this.data.limit ,
                        page: this.data.page ,
                    })
                    .then((res) => {
                        if (res.code !== TopContext.code.Success) {
                            this.errorHandleAtHomeChildren(res.message , res.code);
                            return ;
                        }
                        const data = res.data;
                        this.data.total = data.total;
                        this.data.page = data.page;
                        this.data.data = data.data;
                    })
                    .finally(() => {
                        this.pending('getData' , false);
                    });
            } ,

            toPage (page) {
                this.data.page = page;
                this.getData();
            } ,

            focusHandle (user) {
                if (this.pending('focusHandle')) {
                    return ;
                }
                this.pending('focusHandle' , true);
                Api.user
                    .focusHandle({
                        user_id: user.id ,
                        action: user.focused ? 0 : 1 ,
                    })
                    .then((res) => {
                    this.pending('focusHandle' , false);
                    if (res.code !== TopContext.code.Success) {
                        this.errorHandleAtHomeChildren(res.message , res.code , () => {
                            this.focusHandle();
                        });
                        return ;
                    }
                    this.getData();
                });

            } ,
        } ,
    }
</script>

<style scoped src="./css/index.css"></style>
<style scoped src="./css/focus_me_user.css"></style>
