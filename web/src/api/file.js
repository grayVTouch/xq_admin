export default {
    uploadImage (query , file) {
        return request(TopContext.uploadImageApi + (query && !G.isEmptyObject(query) ? '?' + G.buildQuery(query) : '') , 'post' , G.formData('file' , file));
    } ,

    uploadVideo (file) {
        return request(TopContext.uploadVideoApi , 'post' , G.formData('file' , file));
    } ,

    uploadSubtitle (file) {
        return request(TopContext.uploadSubtitleApi , 'post' , G.formData('file' , file));
    } ,

    uploadOffice (file) {
        return request(TopContext.uploadOfficeApi , 'post' , G.formData('file' , file));
    } ,

    upload (file) {
        return Http.post(TopContext.uploadApi , null  , G.formData('file' , file));
    } ,
};
