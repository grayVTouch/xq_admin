const info = `${TopContext.api}/pannel/info`;

export default {
    info (success , error) {
        return request(info , 'get' , null , success , error);
    } ,
};


