const captcha = `${TopContext.api}/captcha`;

export default {
    captcha () {
        return request(captcha , 'get');
    } ,
};
