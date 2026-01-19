create table utilizatori
(
    id                int auto_increment
        primary key,
    nume              varchar(100)                        not null,
    email             varchar(100)                        not null,
    telefon           varchar(20)                         not null,
    parola            varchar(255)                        not null,
    rol               enum ('client', 'sofer')            not null,
    nr_inmatriculare  varchar(20)                         null,
    culoare_masina    varchar(30)                         null,
    model_masina      varchar(50)                         null,
    data_inregistrare timestamp default CURRENT_TIMESTAMP not null,
    constraint email
        unique (email)
);

create table curse
(
    id              int auto_increment
        primary key,
    client_id       int                                                                                                    not null,
    sofer_id        int                                                                                                    null,
    plecare         varchar(255)                                                                                           not null,
    destinatie      varchar(255)                                                                                           not null,
    cost            decimal(10, 2)                                                               default 0.00              null,
    status          enum ('cauta_sofer', 'acceptata', 'in_desfasurare', 'finalizata', 'anulata') default 'cauta_sofer'     null,
    rating          int                                                                                                    null,
    recenzie        text                                                                                                   null,
    data_cursa      timestamp                                                                    default CURRENT_TIMESTAMP not null,
    rating_client   int                                                                                                    null,
    recenzie_client text                                                                                                   null,
    constraint curse_ibfk_1
        foreign key (client_id) references utilizatori (id),
    constraint curse_ibfk_2
        foreign key (sofer_id) references utilizatori (id)
);

create index client_id
    on curse (client_id);

create index sofer_id
    on curse (sofer_id);


