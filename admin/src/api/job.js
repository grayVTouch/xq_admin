
export default {
    retry () {
        return Http.post(`${TopContext.api}/retry_job`);
    } ,

    flush () {
        return Http.post(`${TopContext.api}/flush_job`);
    } ,

    resourceClear () {
        return Http.post(`${TopContext.api}/resource_clear_job`);
    } ,
};
