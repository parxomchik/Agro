drop table IF  EXISTS workuser;
create table workuser
(
     --
     work_user_id int  primary key ,
     -- айди клиента
     client_id int  ,
     -- айди группы
     work_group_id int
) ;

insert into workuser(work_user_id, client_id, work_group_id) values(1, 1, 2);
insert into workuser(work_user_id, client_id, work_group_id) values(2, 1, 3);
insert into workuser(work_user_id, client_id, work_group_id) values(3, 1, 4);
insert into workuser(work_user_id, client_id, work_group_id) values(4, 1, 5);

