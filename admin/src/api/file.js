export default {
    uploadImage (query , file) {
        return Http.post(TopContext.uploadImageApi , query , G.formData('file' , file));
    } ,

    uploadVideo (file) {
        return Http.post(TopContext.uploadVideoApi , null , G.formData('file' , file));
    } ,

    uploadSubtitle (file) {
        return Http.post(TopContext.uploadSubtitleApi , null , G.formData('file' , file));
    } ,

    uploadOffice (file) {
        return Http.post(TopContext.uploadOfficeApi , null , G.formData('file' , file));
    } ,

    upload (file) {
        return Http.post(TopContext.uploadApi , null , G.formData('file' , file));
    } ,
};
