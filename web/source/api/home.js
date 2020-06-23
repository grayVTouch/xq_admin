const category = `${TopContext.api}/get_all_category`;

export default {
    category (success , error) {
        return request(category , 'get' , null , success , error);
    } ,
};