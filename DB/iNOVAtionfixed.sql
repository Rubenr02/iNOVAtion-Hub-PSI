/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     14/11/2023 11:00:26                          */
/*==============================================================*/


drop table if exists COMMENTS;

drop table if exists FORMS;

drop table if exists IDEAS;

drop table if exists MEDIA;

drop table if exists PROBLEMS;

drop table if exists REPORTS;

drop table if exists REVIEWS;

drop table if exists TAGS;

drop table if exists USERS;

drop table if exists VOTES;

/*==============================================================*/
/* Table: COMMENTS                                              */
/*==============================================================*/
create table COMMENTS
(
   COMMENTID            int not null,
   IDEAID               int,
   PROBLEMID            int,
   USERID               int not null,
   CHARACTERS           varchar(100),
   CREATEDON            date,
   VOTESCORE            int,
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
   CREATEDON            date,
   ACCEPTED             bool,
   primary key (FORMID)
);

/*==============================================================*/
/* Table: IDEAS                                                 */
/*==============================================================*/
create table IDEAS
(
   IDEAID               int not null,
   TAGID                int,
   FORMID               int,
   USERID               int not null,
   PROBLEMID            int,
   TAGS                 varchar(1024),
   VOTESCORE            int,
   CREATEDON            date,
   COMPLEXITYLEVEL      int,
   primary key (IDEAID)
);

/*==============================================================*/
/* Table: MEDIA                                                 */
/*==============================================================*/
create table MEDIA
(
   MEDIAID              int not null auto_increment,
   FORMID               int,
   IDEAID               int,
   IMAGE                longblob,
   PROJECT_FILE         longblob,
   TEXT                 varchar(1024000),
   primary key (MEDIAID)
);

/*==============================================================*/
/* Table: PROBLEMS                                              */
/*==============================================================*/
create table PROBLEMS
(
   PROBLEMID            int not null auto_increment,
   USERID               int not null,
   TAGID                int,
   TAGS                 varchar(1024),
   VOTE_SCORE           int,
   CREATEDON            date,
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
   CREATEDON            date,
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
   CREATEDON            date,
   EVALUATION           float,
   primary key (REVIEWID)
);

/*==============================================================*/
/* Table: TAGS                                                  */
/*==============================================================*/
create table TAGS
(
   TAGID                int not null,
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
   USERNAME             varchar(20),
   EMAIL                varchar(30) not null,
   PASSWORD             varchar(30) not null,
   USERTYPE             int,
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
   LIKE_DISLIKE         smallint,
   CREATEDON            date not null,
   primary key (VOTEID)
);

/*==============================================================*/
/* Table: POSTS                                                 */
/*==============================================================*/
create table POSTS
(
   POSTID               int not null auto_increment,
   USERID               int not null,
   TAGID                int,
   TITLE                varchar(255) not null,
   DESCRIPTION          text not null,
   CONTENT              text not null,
   IMAGE                longblob,
   PDF_FILE             longblob,
   CREATEDON            date,
   IS_ANONYMOUS         boolean default 0,
   primary key (POSTID),
   CONSTRAINT FK_POSTS_USERS FOREIGN KEY (USERID) REFERENCES USERS (USERID),
   CONSTRAINT FK_POSTS_TAGS FOREIGN KEY (TAGID) REFERENCES TAGS (TAGID)
);



alter table COMMENTS add constraint FK_COMMENTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table COMMENTS add constraint FK_COMMENTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table COMMENTS add constraint FK_COMMENTS_USERS foreign key (USERID)
      references USERS (USERID);

alter table FORMS add constraint FK_FORMS_USERS foreign key (USERID)
      references USERS (USERID);

alter table FORMS add constraint FK_IDEAS_FORMS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table IDEAS add constraint FK_FORMS_IDEA foreign key (FORMID)
      references FORMS (FORMID);

alter table IDEAS add constraint FK_IDEAS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table IDEAS add constraint FK_TAGS_IDEAS foreign key (TAGID)
      references TAGS (TAGID);

alter table IDEAS add constraint FK_USERS_IDEAS foreign key (USERID)
      references USERS (USERID);

alter table MEDIA add constraint FK_FORMS_MEDIA foreign key (FORMID)
      references FORMS (FORMID);

alter table MEDIA add constraint FK_IDEAS_MEDIA foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table PROBLEMS add constraint FK_PROBLEMS_USERS foreign key (USERID)
      references USERS (USERID);

alter table PROBLEMS add constraint FK_TAGS_PROBLEMS foreign key (TAGID)
      references TAGS (TAGID);

alter table REPORTS add constraint FK_REPORTS_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID);

alter table REPORTS add constraint FK_REPORTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table REPORTS add constraint FK_REPORTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table REPORTS add constraint FK_REPORTS_USERS foreign key (USERID)
      references USERS (USERID);

alter table REVIEWS add constraint FK_REVIEWS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table REVIEWS add constraint FK_REVIEWS_USERS foreign key (USERID)
      references USERS (USERID);

alter table TAGS add constraint FK_TAGS_USERS foreign key (USERID)
      references USERS (USERID);

alter table VOTES add constraint FK_VOTES_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID);

alter table VOTES add constraint FK_VOTES_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table VOTES add constraint FK_VOTES_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table VOTES add constraint FK_VOTES_USERS foreign key (USERID)
      references USERS (USERID);

/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     14/11/2023 11:00:26                          */
/*==============================================================*/


drop table if exists COMMENTS;

drop table if exists FORMS;

drop table if exists IDEAS;

drop table if exists MEDIA;

drop table if exists PROBLEMS;

drop table if exists REPORTS;

drop table if exists REVIEWS;

drop table if exists TAGS;

drop table if exists USERS;

drop table if exists VOTES;

/*==============================================================*/
/* Table: COMMENTS                                              */
/*==============================================================*/
create table COMMENTS
(
   COMMENTID            int not null,
   IDEAID               int,
   PROBLEMID            int,
   USERID               int not null,
   CHARACTERS           varchar(100),
   CREATEDON            date,
   VOTESCORE            int,
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
   CREATEDON            date,
   ACCEPTED             bool,
   primary key (FORMID)
);

/*==============================================================*/
/* Table: IDEAS                                                 */
/*==============================================================*/
create table IDEAS
(
   IDEAID               int not null,
   TAGID                int,
   FORMID               int,
   USERID               int not null,
   PROBLEMID            int,
   TAGS                 varchar(1024),
   VOTESCORE            int,
   CREATEDON            date,
   COMPLEXITYLEVEL      int,
   primary key (IDEAID)
);

/*==============================================================*/
/* Table: MEDIA                                                 */
/*==============================================================*/
create table MEDIA
(
   MEDIAID              int not null auto_increment,
   FORMID               int,
   IDEAID               int,
   IMAGE                longblob,
   PROJECT_FILE         longblob,
   TEXT                 varchar(1024000),
   primary key (MEDIAID)
);

/*==============================================================*/
/* Table: PROBLEMS                                              */
/*==============================================================*/
create table PROBLEMS
(
   PROBLEMID            int not null auto_increment,
   USERID               int not null,
   TAGID                int,
   TAGS                 varchar(1024),
   VOTE_SCORE           int,
   CREATEDON            date,
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
   CREATEDON            date,
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
   CREATEDON            date,
   EVALUATION           float,
   primary key (REVIEWID)
);

/*==============================================================*/
/* Table: TAGS                                                  */
/*==============================================================*/
create table TAGS
(
   TAGID                int not null,
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
   USERNAME             varchar(20),
   EMAIL                varchar(30) not null,
   PASSWORD             varchar(30) not null,
   USERTYPE             int,
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
   LIKE_DISLIKE         smallint,
   CREATEDON            date not null,
   primary key (VOTEID)
);

/*==============================================================*/
/* Table: POSTS                                                 */
/*==============================================================*/
create table POSTS
(
   POSTID               int not null auto_increment,
   USERID               int not null,
   TAGID                int,
   TITLE                varchar(255) not null,
   DESCRIPTION          text not null,
   CONTENT              text not null,
   IMAGE                longblob,
   PDF_FILE             longblob,
   CREATEDON            date,
   IS_ANONYMOUS         boolean default 0,
   primary key (POSTID),
   CONSTRAINT FK_POSTS_USERS FOREIGN KEY (USERID) REFERENCES USERS (USERID),
   CONSTRAINT FK_POSTS_TAGS FOREIGN KEY (TAGID) REFERENCES TAGS (TAGID)
);



alter table COMMENTS add constraint FK_COMMENTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table COMMENTS add constraint FK_COMMENTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table COMMENTS add constraint FK_COMMENTS_USERS foreign key (USERID)
      references USERS (USERID);

alter table FORMS add constraint FK_FORMS_USERS foreign key (USERID)
      references USERS (USERID);

alter table FORMS add constraint FK_IDEAS_FORMS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table IDEAS add constraint FK_FORMS_IDEA foreign key (FORMID)
      references FORMS (FORMID);

alter table IDEAS add constraint FK_IDEAS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table IDEAS add constraint FK_TAGS_IDEAS foreign key (TAGID)
      references TAGS (TAGID);

alter table IDEAS add constraint FK_USERS_IDEAS foreign key (USERID)
      references USERS (USERID);

alter table MEDIA add constraint FK_FORMS_MEDIA foreign key (FORMID)
      references FORMS (FORMID);

alter table MEDIA add constraint FK_IDEAS_MEDIA foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table PROBLEMS add constraint FK_PROBLEMS_USERS foreign key (USERID)
      references USERS (USERID);

alter table PROBLEMS add constraint FK_TAGS_PROBLEMS foreign key (TAGID)
      references TAGS (TAGID);

alter table REPORTS add constraint FK_REPORTS_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID);

alter table REPORTS add constraint FK_REPORTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table REPORTS add constraint FK_REPORTS_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table REPORTS add constraint FK_REPORTS_USERS foreign key (USERID)
      references USERS (USERID);

alter table REVIEWS add constraint FK_REVIEWS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table REVIEWS add constraint FK_REVIEWS_USERS foreign key (USERID)
      references USERS (USERID);

alter table TAGS add constraint FK_TAGS_USERS foreign key (USERID)
      references USERS (USERID);

alter table VOTES add constraint FK_VOTES_COMMENTS foreign key (COMMENTID)
      references COMMENTS (COMMENTID);

alter table VOTES add constraint FK_VOTES_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID);

alter table VOTES add constraint FK_VOTES_PROBLEMS foreign key (PROBLEMID)
      references PROBLEMS (PROBLEMID);

alter table VOTES add constraint FK_VOTES_USERS foreign key (USERID)
      references USERS (USERID);

INSERT INTO TAGS (TAGID, TAGS) VALUES
(1, 'Technology'),
(2, 'Innovation'),
(3, 'Environment'),
(4, 'Health'),
(5, 'Education'),
(6, 'Business'),
(7, 'Science'),
(8, 'Sustainability'),
(9, 'Community'),
(10, 'Social Impact'),
(11, 'Art'),
(12, 'Design'),
(13, 'Engineering'),
(14, 'Economics'),
(15, 'Communication'),
(16, 'Transportation'),
(17, 'Energy'),
(18, 'Food'),
(19, 'Agriculture'),
(20, 'Urban Planning'),
(21, 'Architecture'),
(22, 'Space Exploration'),
(23, 'Robotics'),
(24, 'Artificial Intelligence'),
(25, 'Healthcare'),
(26, 'Medicine'),
(27, 'Education Technology'),
(28, 'Climate Change'),
(29, 'Renewable Energy'),
(30, 'Social Innovation'),
(31, 'Financial Technology (Fintech)'),
(32, 'Digital Transformation'),
(33, 'Cybersecurity'),
(34, 'Data Science'),
(35, 'Inclusive Design'),
(36, 'Diversity and Inclusion'),
(37, 'Entrepreneurship'),
(38, 'Start-ups'),
(39, 'Crisis Management'),
(40, 'Human Rights'),
(41, 'Equality'),
(42, 'Access to Education'),
(43, 'Access to Healthcare'),
(44, 'Poverty'),
(45, 'Gender Equality'),
(46, 'Climate Action'),
(47, 'Clean Water'),
(48, 'Renewable Transportation'),
(49, 'Digital Inclusion'),
(50, 'Smart Cities'),
(51, 'Globalization'),
(52, 'Virtual Reality'),
(53, 'Augmented Reality'),
(54, 'Internet of Things (IoT)'),
(55, 'E-commerce'),
(56, 'Remote Work'),
(57, 'Artificial General Intelligence (AGI)'),
(58, 'Biotechnology'),
(59, 'Space Travel'),
(60, 'Renewable Materials'),
(61, 'Circular Economy'),
(62, 'Renewable Agriculture'),
(63, 'Biodegradable'),
(64, 'Elderly Care'),
(65, 'Public Health'),
(66, 'Smart Manufacturing'),
(67, 'Supply Chain Management'),
(68, 'Renewable Resources'),
(69, 'Digital Healthcare'),
(70, 'Alternative Energy'),
(71, 'Inclusive Education'),
(72, 'Smart Agriculture'),
(73, 'Blockchain Technology'),
(74, 'Clean Energy'),
(75, 'Infrastructures'),
(76, 'Microfinance'),
(77, 'Cultural Diversity'),
(78, 'Renewable Fashion'),
(79, 'Environmental Conservation'),
(80, 'Renewable Water Solutions'),
(81, 'Innovative Transportation'),
(82, 'Human-Centered Design'),
(83, 'Renewable Energy Policy'),
(84, 'Accessible Technology'),
(85, 'Smart Grids'),
(86, 'Circular Fashion'),
(87, 'Sustainable Tourism'),
(88, 'Renewable Water Technologies'),
(89, 'Clean Air Technologies'),
(90, 'Renewable Energy for Developing Countries'),
(91, 'Innovative Infrastructure Solutions'),
(92, 'Eco-friendly Transportation'),
(93, 'Innovative Waste Management'),
(94, 'Sustainable Supply Chain'),
(95, 'Digital Healthcare Solutions'),
(96, 'Elderly Care Technologies'),
(97, 'Smart Retail'),
(98, 'Renewable Energy Education'),
(99, 'Innovative Solutions for Disabilities'),
(100, 'Global Connectivity');
