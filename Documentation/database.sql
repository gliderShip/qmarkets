create table customer
(
    id   INTEGER
        primary key autoincrement,
    name TEXT not null
);

create table account
(
    id         INTEGER not null
        primary key autoincrement,
    customerId INTEGER not null
        references customer,
    balance    INTEGER default 0 not null
);

create table "transaction"
(
    id                   INTEGER
        primary key autoincrement,
    sourceAccountId      INTEGER not null
        references account,
    destinationAccountId INTEGER not null
        references account,
    amount               INTEGER not null
);

