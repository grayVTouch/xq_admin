const country = genUrl('country');
const search = genUrl('search_region');

export default {
    country (success , error) {
        return request(country , 'get' , null , success , error);
    } ,

    search (data , success , error) {
        return request(search , 'get' , data , success , error);
    } ,
};