-- drop role agroup;
-- drop user agro;
-- drop database agro;

create role agroup;

create user agro password 'agro' in group agroup ;

CREATE DATABASE agro OWNER agroup;