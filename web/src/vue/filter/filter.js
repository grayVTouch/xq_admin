Vue.filter('zero_fill' , (value) => {
    if (!G.isNumeric(value)) {
        return value;
    }
    return value < 10 ? '0' + value : value;
});
