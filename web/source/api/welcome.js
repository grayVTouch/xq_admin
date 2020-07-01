const module = `${TopContext.api}/module`;

export default {
    module (success , error) {
        return request(module , 'get' , null , success , error);
    } ,
};