export default {
    data () {
        return {
            val: {
                pending: {} ,
                error: {} ,
                select: {} ,
            } ,
        };
    } ,
    methods: {

        pending (key , val) {
            if (!G.isValid(val)) {
                return this.val.pending[key];
            }
            this.val.pending[key] = val;
            this.val.pending = {...this.val.pending , ...{[key]: val}};
        } ,

        error (error = {} , clear = true) {
            if (clear) {
                this.val.error = {...error};
            }
            this.val.error = {...this.val.error , ...error};
        } ,


        select (key , val) {
            if (!G.isValid(val)) {
                return this.val.select[key];
            }
            this.val.select[key] = val;
            this.val.select = {...this.val.select , ...{[key]: val}};
        } ,

    } ,
};