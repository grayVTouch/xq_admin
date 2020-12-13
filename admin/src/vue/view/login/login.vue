<template>
    <div class="container">
        <div class="login">
            <div class="logo">
                <div class="outer">
                    <img :src="val.avatar ? val.avatar : TopContext.res.avatar" class="image">
                </div>
            </div>
            <div class="message" :class="val.message.class">{{ val.message.text }}</div>
            <div class="form">
                <form class="form-inner" @submit.prevent="submitEvent">
                    <div class="line" :class="{error: isValid(val.error.username) , focus: val.focus.username}" ref="line-username">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input" v-model="form.username" @input="usernameInputEvent" data-name="username" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                            <div class="right"><my-icon icon="yonghu2" size="24"></my-icon></div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(val.error.username)"></template>
                            <template v-else>{{ val.error.username }}</template>
                        </div>
                    </div>

                    <div class="line password" :class="{error: isValid(val.error.password) , focus: val.focus.password}" ref="line-password">
                        <div class="top">
                            <div class="left">
                                <input :type="val.showPassword ? 'text' : 'password'" class="form-input" ref="input-password" v-model="form.password" @input="val.error.password = ''" placeholder="密码" data-name="password" @focus="focusEvent" @blur="blurEvent">
                                <div class="preview-password">
                                    <div class="outer" @click="val.showPassword = !val.showPassword">
                                        <my-icon icon="xianshi" size="18" v-if="val.showPassword"></my-icon>
                                        <my-icon icon="yincang1" size="18" v-else></my-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="right"><my-icon size="24" icon="mima"></my-icon></div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(val.error.password)"></template>
                            <template v-else>{{ val.error.password }}</template>
                        </div>
                    </div>

                    <div class="line captcha" :class="{error: isValid(val.error.captcha_code) , focus: val.focus.captcha_code}" ref="line-captcha">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input"  v-model="form.captcha_code" @input="val.error.captcha_code = ''" data-name="captcha_code" placeholder="图形验证码" @focus="focusEvent" @blur="blurEvent"></div>
                            <div class="right">
                                <img :src="val.captcha.img" class="image" @click="captcha">
                            </div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(val.error.captcha_code)"></template>
                            <template v-else>{{ val.error.captcha_code }}</template>
                        </div>
                    </div>

                    <div class="line submit">
                        <button type="submit" class="button-submit" :class="{disabled: val.pending.submitEvent}" v-ripple>
                            <my-loading v-if="val.pending.submitEvent"></my-loading>
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
