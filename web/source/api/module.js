const all = `${TopContext.api}/get_all_module`;

export default {
    all (success , error) {
        return request(all , 'get' , null , success , error);
    } ,
};