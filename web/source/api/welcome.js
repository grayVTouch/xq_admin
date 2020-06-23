const module = `${TopContext.api}/get_all_module`;

export default {
    module (success , error) {
        return request(module , 'get' , null , success , error);
    } ,
};