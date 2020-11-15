export default {
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
            return ;
        }
        this.val.error = {...this.val.error , ...error};
    } ,

    _val (key , val) {
        if (!G.isValid(val)) {
            return this.val[key];
        }
        this.val = {...this.val , ...{[key]: val}};
    } ,

    select (key , val) {
        if (!G.isValid(val)) {
            return this.val.select[key];
        }
        this.val.select[key] = val;
        this.val.select = {...this.val.select , ...{[key]: val}};
    } ,

    request (name , val) {
        if (!G.isValid(val)) {
            return this.val.request[name];
        }
        this.val.request = {...this.val.request , ...{[name]: val}};
    } ,

};
