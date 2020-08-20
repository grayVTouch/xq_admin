export default {
    uploadImage (query , file , success , error) {
        return request(TopContext.uploadImageApi + (query && !G.isEmptyObject(query) ? '?' + G.buildQuery(query) : '') , 'post' , G.formData('file' , file) , success , error);
    } ,

    uploadVideo (file , success , error) {
        return request(TopContext.uploadVideoApi , 'post' , G.formData('file' , file) , success , error);
    } ,

    uploadSubtitle (file , success , error) {
        return request(TopContext.uploadSubtitleApi , 'post' , G.formData('file' , file) , success , error);
    } ,

    uploadOffice (file , success , error) {
        return request(TopContext.uploadOfficeApi , 'post' , G.formData('file' , file) , success , error);
    } ,

    upload (file , success , error) {
        return request(TopContext.uploadApi , 'post' , G.formData('file' , file) , success , error);
    } ,
};