<template>
    <div class="container">
        <div class="login">
            <div class="logo">
                <div class="outer">
                    <img :src="myValue.avatar ? myValue.avatar : TopContext.res.avatar" class="image">
                </div>
            </div>
            <div class="message" :class="myValue.message.class">{{ myValue.message.text }}</div>
            <div class="form">
                <form class="form-inner" @submit.prevent="submitEvent">
                    <div class="line" :class="{error: isValid(myValue.error.username) , focus: myValue.focus.username}" ref="line-username">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input" v-model="form.username" @input="usernameInputEvent" data-name="username" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                            <div class="right"><my-icon icon="yonghu2" size="24"></my-icon></div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(myValue.error.username)"></template>
                            <template v-else>{{ myValue.error.username }}</template>
                        </div>
                    </div>

                    <div class="line password" :class="{error: isValid(myValue.error.password) , focus: myValue.focus.password}" ref="line-password">
                        <div class="top">
                            <div class="left">
                                <input :type="myValue.showPassword ? 'text' : 'password'" class="form-input" ref="input-password" v-model="form.password" @input="myValue.error.password = ''" placeholder="密码" data-name="password" @focus="focusEvent" @blur="blurEvent">
                                <div class="preview-password">
                                    <div class="outer" @click="myValue.showPassword = !myValue.showPassword">
                                        <my-icon icon="xianshi" size="18" v-if="myValue.showPassword"></my-icon>
                                        <my-icon icon="yincang1" size="18" v-else></my-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="right"><my-icon size="24" icon="mima"></my-icon></div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(myValue.error.password)"></template>
                            <template v-else>{{ myValue.error.password }}</template>
                        </div>
                    </div>

                    <div class="line captcha" v-if="settings.is_enable_grapha_verify_code_for_login" :class="{error: isValid(myValue.error.captcha_code) , focus: myValue.focus.captcha_code}" ref="line-captcha">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input"  v-model="form.captcha_code" @input="myValue.error.captcha_code = ''" data-name="captcha_code" placeholder="图形验证码" @focus="focusEvent" @blur="blurEvent"></div>
                            <div class="right">
                                <img :src="myValue.captcha.img" class="image" @click="captcha">
                            </div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(myValue.error.captcha_code)"></template>
                            <template v-else>{{ myValue.error.captcha_code }}</template>
                        </div>
                    </div>

                    <div class="line submit">
                        <button type="submit" class="button-submit" :class="{disabled: myValue.pending.submitEvent}" v-ripple>
                            <my-loading v-if="myValue.pending.submitEvent"></my-loading>
                            <template v-else>登录</template>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
<script src="./js/login.js"></script>
<style src="./css/login.css" scoped></style>
