import 'view-design/dist/styles/iview.css';

import {
    Button ,
    Checkbox ,
    Switch ,
    Notice ,
    Drawer ,
    Modal ,
    Select ,
    Option ,
    RadioGroup ,
    Radio ,
    Table ,
    Message ,
    Icon ,
    BackTop ,
} from 'view-design';



Vue.component('Button' , Button);
Vue.component('Checkbox' , Checkbox);
Vue.component('i-switch' , Switch);
Vue.component('Drawer' , Drawer);
Vue.component('Modal' , Modal);
Vue.component('i-select' , Select);
Vue.component('RadioGroup' , RadioGroup);
Vue.component('Radio' , Radio);
Vue.component('i-option' , Option);
Vue.component('Table' , Table);
Vue.component('Table' , Table);
Vue.component('Icon' , Icon);
Vue.component('BackTop' , BackTop);

Vue.prototype.$Notice = Notice;
Vue.prototype.$Modal = Modal;
Vue.prototype.$Message = Message;