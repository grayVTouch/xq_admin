const info = genUrl('info');

export default {
    info (success , error) {
        return request(info , 'get' , null , success , error);
    } ,
};
