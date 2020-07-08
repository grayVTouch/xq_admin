<template>
    <main class="main">
        <!-- 头部 -->
        <header class="header">

            <!-- 导航栏 -->
            <div class="navigation" :class="{fixed: val.fixed}">
                <nav class="nav nav-top">
                    <div class="inner">
                        <my-link class="logo" href="#/welcome">

                            <div class="__logo__"><img src="./res/logo.png" class="image"></div>
                            <div class="site"><a class="link">兴趣部落</a></div>

                        </my-link>

                        <div class="search search-in-logged">
                            <div class="inner">
                                <div class="ico"><i class="run-iconfont run-iconfont-search"></i></div>
                                <div class="input"><input type="text" placeholder="搜索" class="form-input" ref="search" @focus="searchEvent"></div>
                                <div class="type" :class="{show: val.navTypeList}">
                                    <div class="current" @click.stop="showNavTypeList">{{ val.mime.value }}<i class="iconfont run-iconfont run-iconfont-arrow"></i></div>
                                    <ul class="list hide" ref="nav-type-list" @click.stop>
                                        <li v-for="(v,k) in mimeRange" :key="v" :ref="'mime-item-' + k" :data-mime="k" @click="switchSearchType(k,v)">{{ v }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="action">

                            <!-- 登录状态 -->
                            <div class="logged-layer" v-if="$store.state.user">

                                <div class="item history" @click.stop="showHistoryCtrl">
                                    <div class="action"><my-button class="button history"><my-icon icon="lishijilu" mode="right" />记录</my-button></div>
                                    <div class="groups hide" ref="groups-for-history" @click.stop>
<!--                                        <div class="background" @click="hideHistory"></div>-->
                                        <div class="inner">

                                            <div class="loading" v-if="val.pending.getHistories">
                                                <my-loading></my-loading>
                                            </div>

                                            <div class="empty" v-if="!val.pending.getHistories && histories.length <= 0">
                                                暂无数据
                                            </div>

                                            <div class="group" v-for="v in histories">
                                                <div class="title f-12">{{ v.name }}</div>
                                                <div class="list">
                                                    <template v-for="v1 in v.data">
                                                        <a class="item image" v-if="v1.relation_type === 'image_subject'" target="_blank" :href="`#/image_subject/${v1.relation_id}/show`">
                                                            <div class="thumb"><img :src="v1.image_subject.thumb ? v1.image_subject.__thumb__ : $store.state.context.res.notFound" class="image" alt=""></div>
                                                            <div class="info">
                                                                <div class="name f-14">{{ v1.image_subject.name ? (v1.image_subject.name.length > 28 ? v1.image_subject.name.slice(0,28) + '...' : v1.image_subject.name) : '' }}</div>
                                                                <div class="time f-12">
                                                                    <my-icon icon="shijian" mode="right" />{{ v1.image_subject.create_time }}&nbsp;&nbsp;{{ v1.image_subject.user ? v1.image_subject.user.nickname : 'unknow' }}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>

                                            <div class="load-more">
                                                <my-link class="more">加载更多</my-link>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="item collection">
                                    <div class="action"><my-button class="button history"><my-icon icon="shoucang6" mode="right" />收藏</my-button></div>
                                </div>

                                <div class="item user" @click.stop="showUserCtrl">
                                    <div class="action" v-ripple>
                                        <img :src="$store.state.user.avatar ? $store.state.user.__avatar__ : $store.state.context.res.avatar" class="image">
                                    </div>
                                    <div class="info hide" ref="info-for-user" @click.stop>
                                        <div class="user m-b-10" v-ripple>
                                            <div class="avatar">
                                                <div class="mask">
                                                    <img :src="$store.state.user.avatar ? $store.state.user.__avatar__ : $store.state.context.res.avatar" class="image">
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="name">running</div>
                                                <div class="desc">你的名字叫做什么？</div>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <div class="action" v-ripple>
                                                <div class="ico"><my-icon icon="lishijilu" size="16" /></div>
                                                <div class="name">历史记录</div>
                                            </div>

                                            <div class="action" v-ripple>
                                                <div class="ico"><my-icon icon="shoucang6" size="16" /></div>
                                                <div class="name">我的收藏</div>
                                            </div>

                                            <div class="action" v-ripple @click="logout">
                                                <div class="ico"><my-icon icon="084tuichu" size="16" /></div>
                                                <div class="name">退出登录</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- 未登录状态 -->
                            <div class="login-layer" v-else>
                                <my-button class="login" @click="showUserForm('login')">登录</my-button>
                                <my-button class="register" @click="showUserForm('register')">注册</my-button>
                            </div>

                        </div>
                    </div>
                </nav>
                <nav class="nav nav-btm">
                    <div class="inner">
                        <div class="item left menu" ref="nav-menu">
                            <my-nav-menu :data="nav"></my-nav-menu>
                        </div>
                        <div class="item right action"></div>
                    </div>
                </nav>
            </div>

        </header>

        <!-- 公告 -->
        <section class="announcement"></section>

        <!-- 内容 -->
        <section class="body" ref="body">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </section>

        <!-- 尾部 -->
        <footer class="footer">
            <p class="website">兴趣部落</p>
            <p class="links">
                友情链接：
                <a href="https://awm.moe/">ACG萌图</a>
                <a href="https://hanime.tv/">hanime</a>
            </p>
            <p class="copyright">Copyright©2003-2020 xq.com All rights reserved. 兴趣部落 版权所有</p>
        </footer>

        <!-- 登录/注册 -->
        <div class="user-form hide" ref="userForm" @click="hideUserForm">

            <!-- 登录 -->
            <div class="login user-form-item" v-if="val.userFormType === 'login'" @click.stop>
                <div class="logo">
                    <div class="outer">
                        <img src="./res/logo.png" class="image" alt="">
                    </div>
                </div>
                <div class="title">用户登录</div>
                <div class="message" :class="val.loginMessage.class">{{ val.loginMessage.text }}</div>
                <div class="form">
                    <form class="form-inner" @submit.prevent>
                        <div class="line" :class="{error: isValid(val.loginError.username) , focus: val.focus.usernameForLogin}" ref="line-username">
                            <div class="top">
                                <div class="left"><input type="text" class="form-input" v-model="loginForm.username" @input="val.loginError.username = ''" data-name="usernameForLogin" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                                <div class="right"><img src="./res/username.png" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.loginError.username)"></template>
                                <template v-else>{{ val.loginError.username }}</template>
                            </div>
                        </div>

                        <div class="line password" :class="{error: isValid(val.loginError.password) , focus: val.focus.passwordForLogin}" ref="line-password">
                            <div class="top">
                                <div class="left">
                                    <input :type="val.showPasswordForLogin ? 'text' : 'password'" class="form-input" ref="input-password" v-model="loginForm.password" @input="val.loginError.password = ''" placeholder="密码" data-name="passwordForLogin" @focus="focusEvent" @blur="blurEvent">
                                    <div class="preview-password">
                                        <div class="outer" @click="val.showPasswordForLogin = !val.showPasswordForLogin">
                                            <img src="./res/show.png" v-if="val.showPasswordForLogin" class="image">
                                            <img src="./res/hide.png" v-else class="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.loginError.password)"></template>
                                <template v-else>{{ val.loginError.password }}</template>
                            </div>
                        </div>

                        <div class="line operation submit">
                            <my-button type="submit" class="button" v-if="!val.pending.userLogin" @click="userLogin">登录</my-button>
                            <button class="button disabled" v-else>
                                <my-loading></my-loading>
                            </button>
                        </div>

                        <div class="line operation forget">
                            <my-button class="button" @click="showUserForm('forget')">不能登录？</my-button>
                        </div>

                        <div class="line operation register">
                            <div class="title m-b-20">没有账号？现在注册</div>
                            <div class="action">
                                <my-button class="button" @click="val.userFormType = 'register'">注册</my-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- 忘记密码 -->
            <div class="forget user-form-item" v-if="val.userFormType === 'forget'" @click.stop>
                <div class="logo">
                    <div class="outer">
                        <img src="./res/logo.png" class="image" alt="">
                    </div>
                </div>
                <div class="title">忘记密码</div>
                <div class="message" :class="val.forgetMessage.class">{{ val.forgetMessage.text }}</div>
                <div class="form">
                    <form class="form-inner" @submit.prevent>
                        <div class="line" :class="{error: isValid(val.forgetError.username) , focus: val.focus.usernameForForget}" ref="line-username">
                            <div class="top">
                                <div class="left"><input type="text" class="form-input" v-model="forgetForm.username" @input="val.forgetError.username = ''" data-name="usernameForForget" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                                <div class="right"><img src="./res/username.png" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.forgetError.username)"></template>
                                <template v-else>{{ val.forgetError.username }}</template>
                            </div>
                        </div>

                        <div class="line password" :class="{error: isValid(val.forgetError.password) , focus: val.focus.passwordForForget}" ref="line-password">
                            <div class="top">
                                <div class="left">
                                    <input :type="val.showPasswordForForget ? 'text' : 'password'" class="form-input" ref="input-password" v-model="forgetForm.password" @input="val.forgetError.password = ''" placeholder="密码" data-name="passwordForForget" @focus="focusEvent" @blur="blurEvent">
                                    <div class="preview-password">
                                        <div class="outer" @click="val.showPasswordForForget = !val.showPasswordForForget">
                                            <img src="./res/show.png" v-if="val.showPasswordForForget" class="image">
                                            <img src="./res/hide.png" v-else class="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.forgetError.password)"></template>
                                <template v-else>{{ val.forgetError.password }}</template>
                            </div>
                        </div>

                        <div class="line password" :class="{error: isValid(val.forgetError.confirm_password) , focus: val.focus.confirmPasswordForForget}" ref="line-password">
                            <div class="top">
                                <div class="left">
                                    <input :type="val.showConfirmPasswordForForget ? 'text' : 'password'" class="form-input" ref="input-password" v-model="forgetForm.confirm_password" @input="val.forgetError.confirm_password = ''" placeholder="确认密码" data-name="confirmPasswordForForget" @focus="focusEvent" @blur="blurEvent">
                                    <div class="preview-password">
                                        <div class="outer" @click="val.showConfirmPasswordForForget = !val.showConfirmPasswordForForget">
                                            <img src="./res/show.png" v-if="val.showConfirmPasswordForForget" class="image">
                                            <img src="./res/hide.png" v-else class="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.forgetError.confirm_password)"></template>
                                <template v-else>{{ val.forgetError.confirm_password }}</template>
                            </div>
                        </div>

                        <div class="line operation submit">
                            <my-button type="submit" class="button" v-if="!val.pending.updateUserPassword" @click="updateUserPassword">修改</my-button>
                            <button class="button disabled" v-else>
                                <my-loading></my-loading>
                            </button>
                        </div>

                        <div class="line operation register">
                            <div class="title m-b-20">已经有账号？</div>
                            <div class="action">
                                <my-button class="button" @click="val.userFormType = 'login'">登录</my-button>
                            </div>
                        </div>

                        <div class="line operation register">
                            <div class="title m-b-20">还没有账号？</div>
                            <div class="action">
                                <my-button class="button" @click="val.userFormType = 'register'">注册</my-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- 注册 -->
            <div class="register user-form-item" v-if="val.userFormType === 'register'" @click.stop>
                <div class="logo">
                    <div class="outer">
                        <img src="./res/logo.png" class="image" alt="">
                    </div>
                </div>
                <div class="title">用户注册</div>
                <div class="message" :class="val.registerMessage.class">{{ val.registerMessage.text }}</div>
                <div class="form">
                    <form class="form-inner" @submit.prevent>
                        <div class="line" :class="{error: isValid(val.registerError.username) , focus: val.focus.usernameForRegister}" ref="line-username">
                            <div class="top">
                                <div class="left"><input type="text" class="form-input" v-model="registerForm.username" @input="val.registerError.username = ''" data-name="usernameForRegister" placeholder="用户名" @focus="focusEvent" @blur="blurEvent"></div>
                                <div class="right"><img src="./res/username.png" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.registerError.username)"></template>
                                <template v-else>{{ val.registerError.username }}</template>
                            </div>
                        </div>

                        <div class="line password" :class="{error: isValid(val.registerError.password) , focus: val.focus.passwordForRegister}" ref="line-password">
                            <div class="top">
                                <div class="left">
                                    <input :type="val.showPasswordForRegister ? 'text' : 'password'" class="form-input" ref="input-password" v-model="registerForm.password" @input="val.registerError.password = ''" placeholder="密码" data-name="passwordForRegister" @focus="focusEvent" @blur="blurEvent">
                                    <div class="preview-password">
                                        <div class="outer" @click="val.showPasswordForRegister = !val.showPasswordForRegister">
                                            <img src="./res/show.png" v-if="val.showPasswordForRegister" class="image">
                                            <img src="./res/hide.png" v-else class="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.registerError.password)"></template>
                                <template v-else>{{ val.registerError.password }}</template>
                            </div>
                        </div>

                        <div class="line password" :class="{error: isValid(val.registerError.confirm_password) , focus: val.focus.confirmPasswordForRegister}" ref="line-password">
                            <div class="top">
                                <div class="left">
                                    <input :type="val.showConfirmPasswordForRegister ? 'text' : 'password'" class="form-input" ref="input-password" v-model="registerForm.confirm_password" @input="val.registerError.confirm_password = ''" placeholder="确认密码" data-name="confirmPasswordForRegister" @focus="focusEvent" @blur="blurEvent">
                                    <div class="preview-password">
                                        <div class="outer" @click="val.showConfirmPasswordForRegister = !val.showConfirmPasswordForRegister">
                                            <img src="./res/show.png" v-if="val.showConfirmPasswordForRegister" class="image">
                                            <img src="./res/hide.png" v-else class="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="right"><img src="./res/password.png" alt="" class="image"></div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.registerError.confirm_password)"></template>
                                <template v-else>{{ val.registerError.confirm_password }}</template>
                            </div>
                        </div>

                        <div class="line captcha" :class="{error: isValid(val.registerError.captcha_code) , focus: val.focus.captchaCodeForRegister}" ref="line-captcha">
                            <div class="top">
                                <div class="left"><input type="text" class="form-input"  v-model="registerForm.captcha_code" @input="val.registerError.captcha_code = ''" data-name="captchaCodeForRegister" placeholder="图形验证码" @focus="focusEvent" @blur="blurEvent"></div>
                                <div class="right">
                                    <img :src="val.captchaForRegister.img" class="image" @click="captchaForRegister">
                                </div>
                            </div>
                            <div class="btm">
                                <template v-if="!isValid(val.registerError.captcha_code)"></template>
                                <template v-else>{{ val.registerError.captcha_code }}</template>
                            </div>
                        </div>

                        <div class="line operation submit">
                            <my-button type="submit" class="button" v-if="!val.pending.userRegister" @click="userRegister">注册</my-button>
                            <button class="button disabled" v-else>
                                <my-loading></my-loading>
                            </button>
                        </div>

                        <div class="line operation register">
                            <div class="title m-b-20">已经有账号？</div>
                            <div class="action">
                                <my-button class="button" @click="val.userFormType = 'login'">登录</my-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>


        </div>

        <!-- 滚动到顶部 -->
        <div class="to-top" ref="to-top"><my-icon icon="jiantou" size="20" /></div>


    </main>
</template>

<script src="./js/home.js"></script>
<style scoped src="./css/home.css"></style>