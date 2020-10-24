-- Adminer 4.7.6 PostgreSQL dump

DROP TABLE IF EXISTS "domains";
DROP SEQUENCE IF EXISTS domains_id_seq;
CREATE SEQUENCE domains_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."domains" (
    "id" integer DEFAULT nextval('domains_id_seq') NOT NULL,
    "domain" character varying NOT NULL,
    "count_users" integer NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "users";
DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."users" (
    "id" integer DEFAULT nextval('users_id_seq') NOT NULL,
    "name" character varying NOT NULL,
    "city" character varying NOT NULL,
    "domain" character varying NOT NULL,
    "path" character varying
) WITH (oids = false);


-- 2020-10-24 12:18:55.033848+00
