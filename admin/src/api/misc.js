const captcha = `${TopContext.api}/captcha`;

export default {
    captcha (success , error) {
        return request(captcha , 'get' , null , success , error);
    } ,
};