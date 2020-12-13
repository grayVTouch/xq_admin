const captcha = `${TopContext.api}/captcha`;

export default {
    captcha () {
        return Http.get(captcha);
    } ,
};
