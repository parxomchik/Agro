-- drop role agroup;
-- drop user agro;
-- drop database agro;

create database agro;

grant all on agro.* to agro@localhost identified by 'agro';
flush privileges;

