const all = `${TopContext.api}/get_all_category`;

export default {
    all (success , error) {
        return request(all , 'get' , null , success , error);
    } ,
};