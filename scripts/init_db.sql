create database if not exists gestion_commande;
use gestion_commande;

-- drop all constraints if exists
alter table details_commande drop foreign key if exists details_commande_fk;
alter table details_commande drop foreign key if exists details_produit_fk;

alter table commande drop foreign key if exists commande_client_fk;


-- create tables

CREATE OR REPLACE TABLE client (
    num_client      int auto_increment,
    nom             varchar(64),
    raison_social   varchar(64),
    adresse         text,
    ville           varchar(64),
    pays            varchar(64),
    telephone       varchar(64),
    primary key client_pk (num_client)
);

CREATE OR REPLACE TABLE produit (
    ref_produit     int auto_increment,
    nom             varchar(64),
    prix_unitaire   double,
    qte_stockee     int,
    indisponible    enum('0','1'),
    primary key produit_pk (ref_produit)
);

CREATE OR REPLACE TABLE commande (
    num_commande    int auto_increment,
    num_client      int,
    date_commande   date,
    primary key commande_pk (num_commande),
    foreign key commande_client_fk (num_client) references client(num_client) on delete cascade
);

CREATE OR REPLACE TABLE details_commande (
    num_commande    int,
    ref_produit     int,
    qte_commandee   int,
    primary key details_pk (num_commande, ref_produit),
    foreign key details_commande_fk (num_commande) references commande(num_commande) on delete cascade,
    foreign key details_produit_fk (ref_produit) references produit(ref_produit)
);

create or replace table archive (
    select * from commande where 1=0
);

-- a trigger to archive the commandes of deleted clients
create trigger archive_trigger before delete on client for each row
begin
    insert into archive select * from commande where num_client = old.num_client;
end;

-- populate tables with some records

insert into client (num_client, nom, raison_social, adresse, ville, pays, telephone)
values  (1, 'Abdul-Muttalib Bahar', 'Johnston-Lemke', '1547 Chive Drive', 'Hammam Sousse', 'Tunisia', '+216-276-315-6431'),
        (2, 'Nazih Shadid', 'Anderson Group', '420 Bartelt Alley', 'Bi’r al Ḩufayy', 'Tunisia', '+216-730-456-1011'),
        (3, 'Riyad Fakhoury', 'Romaguera, Franecki and Runolfsdottir', '58171 Delaware Way', 'Bhalil', 'Morocco', '+212-663-972-0894'),
        (4, 'Diya Moghadam', 'Powlowski and Sons', '030 Columbus Drive', 'Gueltat Zemmour', 'Morocco', '+212-161-586-6096'),
        (5, 'Ghiyath Shadid', 'Sawayn-Runte', '480 Derek Parkway', 'Aoufous', 'Morocco', '+212-265-754-8925'),
        (6, 'Sayyid Moghadam', 'Predovic and Sons', '5677 Lighthouse Bay Junction', 'Gafsa', 'Tunisia', '+216-579-438-4672'),
        (7, 'Ata Touma', 'Bernhard-Glover', '98740 North Road', 'Majāz al Bāb', 'Tunisia', '+216-702-954-7325'),
        (8, 'Abraha Shamoon', 'Turner-Fay', '86 Longview Hill', 'Bhalil', 'Morocco', '+212-946-659-0846'),
        (9, 'Firas Saliba', 'Emard, Bruen and Graham', '51 Maple Drive', 'Banbalah', 'Tunisia', '+216-578-790-3066'),
        (10, 'Asad Saliba', 'Casper, Jakubowski and Balistreri', '41482 Kings Hill', 'Taghazout', 'Morocco', '+212-719-777-7207'),
        (11, 'Haytham Sabbag', 'Dooley, Cartwright and Reilly', '64181 Golf Crossing', 'Jendouba', 'Tunisia', '+216-504-887-3125'),
        (12, 'Abdul-Salam Harb', 'Mitchell, Gislason and Kuhic', '77 Garrison Pass', 'Zarzis', 'Tunisia', '+216-341-607-9349'),
        (13, 'Abdul-Tawwab Issa', 'Jacobi Inc', '11 Warbler Parkway', 'Boudinar', 'Morocco', '+212-589-875-8509'),
        (14, 'Amr Shadid', 'Dooley-Wisozk', '0386 Carioca Alley', 'Casablanca', 'Morocco', '+212-105-816-7411'),
        (15, 'Kaliq Antoun', 'Mosciski-Hegmann', '768 Milwaukee Lane', 'Tata', 'Morocco', '+212-163-429-2436'),
        (16, 'Hakem Maroun', 'Schmidt-Dickens', '3 Barnett Point', 'Fezna', 'Morocco', '+212-100-901-2375'),
        (17, 'Musa Kouri', 'Hoeger-Yundt', '01516 Burrows Junction', 'Menzel Abderhaman', 'Tunisia', '+216-610-776-2070'),
        (18, 'Muhammad Ghannam', 'Hirthe and Sons', '8 Heath Center', 'Oulmes', 'Morocco', '+212-521-110-2417'),
        (19, 'Muhammad Atiyeh', 'Hermiston LLC', '58051 Aberg Parkway', 'Siliana', 'Tunisia', '+216-479-829-5848'),
        (20, 'Hilel Maroun', 'Hahn, Rowe and Rosenbaum', '08 Ryan Circle', 'Oulmes', 'Morocco', '+212-230-953-8018'),
        (21, 'Sabih Halabi', 'Mayert-Mohr', '50589 Florence Way', 'Mohammedia', 'Morocco', '+212-392-587-6575'),
        (22, 'Basil Qureshi', 'Dooley, Schumm and Zemlak', '76464 Porter Terrace', 'Moulay Abdallah', 'Morocco', '+212-903-556-2119'),
        (23, 'Yasar Kassab', 'Kulas, Little and Hintz', '4954 Brickson Park Park', 'Temara', 'Morocco', '+212-630-338-3873'),
        (24, 'Asim Safar', 'Stiedemann Group', '72 Cardinal Plaza', 'Tamanar', 'Morocco', '+212-524-240-7782');

-- populate table product with 20 different product
insert into produit (nom, prix_unitaire, qte_stockee, indisponible)
values  ('Product 1', 10, 100, '0'),
        ('Product 2', 20, 200, '0'),
        ('Product 3', 30, 300, '0'),
        ('Product 4', 40, 400, '0'),
        ('Product 5', 50, 500, '0'),
        ('Product 6', 60, 600, '0'),
        ('Product 7', 70, 700, '0'),
        ('Product 8', 80, 800, '0'),
        ('Product 9', 90, 900, '0'),
        ('Product 10', 100, 1000, '0'),
        ('Product 11', 110, 1100, '0'),
        ('Product 12', 120, 1200, '0'),
        ('Product 13', 130, 1300, '0'),
        ('Product 14', 140, 1400, '0'),
        ('Product 15', 150, 1500, '0'),
        ('Product 16', 160, 1600, '0'),
        ('Product 17', 170, 1700, '0'),
        ('Product 18', 180, 1800, '0'),
        ('Product 19', 190, 1900, '0'),
        ('Product 20', 200, 2000, '0');




-- populate table commande
insert into commande (num_client, date_commande)
values  (1, '2023-01-01'),
        (2, '2018-01-02'),
        (3, '2018-01-03'),
        (4, '2018-01-04'),
        (5, '2018-01-05'),
        (6, '2018-01-06'),
        (7, '2018-01-07'),
        (8, '2018-01-08'),
        (9, '2018-01-09'),
        (10, '2018-01-10'),
        (11, '2018-01-11'),
        (12, '2018-01-12'),
        (13, '2018-01-13'),
        (14, '2018-01-14'),
        (15, '2018-01-15'),
        (16, '2018-01-16'),
        (17, '2018-01-17');

-- populate table ligne_commande
insert into details_commande (num_commande, ref_produit, qte_commandee)
values  (1, 1, 1),
        (2, 2, 2),
        (3, 3, 3),
        (4, 4, 4),
        (5, 5, 5),
        (6, 6, 6),
        (7, 7, 7),
        (8, 8, 8),
        (9, 9, 9),
        (10, 10, 10),
        (11, 11, 11),
        (12, 12, 12),
        (13, 13, 13),
        (14, 14, 14),
        (15, 15, 15),
        (16, 16, 16),
        (17, 17, 17);

