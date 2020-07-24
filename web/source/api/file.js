/**
 * 文件上传
 */
export default {
    upload (data , success , error) {
        return request(TopContext.fileApi , 'post' , data , success , error);
    } ,
};