export default {
    pending (key , val) {
        if (!G.isValid(val)) {
            return this.myValue.pending[key];
        }
        this.myValue.pending[key] = val;
        this.myValue.pending = {...this.myValue.pending , ...{[key]: val}};
    } ,

    error (error = {} , clear = true) {
        if (clear) {
            this.myValue.error = {...error};
            return ;
        }
        this.myValue.error = {...this.myValue.error , ...error};
    } ,

    setValue (key , val) {
        if (!G.isValid(val)) {
            return this.myValue[key];
        }
        this.myValue = {...this.myValue , ...{[key]: val}};
    } ,

    select (key , val) {
        if (!G.isValid(val)) {
            return this.myValue.select[key];
        }
        this.myValue.select[key] = val;
        this.myValue.select = {...this.myValue.select , ...{[key]: val}};
    } ,

    request (name , val) {
        if (!G.isValid(val)) {
            return this.myValue.request[name];
        }
        this.myValue.request = {...this.myValue.request , ...{[name]: val}};
    } ,

};
