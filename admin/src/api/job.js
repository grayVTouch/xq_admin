const retry = genUrl('retry_job');
const flush = genUrl('flush_job');

export default {
    retry () {
        return Http.post(retry);
    } ,

    flush () {
        return Http.post(flush);
    } ,
};
