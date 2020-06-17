-- 系统用户表
drop table if exists `xq_module`;
create table if not exists `xq_module` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(500) comment '描述' ,
  weight int default 0 comment '权重' ,
  create_time datetime default current_timestamp ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key `id` (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '模块表';


drop table if exists `xq_tag`;
create table if not exists `xq_tag` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '标签名称' ,
  weight int default 0 comment '权重' ,
  `count` int default 0 comment '使用次数' ,
  create_time datetime default current_timestamp ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key (id) ,
  unique key (name)
) engine=innodb default charset=utf8 comment='标签表';

drop table if exists `xq_category`;
create table if not exists `xq_category` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(500) default '' comment '描述' ,
  p_id bigint unsigned default 0 comment 'xq_category.id' ,
  enable tinyint default 1 comment '是否启用：0-否 1-是' ,
  weight int default 0 comment '权重' ,
  create_time datetime default current_timestamp comment '创建时间' ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key(id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '专题表';


drop table if exists `xq_subject`;
create table if not exists `xq_subject` (
  id bigint unsigned not null auto_increment ,
  name varchar(255) default '' comment '名称' ,
  description varchar(1000) default '' comment '描述' ,
  thumb varchar(500) default '' comment '封面' ,
  attr text comment 'json:其他属性' ,
  weight int default 0 comment '权重' ,
  create_time datetime default current_timestamp ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key (id) ,
  unique key `name` (`name`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '主体表';


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
  create_time datetime default current_timestamp comment '创建时间' ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key (id) ,
  key (module_id) ,
  key (category_id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '图片专题表';

drop table if exists `xq_image`;
create table if not exists `xq_image` (
  id bigint unsigned not null auto_increment ,
  image_subject_id bigint unsigned default 0 comment 'xq_image_subject.id' ,
  name varchar(500) default '' comment '图片名称' ,
  mime varchar(50) default '' comment 'mime类型，如：image/jpeg' ,
  `size` bigint unsigned default 0 comment '文件大小，单位字节' ,
  path varchar(500) default '' comment '图片路径' ,
  create_time datetime default current_timestamp comment '创建时间' ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '图片专题包含的图片';

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
  create_time datetime default current_timestamp ,
  update_time datetime default current_timestamp on update current_timestamp ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '图片专题评论表';

drop table if exists `xq_image_subject_comment_image`;
create table if not exists `xq_image_subject_comment_image` (
  id bigint unsigned not null auto_increment ,
  image_subject_id bigint unsigned default 0 comment 'xq_image_subject.id' ,
  image_subject_comment_id bigint unsigned default 0 comment 'xq_image_subject_comment.id' ,
  name varchar(500) default '' comment '文件名称' ,
  mime varchar(50) comment '文件 mime 类型' ,
  `size` bigint unsigned default 0 comment '文件大小，单位字节' ,
  path varchar(500) default '' comment '文件路径' ,
  create_time datetime default current_timestamp ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '图片专题评论表';


drop table if exists `xq_user`;
create table if not exists `xq_user` (
    id bigint unsigned not null auto_increment ,
    username varchar(255) default '' comment '用户名' ,
    password varchar(255) default '' comment '密码' ,
    sex varchar(50) default 'secret' comment '性别: male-男 female-女 secret-保密 both-两性 shemale-人妖' ,
    birthday date default null comment '生日' ,
    avatar varchar(500) default '' comment '头像' ,
    last_time datetime default null comment '最近登录时间' ,
    last_ip varchar(50) default '' comment '最近登录ip' ,
    phone varchar(30) default '' comment '手机' ,
    email varchar(50) default '' comment '电子邮件' ,
    user_group_id bigint unsigned default 0 comment 'xq_user_group.id' ,
    create_time datetime default current_timestamp ,
    update_time datetime default current_timestamp on update current_timestamp ,
    primary key `id` (`id`) ,
    key (`username`) ,
    key (`phone`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '平台用户表';

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
    create_time datetime default current_timestamp comment '注册时间' ,
    update_time datetime default current_timestamp on update current_timestamp ,
    primary key `id` (`id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '后台用户';

drop table if exists `xq_admin_land_log`;
create table if not exists `xq_admin_land_log` (
  id bigint unsigned not null auto_increment ,
  user_id bigint unsigned default 0 comment 'xq_admin.id' ,
  ip varchar(100) comment '登录ip' ,
  duration int comment '登录时长，单位 s' ,
  create_time datetime default current_timestamp comment '登录时间' ,
  primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '管理员登录日志表';

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
     create_time datetime default current_timestamp ,
     update_time datetime default current_timestamp on update current_timestamp ,
     primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '后台用户-权限表';

drop table if exists `xq_role`;
create table if not exists `xq_role` (
    id bigint unsigned not null auto_increment ,
    name varchar(1000) default '' comment '名称' ,
    weight int default 0 comment '权重' ,
    create_time datetime default current_timestamp ,
    primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '角色表';

drop table if exists `xq_role_permission`;
create table if not exists `xq_role_permission` (
 id bigint unsigned not null auto_increment ,
 role_id bigint unsigned default 0 comment 'xq_role.id' ,
 admin_permission_id bigint unsigned default 0 comment 'xq_admin_permission.id' ,
 create_time datetime default current_timestamp ,
 primary key (id)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '角色-权限-关联表';


drop table if exists `xq_user_group`;
create table if not exists `xq_user_group` (
	id bigint unsigned not null auto_increment ,
	name varchar(500) comment '组名' ,
	p_id int comment 'xq_user_group.id' ,
	create_time datetime default current_timestamp ,
	update_time datetime default current_timestamp on update current_timestamp ,
	primary key (id) ,
	unique key `name` (`name`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '用户组';

drop table if exists `xq_user_group_permission`;
create table if not exists `xq_user_group_permission` (
	id bigint unsigned not null auto_increment ,
	user_group_id bigint unsigned default 0 comment 'xq_user_group.id' ,
	permission_id bigint unsigned default 0 comment 'xq_permission.id' ,
	primary key (id) ,
	unique key `permission` (`user_group_id` , `permission_id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '用户组-用户权限 关联表';

drop table if exists `xq_permission`;
drop table if exists `xq_user_permission`;
create table if not exists `xq_user_permission` (
	id bigint unsigned not null auto_increment ,
	name varchar(255) default '' comment '权限名称' ,
	description varchar(500) default '' comment '权限描述' ,
	enable tinyint default 1 comment '是否启用：0-否 1-是' ,
	create_time datetime default current_timestamp ,
	update_time datetime default current_timestamp on update current_timestamp ,
	primary key `id` (`id`)
) engine=innodb auto_increment=1 character set=utf8mb4 collate=utf8mb4_bin comment '平台用户-权限表';


drop table if exists `xq_admin_token`;
create table if not exists `xq_admin_token` (
	id bigint unsigned not null auto_increment ,
	`user_id` bigint unsigned default 0 comment 'xq_admin_user.id' ,
	token varchar(500) comment 'token' ,
	expired datetime not null comment '过期时间' ,
	create_time datetime default current_timestamp ,
	primary key (id) ,
	unique key `token` (`token`)
) comment '后台用户登录表';


drop table if exists `xq_user_token`;
create table if not exists `xq_user_token` (
	id bigint unsigned not null auto_increment ,
	`user_id` bigint unsigned default 0 comment 'xq_user.id' ,
	token varchar(500) comment 'token' ,
	expired datetime not null comment '过期时间' ,
	create_time datetime default current_timestamp ,
	primary key (id) ,
	unique key `token` (`token`)
) comment '平台用户登录表';
