<template>

    <my-base
        :show-search="false"
        :show-data="false"
        :show-actions="false"
    >
        <form
                class="my-form"
                @submit.prevent="submitEvent"
        >
            <div class="line">

                <Tabs v-model="myValue.tab">

                    <TabPane name="web_settings" label="web 端设置">

                        <table class="input-table">
                            <tbody>

                            <tr :class="{error: myValue.error.web_url}">
                                <td>web 端url</td>
                                <td>
                                    <input
                                            type="text"
                                            v-model="systemSettings.web_url"
                                            @input="myValue.error.web_url = ''"
                                            class="form-text"
                                            placeholder="web 端url"
                                    >
                                    <span class="need"></span>
                                    <div class="msg">例：https://www.test.com</div>
                                    <div class="e-msg">{{ myValue.error.web_url }}</div>
                                </td>
                            </tr>

                            <tr v-for="v in webRouteMappings">
                                <td>{{ v.name }}</td>
                                <td>
                                    <input
                                            type="text"
                                            v-model="v.url"
                                            @input="myValue.error.web_url = ''"
                                            class="form-text"
                                            :placeholder="v.name"
                                    >
                                    <span class="need"></span>
                                    <div class="msg">例：/video/{id}/show；{id} - 动态参数</div>
                                    <div class="e-msg"></div>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </TabPane>

                    <TabPane name="admin_settings" label="后台设置">

                        <div class="block">
                            <div class="run-title">
                                <div class="left">登录设置</div>
                                <div class="right"></div>
                            </div>
                            <table class="input-table">
                                <tbody>

                                <tr :class="{error: myValue.error.web_url}">
                                    <td>启用验证码？</td>
                                    <td>
                                        <radio-group v-model="systemSettings.is_enable_grapha_verify_code_for_login">
                                            <radio v-for="(v,k) in TopContext.business.bool.integer" :key="k" :label="parseInt(k)">{{ v }}</radio>
                                        </radio-group>
                                        <span class="need"></span>
                                        <div class="msg">例：https://www.test.com</div>
                                        <div class="e-msg">{{ myValue.error.web_url }}</div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </TabPane>


                </Tabs>

            </div>


            <div class="line actions">
                <i-button
                        type="primary"
                        :loading="myValue.pending.getData || myValue.pending.submitEvent"
                        @click="submitEvent"
                >提交</i-button>
                <button type="submit" v-show="false"></button>
            </div>
        </form>
    </my-base>

</template>

<script src="./js/index.js"></script>
<style scoped>
    /**
     * ****************
     * 表单样式控制
     * ****************
     */
    .input-table tbody tr td:nth-of-type(1) {
        width: 130px;
    }

    .my-form > .line {
        margin-bottom: 15px;
    }

    .my-form > .line:nth-last-of-type(1) {
        margin-bottom: 0;
    }
</style>
