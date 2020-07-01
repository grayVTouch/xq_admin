export default {
    name: "show" ,
    props: ['id'] ,
    data () {
        return {
            data: {
                user: {} ,
                module: {} ,
                subject: {} ,
                images: [] ,
                tags: [] ,
            } ,
            images: {
                page: 1 ,
                maxPage: 1 ,
                data: [] ,
                total: 0 ,
                limit: 5 ,
            } ,

            dom: {} ,
            ins: {} ,
            val: {
                fixedMisc: false ,
            } ,
        };
    } ,

    created () {

    } ,

    mounted () {
        this.initDom();
        this.initEvent();
    } ,

    beforeRouteUpdate (to , from , next) {
        console.log('hello');
    } ,

    beforeRouteEnter (to , from , next) {
        console.log('route enter');
        next((vm) => {
            vm.getData();
        });
    } ,

    methods: {
        getData () {
            this.pending('getData' , true);
            Api.image_subject.show(this.id , (data , code) => {
                if (code !== TopContext.code.Success) {
                    this.pending('getData' , false);
                    this.message(data);
                    return ;
                }
                this.handleData(data);
                this.images.page = 1;
                this.images.total = data.images.length;
                this.images.maxPage = Math.ceil(this.images.total / this.images.limit);
                this.images.data = data.images.slice(0 , this.images.limit);
                this.data = {...data};
                this.$nextTick(() => {
                    this.pending('getData' , false);
                    this.initPicPreview();
                });
            });
        } ,

        handleData (data) {
            // data.user = data.user ? data.user : {};
            // data.subject = data.subject ? data.subject : {};
            // data.images = data.images ? data.images : [];
            // data.tags = data.tags ? data.tags : [];
            // data.module = data.module ? data.module : [];
        } ,

        initPicPreview () {
            this.ins.picPreview = new PicPreview(this.dom.picPreviewContainer.get(0) , {

            });
        },

        imageClick (index) {
            this.ins.picPreview.show(parseInt(index));
        } ,

        initDom () {
            this.dom.win = G(window);
            this.dom.html = G(document.documentElement);
            this.dom.imageSubject = G(this.$refs['image-subject']);
            this.dom.picPreviewContainer = G(this.$refs['pic-preview-container']);

        },

        scrollEvent (e) {
            const y = window.pageYOffset;
            const clientH = this.dom.html.clientHeight();
            const maxHeight = 122 + this.dom.imageSubject.scrollHeight();
            if (y + clientH < maxHeight) {
                return ;
            }
            if (this.images.page >= this.images.maxPage) {
                return ;
            }
            this.images.page++;
            const start = (this.images.page - 1) * this.images.limit;
            const end = start + this.images.limit;
            this.images.data = this.images.data.concat(this.data.images.slice(start , end));
        } ,

        scrollWithMiscEvent () {
            const y = window.pageYOffset;
            if (y < 62) {
                this._val('fixedMisc' , false);
            } else {
                this._val('fixedMisc' , true);
            }
        } ,

        initEvent () {
            this.dom.win.on('scroll' , this.scrollEvent.bind(this));
            this.dom.win.on('scroll' , this.scrollWithMiscEvent.bind(this));
        } ,
    } ,
}