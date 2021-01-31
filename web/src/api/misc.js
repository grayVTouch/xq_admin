const captcha = `${TopContext.api}/captcha`;

export default {
    captcha () {
        return Http.get(captcha);
    } ,

    sendEmailCodeForPassword (query , data) {
        return Http.post(`${TopContext.api}/send_email_code_for_password` , query , data);
    } ,

    sendEmailCodeForRegister (query , data) {
        return Http.post(`${TopContext.api}/send_email_code_for_register` , query , data);
    } ,

};
