const captcha = `${TopContext.api}/captcha`;
const sendEmailCodeForPassword = `${TopContext.api}/send_email_code_for_password`;
const sendEmailCodeForRegister = `${TopContext.api}/send_email_code_for_register`;
const defaultModule = `${TopContext.api}/default_module`;

export default {
    captcha () {
        return Http.get(captcha);
    } ,

    sendEmailCodeForPassword (query , data) {
        return Http.post(sendEmailCodeForPassword , null , data);
    } ,

    sendEmailCodeForRegister (query , data) {
        return Http.post(sendEmailCodeForRegister , query , data);
    } ,

    defaultModule () {
        return Http.get(defaultModule);
    } ,
};
