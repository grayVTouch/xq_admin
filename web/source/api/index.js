const getHomeSlideshow = `${TopContext.api}/get_home_slideshow`;
const getNewestImageSubject = `${TopContext.api}/image_subject/{limit}/newest`;

export default {

    getHomeSlideshow (success , error) {
        return request(getHomeSlideshow , 'get' , null , success , error);
    } ,

    getNewestImageSubject (limit , success , error) {
        return request(getNewestImageSubject.replace('{limit}' , limit) , 'get' , null , success , error);
    } ,
};