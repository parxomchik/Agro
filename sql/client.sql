
drop table if exists client;

create table client
(
     client_id INT not null
   , username varchar(100)
   , password varchar(100)
   , enabled int
);

grant all on client to agroup;

insert into client(client_id, username, password, enabled) values(1, 'wolf', 'wolf2', 1);