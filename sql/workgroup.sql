drop table if exists workgroup;

create table workgroup
(
     -- айди группыгруппы
     work_group_id int  primary key ,
     -- название
     title varchar(200)  ,
     -- алиас
     alias varchar(200)  ,
     -- доступность
     enabled int
) ;


insert into workgroup(work_group_id, title, alias, enabled) values(1, 'Technical administrator', 'techadmin', 1);
insert into workgroup(work_group_id, title, alias, enabled) values(2, 'Content administrator', 'contentadmin', 1);
insert into workgroup(work_group_id, title, alias, enabled) values(3, 'System administrator', 'sysadmin', 1);
insert into workgroup(work_group_id, title, alias, enabled) values(4, 'Manager', 'manager', 1);

