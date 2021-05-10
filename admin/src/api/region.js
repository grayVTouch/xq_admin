const country = genUrl('country');
const search = genUrl('search_region');

export default {

    country () {
        return Http.get(country);
    } ,

    search (query) {
        return Http.get(search , query);
    } ,

};
