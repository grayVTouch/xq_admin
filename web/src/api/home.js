const nav = `${TopContext.api}/nav`;

export default {
    nav () {
        return Http.get(nav);
    } ,
};
