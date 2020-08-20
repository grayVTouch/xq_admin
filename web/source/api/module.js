const all       = `${TopContext.api}/module`;
const _default  = `${TopContext.api}/default_module`;

export default {
    default (success , error) {
        return request(_default , 'get' , null , success , error);
    } ,

    all (success , error) {
        return request(all , 'get' , null , success , error);
    } ,
};