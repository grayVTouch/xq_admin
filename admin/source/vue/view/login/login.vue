<template>
    <div class="container">
        <div class="login">
            <div class="logo">
                <div class="outer">
                    <img :src="val.avatar" v-if="isValid(val.avatar)" class="image">
                    <img src="./res/background.jpg" v-else class="image">
                </div>
            </div>
            <div class="message" :class="val.message.class">{{ val.message.text }}</div>
            <div class="form">
                <form class="form-inner" @submit.prevent="submitEvent">
                    <div class="line" :class="{error: isValid(val.error.username) , focus: val.focus.username}" ref="line-username">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input" v-model="form.username" @input="usernameInputEvent" data-name="username" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                            <div class="right"><img src="./res/username.png" class="image"></div>
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
                                        <img src="./res/show.png" v-if="val.showPassword" class="image">
                                        <img src="./res/hide.png" v-else class="image">
                                    </div>
                                </div>
                            </div>
                            <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                        </div>
                        <div class="btm">
                            <template v-if="!isValid(val.error.password)"></template>
                            <template v-else>{{ val.error.password }}</template>
                        </div>
                    </div>

                    <div class="line captcha" :class="{error: isValid(val.error.captcha_code) , focus: val.focus.captcha_code}" ref="line-captcha">
                        <div class="top">
                            <div class="left"><input type="text" class="form-input"  v-model="form.captcha_code" @input="val.error.captcha_code = ''" data-name="captcha" placeholder="图形验证码" @focus="focusEvent" @blur="blurEvent"></div>
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
                        <button type="submit" class="button-submit" ref="button-submit" v-if="!val.pending.submit">登录</button>
                        <button class="button-disabled" v-else>
                            <my-loading></my-loading>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
<script src="./js/login.js"></script>
<style src="./css/login.css" scoped></style>