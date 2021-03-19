drop table if exists bhp_medicijnen;
create table bhp_medicijnen
(
    `id`               int not null primary key auto_increment,
    `Behandelplan_id`               int not null,
    `name`             text,
    `type`             enum ('Supplementen','Medicijnen','Infusen', 'Overig', 'Anders'),
    `dosering_amount`  int,
    `dosering_unit`    int,
    `pathway_01`          int,
    `pathway_02`          int,
    `pathway_03`          int,
    `pathway_04`          int,
    `pathway_05`          int,
    `merk`             text,
    `lnname`           text,
    `ingredienten_01`     int,
    `ingredienten_02`     int,
    `ingredienten_03`     int,
    `ingredienten_04`     int,
    `ingredienten_05`     int,
    `contraindicaties_01` int,
    `contraindicaties_02` int,
    `contraindicaties_03` int,
    `contraindicaties_04` int,
    `contraindicaties_05` int,
    `welkecontra`      text,
    `uitleg`           text,
    `upmerking`        text
);
drop table if exists bhp_pathway;
create table bhp_pathway
(
    `id`      int not null primary key auto_increment,
    `name`    text,
    `up/down` text

);
drop table if exists bhp_iingredienten;
create table bhp_ingredienten
(
    `id`            int             not null primary key auto_increment,
    `name`          text,
    `extranal_info` text default '',
    `notes`         text default '' not null

);
drop table if exists bhp_dosering;
create table bhp_dosering
(
    `id`    int not null primary key auto_increment,
    `units` text
);
drop table if exists bhp_contraindicaties;
create table bhp_contraindicaties
(
    `id`     int not null primary key auto_increment,
    `name`   text,
    ing_id   int,
    reaction text
);
drop table if exists behandleplanen;
create table `behandleplanen`
(
    `id`         int not null primary key auto_increment,
    `name`       text,
    `medicijnen` int,
    `info`       text,
    `pdf`        text


);
