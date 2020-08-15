const retry = genUrl('retry_job');
const flush = genUrl('flush_job');

export default {
    retry (success , error) {
        return request(retry , 'post' , null , success , error);
    } ,

    flush (success , error) {
        return request(flush , 'get' , null , success , error);
    } ,
};