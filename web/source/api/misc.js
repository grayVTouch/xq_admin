const captcha = `${TopContext.api}/captcha`;
const sendEmailCodeForPassword = `${TopContext.api}/send_email_code_for_password`;
const sendEmailCodeForRegister = `${TopContext.api}/send_email_code_for_register`;

export default {
    captcha (success , error) {
        return request(captcha , 'get' , null , success , error);
    } ,

    sendEmailCodeForPassword (email , success , error) {
        return request(sendEmailCodeForPassword , 'post' , {
            email ,
        } , success , error);
    } ,

    sendEmailCodeForRegister (email , success , error) {
        return request(sendEmailCodeForRegister , 'post' , {
            email ,
        } , success , error);
    } ,

    defaultModule () {

    } ,
};