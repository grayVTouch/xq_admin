const country = genUrl('country');
const search = genUrl('search_region');

export default {

    country () {
        return Http.get(country);
    } ,

    search (param) {
        return Http.get(search , param);
    } ,

};
