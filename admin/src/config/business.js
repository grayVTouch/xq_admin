export default {
    bool_for_int: {
        0: '否',
        1: '是'
    } ,

    bool: {
        integer: {
            0: '否',
            1: '是'
        } ,
        string: {
            'y': '是' ,
            'n': '否' ,
        } ,
    } ,

    admin_permission: {
        type: {
            view: 'view' ,
            api: 'api'
        }
    } ,
    image_project: {
        type: {
            pro: '专题' ,
            misc: '杂项' ,
        } ,

        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    video_project: {

        end_status: {
            making : '连载中' ,
            completed: '已完结' ,
            terminated: '已终止'
        } ,
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    video: {
        type: {
            pro: '专题' ,
            misc: '杂项' ,
        } ,

        status: {
            '-1' : '审核失败' ,
            0: '审核中' ,
            1: '审核通过'
        }
    } ,


    user: {
        sex: {
            male: '男' ,
            female: '女' ,
            secret: '保密' ,
            both: '两性' ,
            shemale: '人妖' ,
        } ,
    } ,

    platform: {
        web: 'web端' ,
        app: 'app' ,
        android: 'android' ,
        ios: 'ios' ,
        mobile: '移动端' ,
    } ,

    disk: {
        os: {
            windows: 'windows' ,
            linux: 'linux' ,
            mac: 'mac' ,
        } ,
    } ,

    image_subject: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    video_subject: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    video_series: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    video_company: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    tag: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,

    category: {
        status: {
            '-1' : '审核失败' ,
            0: '待审核' ,
            1: '审核通过'
        }
    } ,
};
