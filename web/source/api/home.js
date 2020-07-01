const nav = `${TopContext.api}/nav`;

export default {
    nav (success , error) {
        return request(nav , 'get' , null , success , error);
    } ,
};