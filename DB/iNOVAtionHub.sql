/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     28/11/2023 12:21:01                          */
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
   FILE                 varchar(255),
   IMAGE                varchar(255),
   TEXT1                text not null,
   TEXT2                text not null,
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
   TITLE                text not null,
   TEXT                 text not null,
   IMAGE                varchar(255),
   FILE                 varchar(255),
   ISANONYMOUS          bool not null,
   LEVEL                int not null,
   REPORT               tinyint(4),
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
   TAGS                 varchar(1024),
   VOTESCORE            int,
   CREATEDON            datetime,
   TITLE                text not null,
   TEXT                 text not null,
   IMAGE                varchar(255),
   FILE                 varchar(255),
   ISANOUNYMOUS         bool,
   LEVEL                int not null,
   REPORT               tinyint(4),
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
   DATE_OF_BIRTH        datetime not null,
   OCCUPATION           varchar(30) not null,
   USERNAME             varchar(30) not null,
   EMAIL                varchar(30) not null,
   PASSWORD             varchar(3000000) not null,
   USERTYPE             int not null,
   IMAGE                varchar(255),
   primary key (USERID)
);

/*==============================================================*/
/* Table: VOTES                                                 */
/*==============================================================*/

CREATE TABLE votes (
    VOTEID INT AUTO_INCREMENT PRIMARY KEY,
    USERID INT,
    IDEAID INT,
    PROBLEMID INT,
    UPVOTE INT DEFAULT 0,
    DOWNVOTE INT DEFAULT 0,
    CREATEDON DATETIME,
    FOREIGN KEY (USERID) REFERENCES users(USERID) ON DELETE CASCADE,
    FOREIGN KEY (IDEAID) REFERENCES ideas(IDEAID) ON DELETE CASCADE,
    FOREIGN KEY (PROBLEMID) REFERENCES problems(PROBLEMID) ON DELETE CASCADE
);


/*==============================================================*/
/* Table: CHATS                                                 */
/*==============================================================*/

CREATE TABLE CHATS
(
   CHATID               INT NOT NULL AUTO_INCREMENT,
   SENDERID             INT NOT NULL,
   RECEIVERID           INT NOT NULL,
   MESSAGE              TEXT NOT NULL,
   SENT_ON              DATETIME NOT NULL,
   PRIMARY KEY (CHATID),
   FOREIGN KEY (SENDERID) REFERENCES USERS (USERID) ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (RECEIVERID) REFERENCES USERS (USERID) ON DELETE CASCADE ON UPDATE CASCADE
);
      

-- Foreign Keys for the tables

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

alter table FORMS add constraint FK_IDEAS_FORMS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

alter table IDEAS add constraint FK_IDEAS_FORMS foreign key (FORMID)
      references FORMS (FORMID) on delete restrict on update restrict;

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

alter table REVIEWS add constraint FK_COMMENTS_IDEAS foreign key (IDEAID)
      references IDEAS (IDEAID) on delete restrict on update restrict;

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


-- Adding a column to codes to check if the cide has been emailed 

ALTER TABLE CODES
ADD COLUMN EMAILED TINYINT DEFAULT 0 NOT NULL;


-- For the forms table

ALTER TABLE forms
DROP FOREIGN KEY FK_IDEAS_FORMS,
ADD FOREIGN KEY (IDEAID) REFERENCES ideas(IDEAID) ON DELETE SET NULL;


-- Insert tags into the TAGS table

INSERT INTO TAGS (USERID, TAGS) VALUES
(1, 'Project Ideas'),
(1, 'Technology'),
(1, 'Problem Solving'),
(1, 'Sports'),
(1, 'Exercise'),
(1, 'Health'),
(1, 'Infrastructures'),
(1, 'Environment'),
(1, 'School Projects'),
(2, 'Innovation'),
(2, 'Coding'),
(2, 'STEM'),
(2, 'Biology'),
(2, 'Chemistry'),
(2, 'Physics'),
(2, 'Mathematics'),
(2, 'Programming'),
(2, 'App Development'),
(2, 'Robotics'),
(3, 'Sustainable Living'),
(3, 'Renewable Energy'),
(3, 'Climate Change'),
(3, 'Green Solutions'),
(3, 'Recycling'),
(3, 'Conservation'),
(3, 'Environmental Ethics'),
(3, 'Eco-Friendly Innovations'),
(4, 'Education Technology'),
(4, 'E-Learning'),
(4, 'Online Courses'),
(4, 'Educational Apps'),
(4, 'Virtual Learning'),
(4, 'Learning Platforms'),
(4, 'Classroom Innovations'),
(4, 'Educational Games'),
(5, 'Health and Wellness'),
(5, 'Mental Health'),
(5, 'Fitness'),
(5, 'Nutrition'),
(5, 'Healthy Habits'),
(5, 'Well-being'),
(5, 'Yoga'),
(5, 'Meditation'),
(6, 'Community Engagement'),
(6, 'Social Issues'),
(6, 'Volunteerism'),
(6, 'Social Innovation'),
(6, 'Community Projects'),
(6, 'Civic Responsibility'),
(6, 'Empowerment'),
(7, 'Coding Challenges'),
(7, 'Hackathons'),
(7, 'Programming Contests'),
(7, 'Code Debugging'),
(7, 'Algorithm Design'),
(7, 'Software Development'),
(7, 'Coding Communities'),
(7, 'Open Source Projects'),
(8, 'Sportsmanship'),
(8, 'Teamwork'),
(8, 'Leadership'),
(8, 'Competitive Spirit'),
(8, 'Physical Fitness'),
(8, 'Training Techniques'),
(8, 'Sports Innovation'),
(8, 'Game Strategy'),
(9, 'Creative Writing'),
(9, 'Storytelling'),
(9, 'Poetry'),
(9, 'Literature'),
(9, 'Book Recommendations'),
(9, 'Book Reviews'),
(9, 'Writing Prompts'),
(9, 'Literary Discussions'),
(10, 'STEM Education'),
(10, 'Science Experiments'),
(10, 'Math Puzzles'),
(10, 'Technology Trends'),
(10, 'Engineering Projects'),
(10, 'Innovation Workshops'),
(10, 'Scientific Research'),
(10, 'Robotics Competitions'),
(11, 'Art and Design'),
(11, 'Creative Expression'),
(11, 'Visual Arts'),
(11, 'Graphic Design'),
(11, 'Photography'),
(11, 'Digital Art'),
(11, 'Artistic Innovations'),
(11, 'Art Critique'),
(12, 'Cultural Diversity'),
(12, 'Global Awareness'),
(12, 'International Relations'),
(12, 'Cross-Cultural Collaboration'),
(12, 'Multilingualism'),
(12, 'Global Citizenship'),
(12, 'World Issues'),
(12, 'Cultural Exchange'),
(13, 'Astronomy'),
(13, 'Space Exploration');