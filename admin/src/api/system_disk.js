const index = genUrl('system_disk');

export default {
    index (query) {
        return Http.get(index , query);
    } ,
};
