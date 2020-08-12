-- 系统用户表
drop table if exists `xq_module`;
create table if not exists `xq_module` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(500) comment '描述' ,
  weight int default 0 comment '权重' ,
  enable tinyint default 1 comment '启用？0-否 1-是' ,
  auth tinyint default 0 comment '认证？0-否 1-是' ,
  auth_password varchar(255) default '' comment '当 auth=1 时，要求提供认证密码' ,
  create_time datetime default null ,
  update_time datetime default null ,
  primary key `id` (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '模块表';


drop table if exists `xq_tag`;
create table if not exists `xq_tag` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '标签名称' ,
  weight int default 0 comment '权重' ,
  `count` int default 0 comment '使用次数' ,
  module_id bigint unsigned default 0 comment 'xq_module.id' ,
  create_time datetime default null ,
  update_time datetime default null ,
  primary key (id) ,
  unique key `name_module_id` (name , module_id)
) engine=innodb default charset=utf8 comment='标签表';

drop table if exists `xq_category`;
create table if not exists `xq_category` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(500) default '' comment '描述' ,
  p_id bigint unsigned default 0 comment 'xq_category.id' ,
  enable tinyint default 1 comment '是否启用：0-否 1-是' ,
  weight int default 0 comment '权重' ,
  module_id bigint unsigned default 0 comment 'xq_module.id' ,
  create_time datetime default null comment '创建时间' ,
  update_time datetime default null ,
  primary key(id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '分类表';


drop table if exists `xq_subject`;
create table if not exists `xq_subject` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(1000) default '' comment '描述' ,
  thumb varchar(500) default '' comment '封面' ,
  attr text comment 'json:其他属性' ,
  weight int default 0 comment '权重' ,
  module_id bigint unsigned default 0 comment 'xq_module.id' ,
  create_time datetime default null ,
  update_time datetime default null ,
  primary key (id) ,
  unique key `name` (`name`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '主体表';


-- 图片专题
drop table if exists `xq_image_subject`;
create table if not exists `xq_image_subject` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '专题名称' ,
  user_id bigint unsigned default 0 comment 'xq_user.id' ,
  module_id bigint unsigned default 0 comment 'xq_module.id' ,
  category_id bigint unsigned default 0 comment 'xq_category.id' ,
  type varchar(100) default 'misc' comment '类别：pro-专题、 misc-杂类' ,
  subject_id bigint unsigned default 0 comment '仅在 type=pro的时候有效，关联的主体，xq_subject.id' ,
  tag text comment '标签，json字段' ,
  thumb varchar(500) comment '封面' ,
  description varchar(500) comment '描述' ,
  weight int default 0 comment '权重' ,
  view_count bigint unsigned default 0 comment '浏览次数' ,
  praise_count bigint unsigned default 0 comment '获赞次数' ,
  status tinyint default 0 comment '审核状态：-1-审核失败 0-待审核 1-审核通过' ,
  fail_reason varchar(1000) default '' comment '失败原因' ,
  update_time datetime default null ,
  create_time datetime default null comment '创建时间' ,
  primary key (id) ,
  key (module_id) ,
  key (category_id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '图片专题表';

drop table if exists `xq_relation_tag`;
create table if not exists `xq_relation_tag` (
  id bigint unsigned not null auto_increment ,
  tag_id bigint unsigned default 0 comment 'xq_tag.id' ,
  module_id bigint unsigned default 0 comment 'xq_module.id，缓存字段' ,
  name varchar(500) default '' comment '标签名称，缓存字段' ,
  relation_type varchar(255) default '' comment '关联类型，比如 image_subject-图片专题' ,
  relation_id bigint unsigned default 0 comment '对应关联表中的 id' ,
  primary key (id)
  -- unique key `relation` (`tag_id` , `relation_type` , 'relation_id')
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '关联标签';


drop table if exists `xq_image`;
create table if not exists `xq_image` (
  id bigint unsigned not null auto_increment ,
  image_subject_id bigint unsigned default 0 comment 'xq_image_subject.id' ,
  name varchar(500) default '' comment '图片名称' ,
  mime varchar(50) default '' comment 'mime类型，如：image/jpeg' ,
  `size` bigint unsigned default 0 comment '文件大小，单位字节' ,
  path varchar(500) default '' comment '图片路径' ,
  create_time datetime default null comment '创建时间' ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '图片专题包含的图片';

drop table if exists `xq_image_subject_comment` ;
create table if not exists `xq_image_subject_comment` (
  id bigint unsigned not null auto_increment ,
  content text comment '内容' ,
  image_subject_id bigint unsigned default 0 comment 'xq_image_subject.id' ,
  user_id bigint unsigned default 0 comment 'xq_user.id 评论者' ,
  p_id int comment 'xq_image_subject_comment.id' ,
  praise_count bigint unsigned default 0 comment '获赞次数' ,
  against_count bigint unsigned default 0 comment '反对次数' ,
  status tinyint default 1 comment '状态：-1-审核不通过 0-审核中 1-审核通过' ,
  update_time datetime default null ,
  create_time datetime default null ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '图片专题评论';

drop table if exists `xq_image_subject_comment_image`;
create table if not exists `xq_image_subject_comment_image` (
  id bigint unsigned not null auto_increment ,
  image_subject_id bigint unsigned default 0 comment 'xq_image_subject.id' ,
  image_subject_comment_id bigint unsigned default 0 comment 'xq_image_subject_comment.id' ,
  path varchar(500) default '' comment '文件路径' ,
  create_time datetime default null ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '图片专题评论-图片';


drop table if exists `xq_user`;
create table if not exists `xq_user` (
    id bigint unsigned not null auto_increment ,
    username varchar(255) default '' comment '用户名' ,
    nickname varchar(255) default '' comment '昵称' ,
    password varchar(255) default '' comment '密码' ,
    sex varchar(50) default 'secret' comment '性别: male-男 female-女 secret-保密 both-两性 shemale-人妖' ,
    birthday date default null comment '生日' ,
    avatar varchar(500) default '' comment '头像' ,
    last_time datetime default null comment '最近登录时间' ,
    last_ip varchar(50) default '' comment '最近登录ip' ,
    phone varchar(30) default '' comment '手机' ,
    email varchar(50) default '' comment '电子邮件' ,
    user_group_id bigint unsigned default 0 comment 'xq_user_group.id' ,
    channel_thumb varchar(500) default '' comment '频道封面' ,
    create_time datetime default null ,
    update_time datetime default null ,
    primary key `id` (`id`) ,
    key (`username`) ,
    key (`phone`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '平台用户表';

drop table if exists `xq_admin`;
create table if not exists `xq_admin` (
    id bigint unsigned auto_increment not null ,
    username varchar(255) default '' comment '用户名' ,
    password varchar(255) default '' comment '密码' ,
    sex varchar(100) default 'secret' comment '性别: male-男 female-女 secret-保密 both-两性 shemale-人妖' ,
    birthday date default null comment '生日' ,
    avatar varchar(500) comment '头像' ,
    last_time datetime default null comment '最近登录时间' ,
    last_ip varchar(100) default null comment '最近登录ip' ,
    phone varchar(50) default '' comment '手机' ,
    email varchar(50) comment '电子邮件' ,
    role_id bigint unsigned default 0 comment 'xq_role.id' ,
    is_root tinyint default 0 comment '是否超级管理员：0-否 1-是' ,
    create_time datetime default null comment '注册时间' ,
    update_time datetime default null ,
    primary key `id` (`id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '后台用户';

drop table if exists `xq_admin_land_log`;
create table if not exists `xq_admin_land_log` (
  id bigint unsigned not null auto_increment ,
  user_id bigint unsigned default 0 comment 'xq_admin.id' ,
  ip varchar(100) comment '登录ip' ,
  duration int comment '登录时长，单位 s' ,
  create_time datetime default null comment '登录时间' ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '管理员登录日志表';

drop table if exists `xq_admin_permission`;
create table if not exists `xq_admin_permission` (
     id bigint unsigned not null auto_increment ,
     cn varchar(255) default '' comment '中文名' ,
     en varchar(255) default '' comment '英文名' ,
     `value` varchar(255) default '' comment '实际权限' ,
     description varchar(500) default '' comment '描述' ,
     type varchar(255) default '' comment '类型：api-接口 client-客户端' ,
     method varchar(100) default 'GET' comment '仅在 type=api 的时候有效！GET|POST|PUT|PATCH|DELETE ...' ,
     is_menu tinyint default 0 comment '仅在 type=client 的时候有效，是否在菜单列表显示：0-否 1-是' ,
     is_view tinyint default 0 comment '仅在 type=client 的时候有效，是否是一个视图：0-否 1-是' ,
     enable tinyint default 1 comment '是否启用：0-否 1-是' ,
     p_id bigint unsigned default 0 comment 'xq_admin_permission.id' ,
     s_ico varchar(500) default '' comment '小图标' ,
     b_ico varchar(500) default '' comment '大图标' ,
     weight smallint default 0 comment '权重' ,
     create_time datetime default null ,
     update_time datetime default null ,
     primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '后台用户-权限表';

drop table if exists `xq_role`;
create table if not exists `xq_role` (
    id bigint unsigned not null auto_increment ,
    name varchar(1000) default '' comment '名称' ,
    weight int default 0 comment '权重' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '角色表';

drop table if exists `xq_role_permission`;
create table if not exists `xq_role_permission` (
 id bigint unsigned not null auto_increment ,
 role_id bigint unsigned default 0 comment 'xq_role.id' ,
 admin_permission_id bigint unsigned default 0 comment 'xq_admin_permission.id' ,
 create_time datetime default null ,
 primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '角色-权限-关联表';


drop table if exists `xq_user_group`;
create table if not exists `xq_user_group` (
	id bigint unsigned not null auto_increment ,
	name varchar(500) comment '组名' ,
	p_id int comment 'xq_user_group.id' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
	create_time datetime default null ,
	update_time datetime default null ,
	primary key (id) ,
	unique key `name` (`name`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '用户组';

drop table if exists `xq_user_group_permission`;
create table if not exists `xq_user_group_permission` (
	id bigint unsigned not null auto_increment ,
	user_group_id bigint unsigned default 0 comment 'xq_user_group.id' ,
	user_permission_id bigint unsigned default 0 comment 'xq_user_permission.id' ,
	primary key (id) ,
	unique key `permission` (`user_group_id` , `user_permission_id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '用户组-用户权限 关联表';

drop table if exists `xq_permission`;
drop table if exists `xq_user_permission`;
create table if not exists `xq_user_permission` (
	id bigint unsigned not null auto_increment ,
	name varchar(255) default '' comment '权限名称' ,
	description varchar(500) default '' comment '权限描述' ,
	enable tinyint default 1 comment '是否启用：0-否 1-是' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
	create_time datetime default null ,
	update_time datetime default null ,
	primary key `id` (`id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '平台用户-权限表';


drop table if exists `xq_admin_token`;
create table if not exists `xq_admin_token` (
	id bigint unsigned not null auto_increment ,
	`user_id` bigint unsigned default 0 comment 'xq_admin_user.id' ,
	token varchar(500) comment 'token' ,
	expired datetime not null comment '过期时间' ,
	create_time datetime default null ,
	primary key (id) ,
	unique key `token` (`token`)
) comment '后台用户登录表';


drop table if exists `xq_user_token`;
create table if not exists `xq_user_token` (
	id bigint unsigned not null auto_increment ,
	`user_id` bigint unsigned default 0 comment 'xq_user.id' ,
	token varchar(500) comment 'token' ,
	expired datetime not null comment '过期时间' ,
	create_time datetime default null ,
	primary key (id) ,
	unique key `token` (`token`)
) comment '平台用户登录表';

drop table if exists `xq_position`;
create table if not exists `xq_position` (
     id bigint unsigned not null auto_increment ,
     value varchar(255) default '' comment '值' ,
     name varchar(255) default '' comment '名称' ,
     description varchar(1000) default '' comment '位置描述' ,
     platform varchar(255) default '' comment '平台：当前预备的有 app|android|ios|web|mobile 等，后期可扩充' ,
     create_time datetime default null ,
     primary key (id)
) comment '位置';


drop table if exists `xq_image_at_position`;
create table if not exists `xq_image_at_position` (
   id bigint unsigned not null auto_increment ,
   position_id bigint unsigned default 0 comment '放置位置' ,
   platform varchar(255) default '' comment '缓存字段,xq_position.platform' ,
   name varchar(255) default '' comment '名称' ,
   mime varchar(50) default '' comment 'mime' ,
   path varchar(1000) default '' comment '路径' ,
   link varchar(1000) default '' comment '跳转链接' ,
   module_id bigint unsigned default 0 comment 'xq_module.id' ,
   create_time datetime default null ,
   primary key (id)
) comment '定点图片';

drop table if exists `xq_collection_group`;
create table if not exists `xq_collection_group` (
   id bigint unsigned not null auto_increment ,
   name varchar(500) default '' comment '名称' ,
   user_id bigint unsigned default 0 comment 'xq_user.id' ,
   module_id bigint unsigned default 0 comment 'xq_module.id' ,
   create_time datetime default null ,
   primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '收藏分组';


drop table if exists `xq_nav`;
create table if not exists `xq_nav` (
    id bigint unsigned not null auto_increment ,
    name varchar(255) default '' comment '菜单名称' ,
    value varchar(500) default '' comment '菜单 value' ,
    p_id bigint unsigned default 0 comment 'xq_nav.id' ,
    is_menu tinyint default 0 comment '菜单？0-否 1-是' ,
    enable tinyint default 0 comment '启用？0-否 1-是' ,
    weight int default 0 comment '权重' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
    platform varchar(255) default '' comment '平台：app | android | ios | web | mobile' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '菜单表-区分不同平台';

drop table if exists `xq_history`;
create table if not exists `xq_history` (
    id bigint unsigned not null auto_increment ,
    user_id bigint unsigned default 0 comment 'xq_user.id' ,
    relation_type varchar(255) default '' comment '关联表类型: 比如 image_subject-图片专题' ,
    relation_id bigint unsigned default 0 comment '关联表id' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
    `date` date default null comment '创建日期' ,
    `time` time default null comment '创建时间' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '活动记录';

drop table if exists `xq_collection`;
create table if not exists `xq_collection` (
   id bigint unsigned not null auto_increment ,
   user_id bigint unsigned default 0 comment 'xq_user.id' ,
   collection_group_id bigint unsigned default 0 comment 'xq_collection_group.id' ,
   relation_type varchar(255) default '' comment '关联表类型: 比如 image_subject-图片专题' ,
   relation_id bigint unsigned default 0 comment '关联表id' ,
   module_id bigint unsigned default 0 comment 'xq_module.id' ,
   create_time datetime default null ,
   primary key (id) ,
   unique key (user_id , relation_type , relation_id , module_id , collection_group_id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '我的收藏';

drop table if exists `xq_focus_user`;
create table if not exists `xq_focus_user` (
    id bigint unsigned not null auto_increment ,
    user_id bigint unsigned default 0 comment 'xq_user.id' ,
    focus_user_id bigint unsigned default 0 comment 'xq_user.id，关注的用户' ,
    create_time datetime default null ,
    primary key (id) ,
    unique key (user_id , focus_user_id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '关注的用户';

drop table if exists `xq_praise`;
create table if not exists `xq_praise` (
    id bigint unsigned not null auto_increment ,
    user_id bigint unsigned default 0 comment 'xq_user.id' ,
    relation_type varchar(255) default '' comment '关联表类型: 比如 image_subject-图片专题' ,
    relation_id bigint unsigned default 0 comment '关联表主键id' ,
    module_id bigint unsigned default 0 comment 'xq.module.id' ,
    create_time datetime default null ,
    primary key (id) ,
    unique key (user_id , relation_type , relation_id , module_id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '点赞表';

drop table if exists `xq_email_code`;
create table if not exists `xq_email_code` (
    id bigint unsigned not null auto_increment ,
    email varchar(30) default '' comment '邮箱' ,
    code varchar(30) default '' comment '邮箱验证码' ,
    type varchar(50) default '' comment '类型，比如：login-登录验证码 register-注册验证码 password-修改密码验证码 等' ,
    used tinyint default 0 comment '是否被使用过: 0-否 1-是' ,
    send_time datetime default null comment '发送时间' ,
    update_time datetime default null comment '更新时间' ,
    create_time datetime default null ,
    primary key (id) ,
    unique key `email` (`email` , `type`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '邮箱验证码';

drop table if exists `xq_video_series`;
create table if not exists `xq_video_series` (
    id bigint unsigned not null auto_increment ,
    name varchar(255) default '' comment '名称' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
    weight smallint default 0 comment '权重' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频系列';

drop table if exists `xq_video_subject`;
create table if not exists `xq_video_subject` (
    id bigint unsigned not null auto_increment ,
    name varchar(255) default '' comment '名称' ,
    thumb varchar(500) default '' comment '封面' ,
    score decimal(13,2) default 0 comment '评分' ,
    release_time date default null comment '发布时间' ,
    end_time date default null comment '完结时间' ,
    status varchar(30) default 'completed' comment '状态：making-制作中 completed-已完结 terminated-已终止（部分完成）' ,
    `count` smallint unsigned default 0 comment '视频数量' ,
    play_count bigint unsigned default 0 comment '播放数' ,
    type varchar(30) default '' comment '类型：second-二次元 third-三次元' ,
    description varchar(1000) default '' comment '描述' ,
    video_series_id bigint unsigned default 0 comment 'xq_video_series.id' ,
    video_company_id bigint unsigned default 0 comment 'xq_video_company.id' ,
    module_id bigint unsigned default 0 comment 'xq_module.id' ,
    weight int default 0 comment '权重' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频专题';

drop table if exists `xq_video_company`;
create table if not exists `xq_video_company` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  thumb varchar(500) default '' comment '封面' ,
  description varchar(1000) default '' comment '描述' ,
  country_id int unsigned default 0 comment 'xq_region.id' ,
  country varchar(50) default '' comment '国家名称' ,
  module_id bigint unsigned default 0 comment 'xq_module.id' ,
  weight int default 0 comment '权重' ,
  create_time datetime default null ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频制作公司';

drop table if exists `xq_video`;
create table if not exists `xq_video` (
      id bigint unsigned not null auto_increment ,
      name varchar(255) default '' comment '名称' ,
      user_id bigint unsigned default 0 comment 'xq_user.id' ,
      module_id bigint unsigned default 0 comment 'xq_module.id' ,
      category_id bigint unsigned default 0 comment 'xq_category.id' ,
      thumb varchar(500) default '' comment '用户设置封面' ,
      thumb_for_program varchar(500) default '' comment '程序智能截取封面' ,
      praise_count bigint unsigned default 0 comment '点赞数' ,
      against_count bigint unsigned default 0 comment '反对数' ,
      view_count bigint unsigned default 0 comment '观看次数' ,
      src varchar(500) default '' comment '视频源' ,
      duration int unsigned default 0 comment '时长' ,
      type varchar(50) default '' comment 'misc-杂项 pro-视频专题' ,
      video_subject_id bigint unsigned default 0 comment 'xq_video_subject.id，视频专题' ,
      simple_preview varchar(500) default '' comment '简单视频预览 gif' ,
      preview varchar(500) default '' comment '视频预览' ,
      preview_width int unsigned default 0 comment '视频预览：单个画面尺寸：宽' ,
      preview_height int unsigned default 0 comment '视频预览：单个画面尺寸：高' ,
      preview_duration int unsigned default 0 comment '视频预览：单个画面间隔时间' ,
      preview_count int unsigned default 0 comment '视频预览：合成的画面数量' ,
      status tinyint default 1 comment '状态：-1-审核不通过 0-审核中 1-审核通过' ,
      process_status tinyint default 0 comment '处理处理状态：-1-处理失败 0-信息处理中 1-转码中 2-处理完成' ,
      fail_reason varchar(1000) default '' comment '失败原因' ,
      weight int default 0 comment '权重' ,
      update_time datetime default null ,
      create_time datetime default null ,
      primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频';

drop table if exists `xq_video_src`;
create table if not exists `xq_video_src` (
      id bigint unsigned not null auto_increment ,
      video_id bigint unsigned default 0 comment 'xq_video.id' ,
      src varchar(500) default '' comment '视频源' ,
      duration int unsigned default 0 comment '时长' ,
      width int unsigned default 0 comment '宽' ,
      height int unsigned default 0 comment '高' ,
      display_aspect_ratio varchar(50) default '' comment '长宽比，比如：16:9' ,
      size bigint unsigned default 0 comment '大小，单位 Byte' ,
      definition varchar(50) default '' comment '清晰度: 360P|480P|720P|1080P|2K|4K ... 等' ,
      create_time datetime default null ,
      primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频源';

drop table if exists `xq_video_subtitle`;
create table if not exists `xq_video_subtitle` (
    id bigint unsigned not null auto_increment ,
    video_id bigint unsigned default 0 comment 'xq_video.id' ,
    name varchar(255) default '' comment '字幕名称' ,
    src varchar(500) default '' comment '字幕源' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频字幕-仅支持 .vtt 格式';

drop table if exists `xq_video_comment` ;
create table if not exists `xq_video_comment` (
    id bigint unsigned not null auto_increment ,
    content text comment '内容' ,
    user_id bigint unsigned default 0 comment 'xq_user.id 评论者' ,
    p_id int comment 'xq_video_comment.id' ,
    video_id bigint unsigned default 0 comment 'xq_video.id' ,
    video_subject_id bigint unsigned default 0 comment 'xq_video_subject.id' ,
    praise_count bigint unsigned default 0 comment '获赞次数' ,
    against_count bigint unsigned default 0 comment '反对次数' ,
    status tinyint default 1 comment '状态：-1-审核不通过 0-审核中 1-审核通过' ,
    update_time datetime default null ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频评论';

drop table if exists `xq_video_comment_image`;
create table if not exists `xq_video_comment_image` (
    id bigint unsigned not null auto_increment ,
    video_id bigint unsigned default 0 comment 'xq_video.id' ,
    video_subject_id bigint unsigned default 0 comment 'xq_video_subject.id' ,
    video_comment_id bigint unsigned default 0 comment 'xq_video_comment.id' ,
    path varchar(500) default '' comment '文件路径' ,
    create_time datetime default null ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_unicode_ci comment '视频评论-图片';

alter table xq_tag add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_category add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_subject add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_relation_tag add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_user_group add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_user_permission add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_position add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_image_at_position add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_collection add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_history add module_id bigint unsigned default 0 comment 'xq_module.id';
alter table xq_module add enable tinyint default 1 comment '启用？0-否 1-是';


-- ----------------------------
-- Table structure for xq_region
-- ----------------------------
DROP TABLE IF EXISTS `xq_region`;
CREATE TABLE `xq_region`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地区名称',
  `p_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT 'xq_region.id',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '地区类型：country-国家 state-州|邦|省份 region-地区',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `p_id`(`p_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6909 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '全球地区' ROW_FORMAT = Dynamic;

-- 新增菜单
delete from xq_nav;
alter table xq_nav auto_increment=1;
insert into `xq_nav` (id , name , value , p_id , is_menu , enable , module_id , platform , create_time) values
(1 , '首页' , '/index' , 0 , 1 , 1 , 3 , 'web' , current_time()) ,
(2 , '图片专区' , '/image_subject/index' , 0 , 1 , 1 , 3 , 'web' , current_time()) ,
(3 , '二次元' , '/image_subject/search?category_id=37' , 2 , 1 , 1 , 3 , 'web' , current_time()) ,
(4 , '三次元' , '/image_subject/search?category_id=38' , 2 , 1 , 1 , 3 , 'web' , current_time()) ,
(5 , '琉璃神社' , '/image_subject/search?category_id=39' , 3 , 1 , 1 , 3 , 'web' , current_time());
