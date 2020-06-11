<template>
    <my-base ref="base">
        <div class="mask">
            <div class="line search hide">
                <div class="top run-title">
                    <div class="left">筛选</div>
                    <div class="right"></div>
                </div>
                <div class="btm">
                    <form @submit.prevent="searchEvent">
                        <div class="filter-option">
                            <div class="option">
                                <div class="field">ID：</div>
                                <div class="value"><input type="text" v-model="filter.id" class="form-text"></div>
                            </div>

                            <div class="option">
                                <div class="field"></div>
                                <div class="value">
                                    <button type="submit" class="run-button run-button-blue">搜索</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="line list">
                <div class="top run-title">
                    <div class="left">数据列表</div>
                    <div class="right">
                        <Button type="primary" size="small" @click="addEvent">添加</Button>
                        <Button type="error" icon="md-trash" size="small" :loading="val.pending.destroyAll" @click="destroyAllEvent">删除选中项</Button>
                    </div>
                </div>

                <div class="btm">
                    <table class="line-table">
                        <thead>
                        <tr>
                            <th class="th-checkbox"><Checkbox @on-change="selectAllEvent"></Checkbox></th>
                            <th class="th-id">id</th>
                            <th class="th-name">中文名称</th>
                            <th class="th-name">英文名称</th>
                            <th class="th-name">上级权限【id】</th>
                            <th class="th-name">权限值</th>
                            <th class="th-name">描述</th>
                            <th class="th-type">类型</th>
                            <th class="th-status">菜单？</th>
                            <th class="th-status">视图？</th>
                            <th class="th-status">启用？</th>
                            <th class="th-weight">权重？</th>
                            <th class="w-140">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(v,k) in list" @click="selectEvent(v)">
                            <td><Checkbox v-model="val.select['select_' + v.id]"></Checkbox></td>
                            <td>{{ v.id }}</td>
                            <td class="text-left">
                                <template v-if="v.floor > 1">{{ '|' + '_'.repeat(v.floor * 2) + v.cn }}</template>
                                <template v-else>{{ v.cn }}</template>
                            </td>
                            <td>{{ v.en }}</td>
                            <td>{{ v.permission ? `${v.permission.cn}【${v.permission.id}】` : `顶级项【${v.p_id}】` }}</td>
                            <td>{{ v.value }}</td>
                            <td>{{ v.description }}</td>
                            <td>{{ v.type }}</td>
                            <td @click.stop><my-switch v-model="v.is_menu" :loading="val.pending['is_menu_' + v.id]" :extra="{id: v.id , field: 'is_menu'}" @on-change="updateBoolValEvent" /></td>
                            <td @click.stop><my-switch v-model="v.is_view" :loading="val.pending['is_view' + v.id]" :extra="{id: v.id , field: 'is_view'}" @on-change="updateBoolValEvent" /></td>
                            <td @click.stop><my-switch v-model="v.enable" :loading="val.pending['enable_' + v.id]" :extra="{id: v.id , field: 'enable'}" @on-change="updateBoolValEvent" /></td>
                            <td>{{ v.weight }}</td>
                            <td @click.stop>
                                <Button type="primary" size="small" @click="editEvent(v)">编辑</Button>
                                <Button type="error" icon="md-trash" size="small" :loading="val.pending['delete_' + v.id]" @click="destroyEvent(k , v)">删除</Button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <my-form-drawer
                    :title="drawerTitle"
                    v-model="val.drawer"
            >
                <form class="form" @submit.prevent ref="form">
                    <table class="input-table">
                        <tbody>
                        <tr :class="getClass(val.error.cn)" id="form_cn">
                            <td>中文名称</td>
                            <td>
                                <input type="text" v-model="form.cn"  @input="val.error.cn = ''" class="form-text">
                                <span class="msg"></span>
                                <span class="need"></span>
                                <span class="e-msg">{{ val.error.cn }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.en)" id="form_en">
                            <td>英文名称</td>
                            <td>
                                <input type="text" v-model="form.en"  @input="val.error.en = ''" class="form-text">
                                <span class="msg"></span>
                                <span class="need"></span>
                                <span class="e-msg">{{ val.error.en }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.value)" id="form_value">
                            <td>权限</td>
                            <td>
                                <input type="text" class="form-text"  @input="val.error.value = ''" v-model="form.value">
                                <span class="msg"></span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.value }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.p_id)" id="form_p_id">
                            <td>上级权限</td>
                            <td>
                                <my-select :data="list" v-model="form.p_id" :has="true" :attr="val.attr"  @change="val.error.p_id = ''"></my-select>
                                <span class="msg"></span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.p_id }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.description)" id="form_description">
                            <td>描述</td>
                            <td>
                                <textarea v-model="form.description" class="form-textarea" @input="val.error.description = ''"></textarea>
                                <span class="msg"></span>
                                <span class="need"></span>
                                <span class="e-msg">{{ val.error.description }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.type)" id="form_type">
                            <td>类型</td>
                            <td>
                                <RadioGroup v-model="form.type" @on-change="val.error.type = ''">
                                    <Radio v-for="(v,k) in $store.state.business.admin_permission.type" :key="k" :label="k">{{ v }}</Radio>
                                </RadioGroup>
                                <span class="msg"></span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.type }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.is_menu)" id="form_is_menu">
                            <td>菜单？</td>
                            <td>
                                <RadioGroup v-model="form.is_menu"  @on-change="val.error.is_menu = ''">
                                    <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                </RadioGroup>
                                <span class="msg">默认：否</span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.is_menu }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.is_view)" id="form_is_view">
                            <td>视图？</td>
                            <td>
                                <RadioGroup v-model="form.is_view"  @on-change="val.error.is_view = ''">
                                    <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                </RadioGroup>
                                <span class="msg">默认：是</span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.is_view }}</span>
                            </td>
                        </tr>
                        <tr :class="getClass(val.error.enable)" id="form_enable">
                            <td>启用？</td>
                            <td>
                                <RadioGroup v-model="form.enable"  @input="val.error.enable = ''">
                                    <Radio v-for="(v,k) in $store.state.business.bool_for_int" :key="k" :label="parseInt(k)">{{ v }}</Radio>
                                </RadioGroup>
                                <span class="msg">默认：开启</span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.enable }}</span>
                            </td>
                        </tr>

                        <tr :class="getClass(val.error.b_ico)" id="b_ico">
                            <td>大图标</td>
                            <td ref="b-ico">
                                <!-- 上传图片组件 -->
                                <div class='upload-image'>

                                    <div class='select-images'>
                                        <div class="upload-show">
                                            <div class="image-line"><img src="" class="image upload-image-btn" /><span class="selected-count hide">10</span></div>
                                            <div class="text-line">请选择要上传的图片</div>
                                            <div class="clear-selected" title="清空已选择的图片"><img src="" class="image" /></div>
                                            <input type='file' name='upload_images' multiple="multiple" class='upload-images-input'  />
                                        </div>
                                        <div class="tip">这边是提示内容</div>
                                    </div>

                                    <!-- 预置显示图片 -->
                                    <div class="init-show-image-list">
                                        <img src="http://qp333com.oss-cn-hangzhou.aliyuncs.com/7peishang.com/avatar/2017-11-10/bf07531ad5dd288afc93bab47ee8d258.jpg" class="init-show-image" />
                                        <img src="http://qp333com.oss-cn-hangzhou.aliyuncs.com/7peishang.com/avatar/2017-11-10/bf07531ad5dd288afc93bab47ee8d258.jpg" class="init-show-image" />
                                        <img src="http://qp333com.oss-cn-hangzhou.aliyuncs.com/7peishang.com/avatar/2017-11-10/bf07531ad5dd288afc93bab47ee8d258.jpg" class="init-show-image" />
                                    </div>

                                    <div class='preview-images hide'>
                                        <!-- 图片上传项目：旧 -->
                                        <div class="image-item" data-filename="sama-96.jpg">
                                            <div class="img"><img src="http://qp333com.oss-cn-hangzhou.aliyuncs.com/7peishang.com/avatar/2017-11-10/bf07531ad5dd288afc93bab47ee8d258.jpg" class="image"></div>

                                            <div class="close"><img src="/UploadImages/Images/delete_unfocus.png" data-focus="/UploadImages/Images/delete_focus.png" data-unfocus="/UploadImages/Images/delete_unfocus.png" class="image"></div>

                                            <div class="progress hide">
                                                <div class="p-total">
                                                    <div class="p-cur"></div>
                                                </div>
                                            </div>

                                            <div class="msg hide">
                                                <div class="msg-in">成功</div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- 待上传列表 -->
                                    <div class="upload-image-list hide">
                                        <div class="upload-title">待上传列表</div>

                                        <div class="image-list">
                                            <div class="list-content list-title">
                                                <div class="item div-preview">图片预览</div>
                                                <div class="item div-type">类型</div>
                                                <div class="item div-size">大小</div>
                                                <div class="item div-speed">速度</div>
                                                <div class="item div-status">状态</div>
                                                <div class="item div-opr">操作</div>
                                            </div>

                                            <div class="list-content list-body">
                                                <!-- 项 -->
                                                <div class="line total-progress">
                                                    <div class="line-in">
                                                        <!-- 上传进度 -->
                                                        <div class="cur-progress"></div>

                                                        <!-- 状态 -->
                                                        <div class="msg hide">
                                                            <div class="msg-in">...</div>
                                                        </div>
                                                        <div class="item div-preview multiple-rows">
                                                            <div class="row">sama-01.jpg</div>
                                                            <div class="row"><img src="http://qp333com.oss-cn-hangzhou.aliyuncs.com/7peishang.com/avatar/2017-11-10/bf07531ad5dd288afc93bab47ee8d258.jpg" class="image" /></div>
                                                        </div>
                                                        <div class="item div-type">image/jpeg</div>
                                                        <div class="item div-size">2.4M</div>
                                                        <div class="item div-speed">50kb/s</div>
                                                        <div class="item div-status">上传中...</div>
                                                        <div class="item div-opr multiple-rows">
                                                            <div class="row"><button type="button" class="run-button run-button-orange cancel">取消上传</button></div>
                                                            <div class="row"><button type="button" class="run-button run-button-yellow delete">删除图片</button></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <span class="msg"></span>
                                <span class="need">*</span>
                                <span class="e-msg">{{ val.error.b_ico }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <Button type="primary" :loading="val.pending.submit" @click="submitEvent">提交</Button>
                                <Button type="error" @click="closeFormDrawer">关闭</Button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </my-form-drawer>
        </div>
    </my-base>
</template>

<script src="./js/list.js"></script>

<style scoped>
    .mask {

    }

    .mask > .line {
        margin-bottom: 15px;
    }

    .mask > .line:nth-last-of-type(1) {
        margin-bottom: 0;
    }

    .mask > .list > .btm {
        overflow: hidden;
        overflow-x: auto;
        width: 100%;
    }

    .mask > .function {
        height: 40px;
        display: flex;
        display: -webkit-flex;
        justify-content: flex-start;
        -webkit-justify-content: flex-start;
        align-items: center;
        -webkit-align-items: center;
    }

    .mask > .function > * {
        margin: 0;
    }
</style>