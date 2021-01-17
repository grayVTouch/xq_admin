const all       = `${TopContext.api}/module`;
const _default  = `${TopContext.api}/default_module`;

export default {
    default () {
        return Http.get(_default);
    } ,

    all () {
        return Http.get(all);
    } ,
};
