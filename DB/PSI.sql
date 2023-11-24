/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     21/11/2023 15:47:38                          */
/*==============================================================*/


drop table if exists CODES;

drop table if exists COMMENTS;

drop table if exists FORMS;

drop table if exists IDEAS;

drop table if exists PROBLEMS;

drop table if exists REPORTS;

drop table if exists REVIEWS;

drop table if exists TAGS;

drop table if exists USERS;

drop table if exists VOTES;

/*==============================================================*/
/* Table: CODES                                                 */
/*==============================================================*/
create table CODES
(
   CODE_ID              int not null auto_increment,
   USERID               int not null,
   CODE                 varchar(50) not null,
   CREATED_ON           datetime not null,
   TYPE_OF_USER         int not null,
   primary key (CODE_ID)
);

/*==============================================================*/
/* Table: COMMENTS                                              */
/*==============================================================*/
create table COMMENTS
(
   COMMENTID            int not null auto_increment,
   IDEAID               int,
   PROBLEMID            int,
   USERID               int not null,
   CHARACTERS           varchar(300) not null,
   CREATEDON            datetime not null,
   VOTESCORE            int not null,
   COMMENT_TYPE         smallint not null,
   primary key (COMMENTID)
);

/*==============================================================*/
/* Table: FORMS                                                 */
/*==============================================================*/
create table FORMS
(
   FORMID               int not null auto_increment,
   IDEAID               int,
   USERID               int not null,
   CREATEDON            datetime,
   ACCEPTED             bool,
   FILE                 longblob,
   IMAGE                longblob,
   primary key (FORMID)
);

/*==============================================================*/
/* Table: IDEAS                                                 */
/*==============================================================*/
create table IDEAS
(
   IDEAID               int not null auto_increment,
   TAGID                int,
   FORMID               int,
   USERID               int not null,
   PROBLEMID            int,
   VOTESCORE            int not null,
   CREATEDON            datetime not null,
   COMPLEXITYLEVEL      int not null,
   TITTLE               text not null,
   TEXT                 text not null,
   IMAGE                longblob,
   FILE                 longblob,
   ISANONYMOUS          bool not null,
   primary key (IDEAID)
);

/*==============================================================*/
/* Table: PROBLEMS                                              */
/*==============================================================*/
create table PROBLEMS
(
   PROBLEMID            int not null auto_increment,
   USERID               int not null,
   TAGID                int,
   VOTE_SCORE           int,
   CREATEDON            datetime,
   TITTLE               text not null,
   TEXT                 text not null,
   IMAGE                longblob,
   FILE                 longblob,
   ISANONYMOUS          bool not null,
   primary key (PROBLEMID)
);

/*==============================================================*/
/* Table: REPORTS                                               */
/*==============================================================*/
create table REPORTS
(
   REPORTID             int not null auto_increment,
   IDEAID               int,
   COMMENTID            int,
   PROBLEMID            int,
   USERID               int not null,
   CREATEDON            datetime not null,
   primary key (REPORTID)
);

/*==============================================================*/
/* Table: REVIEWS                                               */
/*==============================================================*/
create table REVIEWS
(
   REVIEWID             int not null auto_increment,
   IDEAID               int not null,
   USERID               int not null,
   CREATEDON            datetime,
   EVALUATION           int,
   NOTES                text,
   primary key (REVIEWID)
);

/*==============================================================*/
/* Table: TAGS                                                  */
/*==============================================================*/
create table TAGS
(
   TAGID                int not null auto_increment,
   USERID               int not null,
   TAGS                 varchar(1024),
   primary key (TAGID)
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS
(
   USERID               int not null auto_increment,
   FIRSTNAME            varchar(30) not null,
   LASTNAME             varchar(30) not null,
   DATE_OF_BIRTH        date not null,
   OCCUPATION           varchar(30) not null,
   USERNAME             varchar(30) not null,
   EMAIL                varchar(30) not null,
   PASSWORD             varchar(64) not null,
   USERTYPE             int,
   IMAGE                varchar(255) not null,
   primary key (USERID)
);

/*==============================================================*/
/* Table: VOTES                                                 */
/*==============================================================*/
create table VOTES
(
   VOTEID               int not null auto_increment,
   COMMENTID            int,
   IDEAID               int,
   USERID               int not null,
   PROBLEMID            int,
   UPVOTE               int,
   DOWNVOTE             int,
   CREATEDON            date not null,
   primary key (VOTEID)
);

alter table CODES add constraint FK_RELATIONSHIP_25 foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table COMMENTS add constraint FK_COMMENTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

alter table COMMENTS add constraint FK_COMMENTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID) on delete restrict on update restrict;

alter table COMMENTS add constraint FK_COMMENTS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table FORMS add constraint FK_FORMS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table IDEAS add constraint FK_IDEAS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID) on delete restrict on update restrict;

alter table IDEAS add constraint FK_TAGS_IDEAS foreign key (TAGID)
      references TAGS (TAGID) on delete restrict on update restrict;

alter table IDEAS add constraint FK_USERS_IDEAS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table PROBLEMS add constraint FK_PROBLEMS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table PROBLEMS add constraint FK_TAGS_PROBLEMS foreign key (TAGID)
      references TAGS (TAGID) on delete restrict on update restrict;

alter table REPORTS add constraint FK_REPORTS_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID) on delete restrict on update restrict;

alter table REPORTS add constraint FK_REPORTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

alter table REPORTS add constraint FK_REPORTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID) on delete restrict on update restrict;

alter table REPORTS add constraint FK_REPORTS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table REVIEWS add constraint FK_REVIEWS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

alter table REVIEWS add constraint FK_REVIEWS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table TAGS add constraint FK_TAGS_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

alter table VOTES add constraint FK_VOTES_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID) on delete restrict on update restrict;

alter table VOTES add constraint FK_VOTES_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

alter table VOTES add constraint FK_VOTES_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID) on delete restrict on update restrict;

alter table VOTES add constraint FK_VOTES_USERS foreign key (USERID)
      references USERS (USERID) on delete restrict on update restrict;

