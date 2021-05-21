const info = `${TopContext.api}/pannel_info`;
const month = `${TopContext.api}/month_data`;
const quarter = `${TopContext.api}/quarter_data`;
const year = `${TopContext.api}/year_data`;

export default {
    info () {
        return Http.get(info);
    } ,

    month (query) {
        return Http.get(month , query);
    } ,


    quarter (query) {
        return Http.get(quarter , query);
    } ,

    year (query) {
        return Http.get(year , query);
    } ,
};


