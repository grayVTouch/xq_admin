
const loginForm = {
    username: '' ,
    password: '' ,
};
const forgetForm = {
    email: '' ,
    email_code: '' ,
    password: '' ,
    confirm_password: '' ,
};

const registerForm = {
    email: '' ,
    nickname: '' ,
    password: '' ,
    confirm_password: '' ,
    email_code: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const loginError = {
    username: '' ,
    password: '' ,
};

const forgetError = {
    email: '' ,
    password: '' ,
    email_code: '' ,
    confirm_password: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const registerError = {
    username: '' ,
    password: '' ,
    confirm_password: '' ,
    captcha_code: '' ,
    captcha_key: '' ,
};

const loginMessage = {
    class: '' ,
    text: '' ,
};

const registerMessage = {...loginMessage};
const forgetMessage = {...loginMessage};

const userFormCallback = {
    login: [] ,
    register: [] ,
    forget: [] ,
};

const collectionGroup = {
    count: 0 ,
    collections: []

};

export default {
    methods: {

    } ,
};