DROP TABLE IF EXISTS library;
DROP TABLE IF EXISTS rang;
DROP TABLE IF EXISTS user;

CREATE TABLE library (
    idLibrary       INTEGER,
    refUser         INTEGER,
    name            VARCHAR(255),
    description     VARCHAR(255),
    home            VARCHAR(255),
    release         VARCHAR(255),
    documentation   VARCHAR(255),
    issues          VARCHAR(255),
    time            TIMESTAMP,
    valid           INTEGER,
    PRIMARY KEY(idLibrary)
);

CREATE TABLE rang (
    idRang          INTEGER,
    RangLabel       VARCHAR(255),
    RangClass       VARCHAR(255),
    PRIMARY KEY(idRang)
);

CREATE TABLE user (
    idUser          INTEGER,
    username        VARCHAR(255),
    password        VARCHAR(255),
    email           VARCHAR(255),
    rang            INTEGER,
    PRIMARY KEY(idUser)

);

INSERT INTO rang( idRang, RangLabel, RangClass) VALUES (null , "Banned" ,"inverse");
INSERT INTO rang( idRang, RangLabel, RangClass) VALUES (null , "Register","success");
INSERT INTO rang( idRang, RangLabel, RangClass) VALUES (null , "Administrator","important");

INSERT INTO user( idUser, username, password, email, rang) VALUES (null,"camael", "4438ce731657057ba02736526d2018bfac7d4971" ,"thehawk@hoa-project.net",3);

