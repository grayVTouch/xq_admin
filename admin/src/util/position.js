import adminIco from '@asset/res/nav/admin.png';
import categoryIco from '@asset/res/nav/category.png';
import imageIco from '@asset/res/nav/image.png';
import pannelIco from '@asset/res/nav/pannel.png';
import subjectIco from '@asset/res/nav/subject.png';
import systemIco from '@asset/res/nav/system.png';
import tagIco from '@asset/res/nav/tag.png';
import userIco from '@asset/res/nav/user.png';
import videoIco from '@asset/res/nav/video.png';
import moduleIco from '@asset/res/nav/module.png';
import permissionIco from '@asset/res/nav/permission.png';

/**
 * 侧边菜单功能
 */
export default [
    {
        key: 'pannel' ,
        cn: '控制台' ,
        en: 'Pannel' ,
        // 路由路径
        path: '/pannel' ,
        hidden: false ,
        view: true ,
        sIco: pannelIco ,
        bIco: pannelIco ,
        children: [] ,
    } ,
    {
        key: 'admin' ,
        cn: '后台用户' ,
        en: 'Admin' ,
        path: '/admin/index' ,
        hidden: false ,
        view: true ,
        sIco: adminIco ,
        bIco: adminIco ,
        children: [] ,
    } ,
    {
        key: 'user' ,
        cn: '用户管理' ,
        en: 'User' ,
        path: '/user/index' ,
        hidden: false ,
        view: true ,
        sIco: userIco ,
        bIco: userIco ,
        children: [] ,
    } ,
    {
        key: 'image_manager' ,
        cn: '图片管理' ,
        en: 'Image Manager' ,
        // 路由路径
        path: '' ,
        hidden: false ,
        view: false ,
        sIco: imageIco ,
        bIco: imageIco ,
        children: [
            {
                cn: '图片主体' ,
                en: '' ,
                // 路由路径
                path: '/image_subject/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                cn: '图片专题' ,
                en: '' ,
                path: '/image_project/index' ,
                hidden: false ,
                view: true ,
                sIco: subjectIco ,
                bIco: subjectIco ,
                children: [] ,
            } ,
        ] ,
    } ,
    {
        key: 'video_manager' ,
        cn: '视频管理' ,
        en: 'Video' ,
        // 路由路径
        path: '' ,
        hidden: false ,
        view: false ,
        sIco: videoIco ,
        bIco: videoIco ,
        children: [
            {
                key: 'video_series' ,
                cn: '视频系列' ,
                en: '' ,
                // 路由路径
                path: '/video_series/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                key: 'video_company' ,
                cn: '视频制作公司' ,
                en: '' ,
                // 路由路径
                path: '/video_company/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                key: 'video_project' ,
                cn: '视频专题' ,
                en: '' ,
                // 路由路径
                path: '/video_project/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                key: 'video' ,
                cn: '视频列表' ,
                en: '' ,
                // 路由路径
                path: '/video/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
        ] ,
    } ,
    {
        key: 'tag' ,
        cn: '个性标签' ,
        en: 'Tag' ,
        // 路由路径
        path: '/tag/index' ,
        hidden: false ,
        view: true ,
        sIco: tagIco ,
        bIco: tagIco ,
        children: [] ,
    } ,
    {
        cn: '分类管理' ,
        en: 'Category' ,
        // 路由路径
        path: '/category/index' ,
        hidden: false ,
        view: true ,
        sIco: categoryIco ,
        bIco: categoryIco ,
        children: [] ,
    } ,
    {

        cn: '模块管理' ,
        en: 'Module' ,
        // 路由路径
        path: '/module/index' ,
        hidden: false ,
        view: true ,
        sIco: moduleIco ,
        bIco: moduleIco ,
        children: [] ,
    } ,
    {

        cn: '权限管理' ,
        en: 'Permission' ,
        // 路由路径
        path: '' ,
        hidden: false ,
        view: false ,
        sIco: permissionIco ,
        bIco: permissionIco ,
        children: [
            {

                cn: '角色列表' ,
                en: '' ,
                // 路由路径
                path: '/role/index' ,
                hidden: false ,
                view: true ,

                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {

                cn: '权限列表' ,
                en: '' ,
                // 路由路径
                path: '/admin_permission/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
        ] ,
    } ,
    {

        cn: '系统管理' ,
        en: 'System' ,
        // 路由路径
        path: '' ,
        hidden: false ,
        view: false ,
        sIco: systemIco ,
        bIco: systemIco ,
        children: [
            {

                cn: '存储管理' ,
                en: '' ,
                // 路由路径
                path: '/disk/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                cn: '导航菜单' ,
                en: '' ,
                // 路由路径
                path: '/nav/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                cn: '定点位置' ,
                en: '' ,
                // 路由路径
                path: '/position/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
            {
                cn: '定点图片' ,
                en: '' ,
                // 路由路径
                path: '/image_at_position/index' ,
                hidden: false ,
                view: true ,
                sIco: '' ,
                bIco: '' ,
                children: [] ,
            } ,
        ] ,
    } ,
];
