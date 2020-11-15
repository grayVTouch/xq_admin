export default {
    fileApi (resize = false) {
        return TopContext.fileApi;
    } ,

    imageApi (resize = false) {
        return TopContext.uploadImageApi  + (resize ? '?w=' + TopContext.val.imageW : '');
    } ,

    thumbApi (resize = true) {
        return TopContext.uploadImageApi + (resize ? '?w=' + TopContext.val.thumbW : '');
    } ,

    videoApi () {
        return TopContext.uploadVideoApi;
    } ,

    subtitleApi () {
        return TopContext.uploadSubtitleApi;
    } ,

    officeApi () {
        return TopContext.uploadOfficeApi;
    } ,
};
